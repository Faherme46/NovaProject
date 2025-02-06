<?php

namespace App\Http\Controllers;

use App\Exports\AsambleaExport;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;


use App\Http\Controllers\FileController;
use App\Imports\AsambleaImport;
use App\Models\Asamblea;
use App\Models\Control;

use App\Imports\PersonasImport;
use App\Imports\PrediosImport;

use App\Imports\PredioWithRegistro;
use App\Imports\UsersImport;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException;

class AsambleaController extends Controller
{

    protected $prediosController;
    protected $sessionController;
    protected $fileController;

    public function __construct()
    {
        $this->prediosController = new PrediosController();
        $this->sessionController = new SessionController;
        $this->fileController = new FileController;
    }


    public function store(Request $request)
    {

        // Obtener los datos originales del request
        $input = $request->all();

        // Modificar los datos del request
        if ($input['registro'] === 'true') {
            $input['registro'] = true;
        } else {
            $input['registro'] = false;
        }

        $input['name'] = $input['folder'] . '_' . str_replace('-', '.', $input['fecha']);
        if (array_key_exists('signature', $input)) {

            $input['signature'] = true;
        }
        $request->merge($input);

        $request->validate([
            'folder' => 'required',
            'lugar' => 'required',
            'fecha' => 'required|date',
            'hora' => 'required|date_format:H:i',
            'controles' => 'required',
            'registro' => 'required|boolean',

        ], [
            'folder.required' => 'Debe seleccionar un cliente.',
        ]);

        try {
            $imports = $this->importPredios($request->folder, $request->registro);

            if ($imports == 200) {
                $asamblea = Asamblea::create($request->all());
                $asamblea->name = str_replace(' ', '_', $request->name);
                $asamblea->save();
                $this->sessionController->setSession($asamblea->id_asamblea, $asamblea->folder);
                $data = [
                    'id_asamblea'   => $asamblea->id_asamblea,
                    'inRegistro'    => $asamblea->registro,
                    'asamblea' => $asamblea
                ];

                Cache::putMany($data);
                Control::factory()->count($asamblea->controles)->create();
                $this->fileController->getAsambleaFolderPath();
            }
        } catch (QueryException $qe) {
            $this->sessionController->destroyOnError();
            if ($qe->errorInfo[1] == 1062) { // 1062 es el código de error para duplicados
                return back()->withErrors('Ya existe una asamblea en la misma fecha.');
            } else {
                return back()->withErrors($qe->getMessage());
            }
        } catch (Exception $e) {
            $this->sessionController->destroyOnError();
            return back()->withErrors($e->getMessage());
        }

        return redirect()->route('home')->with('success', 'Asamblea creada con éxito, documentos importados.');
    }



    public function updateAsamblea(Request $request)
    {
        $messages = [
            '*.required' => 'El campo :attribute no puede estar vacio',
            'fecha.date' => 'El campo :attribute debe ser una fecha válida.',
            '*.date_format' => 'El campo :attribute no corresponde con el formato :format.',
        ];
        $request->validate([
            'lugar' => 'required',
            'fecha' => 'required|date',
            'hora' => 'required',
            'controles' => 'required',
        ], $messages);

        $asamblea = Asamblea::find($request->id_asamblea);
        $controles = intval($request->controles);
        if ($controles >= $asamblea->controles) {
            for ($i = $asamblea->controles + 1; $i < $request->controles + 1; $i++) {
                Control::firstOrCreate([
                    'id' => $i,
                    'state' => 4,
                    'sum_coef' => 0,
                    'sum_coef_can' => 0,
                    'predios_vote' => 0
                ]);
            }
        }else{
            return back()->withErrors('No se puede reducir el numero de controles')->withInput();
        }
        if ($asamblea) {
            $asamblea->update($request->all());
            if (!$request->signature) {
                $asamblea->signature = false;
                $asamblea->save();
            }
            cache(['asamblea' => $asamblea->toArray()]);
            return back()->with('success', 'Asamblea actualizada con éxito.');
        } else {
            return back()->withErrors('No se encontro la asamblea')->withInput();
        }
    }

    public function destroy($id)
    {
        $asamblea = Asamblea::findOrFail($id);
        $asamblea->delete();
        cache()->forget('asamblea');
        return redirect()->route('home');
    }


    public function getName($id)
    {

        if ($id) {
            $asamblea = Asamblea::findOrFail($id);
            return $asamblea->folder;
        } else {
            return ('-');
        }
    }

    public function getOne($id)
    {
        if ($id) {
            $asamblea = Asamblea::findOrFail($id);
            return $asamblea;
        } else {
            return (null);
        }
    }



    //Metodos de manejo de archivos
    public function importPredios(String $file, $registro)
    {
        try {
            $externalFilePathPredios = 'C:/Asambleas/Clientes/' . $file . '/predios.xlsx';
            $externalFilePathPersonas = 'C:/Asambleas/Clientes/' . $file . '/personas.xlsx';


            if (!file_exists($externalFilePathPersonas) && $registro) {
                throw new FileNotFoundException("El archivo no se encontró en la ruta: {$externalFilePathPersonas}");
            }
            if (!file_exists($externalFilePathPredios)) {
                throw new FileNotFoundException("El archivo no se encontró en la ruta: {$externalFilePathPredios}");
            }
            if ($registro) {
                Excel::import(new PersonasImport, $externalFilePathPersonas);
                Excel::import(new PredioWithRegistro, $externalFilePathPredios);
            } else {
                Excel::import(new PrediosImport, $externalFilePathPredios);
            }
            if(file_exists('C:/Asambleas/usuarios.xlsx')){
                Excel::import(new UsersImport, 'C:/Asambleas/usuarios.xlsx');
            }


            return 200;
        } catch (ValidationException $e) {
            // Manejar excepciones específicas de validación de Excel
            $failures = $e->failures();
            throw new Exception('Error: ' . $failures[1]);
            //Excepcion por archivo inexistente
        } catch (FileNotFoundException $e) {
            throw new Exception('Error: ' . $e->getMessage());
        } catch (Exception $e) {

            // Manejar cualquier otra excepción
            throw new Exception($e->getMessage());
        }
    }

    public function deleteAsamblea(Request $request)
    {
        $request->validate([
            'password' => 'required',
        ], [
            'password.required' => 'Se requiere la contraseña'
        ]);

        $password = $request->input('password');
        if ($this->validatePassword($password)) {
            $this->deleteAsambleaFiles($request->name);
            Asamblea::where('name', $request->name)->delete();
            return back()->with('success', 'Informacion eliminada correctamente');
        } else {
            return back()->with('error', 'La contraseña es incorrecta');
        }
    }
    public function deleteAsambleaFiles($asambleaName)
    {
        $disk = Storage::disk('results');
        $path = "backups\\" . $asambleaName . '.sql';
        if (Storage::exists("public/backups/" . $asambleaName . '.sql')) {
            Storage::delete("public/backups/" . $asambleaName . '.sql');
        }

        // Verifica si el disco existe
        if ($disk->exists($asambleaName)) {
            $this->deleteDirectory($disk, $asambleaName);
        }
    }
    private function deleteDirectory($disk, $directory)
    {
        // Obtén todos los archivos y subdirectorios en el directorio actual
        $files = $disk->allFiles($directory);
        $directories = $disk->allDirectories($directory);

        // Borra todos los archivos
        foreach ($files as $file) {
            $disk->delete($file);
        }

        // Borra todos los subdirectorios recursivamente
        foreach ($directories as $subDirectory) {
            $this->deleteDirectory($disk, $subDirectory);
        }

        // Finalmente, borra el directorio actual si está vacío
        $disk->deleteDirectory($directory);
    }
    public function validatePassword($password)
    {


        // Comparar con la contraseña del usuario autenticado
        if (Hash::check($password, Auth::user()->password)) {
            return true;
        } else {
            return false;
        }
    }






    public function importAsambleaFile($folder)
    {

        try {

            $data = Storage::disk('externalAsambleas')->get($folder . '/info.json');
            if (!$data){
                return redirect()->route('asambleas')->with('error', 'No se encontro el archivo "info.json" de '.$folder);
            }
            $values = json_decode($data, true);
            // if ($values['h_cierre']) {
            //     $time = Carbon::parse($values['h_cierre']);


            //     $hora = $time->format('H:i:s');
            //     $values['h_cierre'] = $hora;
            // }
            unset($values['id_asamblea']);
            unset($values['created_at']);
            unset($values['updated_at']);


            // if($values['ordenDia']){
            //     $values['ordenDia']=$values['ordenDia']);

            //     // $values['ordenDia']=null;
            // }
            $asamblea = Asamblea::updateOrCreate(
                $values
            );

        } catch (FileNotFoundException $e) {
            return redirect()->route('asambleas')->with('error', '3 '. $e->getMessage());
        } catch (Exception $e) {
            // Manejar cualquier otra excepción
            return redirect()->route('asambleas')->with('error', '3 '. $e->getMessage());
        }
    }


    public function loadAsambleas()
    {

        $externalFolderPath = config('filesystems.disks.externalAsambleas.root');
        // Verifica si la carpeta existe
        if (is_dir($externalFolderPath)) {
            // Obtén una lista de subcarpetas
            $subfolders = array_filter(glob($externalFolderPath . '/*'), 'is_dir');

            // Extrae solo los nombres de las carpetas
            $subfolderNames = array_map('basename', $subfolders);
        } else {
            return redirect()->route('asambleas')->withErrors(['error' => 'La carpeta externa no existe.']);
        }

        $folderStorage = Asamblea::all()->pluck('name')->toArray();
        $foldersDiff = array_diff($subfolderNames,  $folderStorage);
        $foldersDelete = array_diff($folderStorage, $subfolderNames);

        try {
            foreach ($foldersDelete as $name) {
                Asamblea::where('name',$name)->delete();
            }
            if (!$foldersDiff) {

                return redirect()->route('asambleas')->with('success', 'Asambleas Importadas Correctamente');
            }
            foreach ($foldersDiff as $name) {
                $this->importAsambleaFile($name);
            }
            return redirect()->route('asambleas')->with('success', 'Asambleas Importadas Correctamente');
        } catch (\Throwable $th) {
            return redirect()->route('asambleas')->withErrors(['error' => '1' . $th->getMessage()]);
        }
    }

    public function loadFile($sourcePath, $destinationPath)
    {
        // Verifica si el archivo existe
        if (file_exists($sourcePath)) {

            // Mueve el archivo al directorio de almacenamiento
            Storage::disk('results')->put($destinationPath, file_get_contents($sourcePath));

            return $destinationPath;
        } else {

            throw new Exception('File does not exist');
        }
    }
}
