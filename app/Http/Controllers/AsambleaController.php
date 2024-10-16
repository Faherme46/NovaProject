<?php

namespace App\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;


use App\Http\Controllers\FileController;

use App\Models\Predio;
use App\Models\Persona;
use App\Models\Asamblea;
use App\Models\Control;

use App\Imports\PersonasImport;
use App\Imports\PrediosImport;

use App\Imports\PredioWithRegistro;
use App\Imports\UsersImport;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
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
            $imports=$this->importPredios($request->folder,$request->registro);

            if($imports==200){
                $asamblea = Asamblea::create($request->all());
                $this->sessionController->setSession($asamblea->id_asamblea, $asamblea->folder);
                $data = [
                    'id_asamblea'   => $asamblea->id_asamblea,
                    'inRegistro'    => $asamblea->registro,
                    'asamblea'=>$asamblea
                ];

                Cache::putMany($data);
                Control::factory()->count($asamblea->controles)->create();
                $this->fileController->getAsambleaFolderPath();
            }
        } catch (QueryException $qe) {
            $this->sessionController->destroyOnError();
            if ($qe->errorInfo[1] == 1062) { // 1062 es el código de error para duplicados
                return redirect()->route('home')->withErrors('Ya existe una asamblea en la misma fecha.');
            } else {
                return redirect()->route('home')->withErrors($qe->getMessage());
            }
        } catch (\Exception $e) {
            $this->sessionController->destroyOnError();
            return redirect()->route('home')->withErrors($e->getMessage());
        }

        return redirect()->route('home')->with('success', 'Asamblea creada con éxito, documentos importados.');
    }



    public function updateAsamblea(Request $request)
    {
        $messages=[
        '*.required' =>'El campo :attribute no puede estar vacio',
        'fecha.date' => 'El campo :attribute debe ser una fecha válida.',
        '*.date_format' => 'El campo :attribute no corresponde con el formato :format.',
        ];
        $request->validate([
            'lugar' => 'required',
            'fecha' => 'required|date',
            'hora' => 'required',
            'controles' => 'required',
        ],$messages);

        $asamblea = Asamblea::find($request->id_asamblea);
        if($asamblea){
            $asamblea->update($request->all());
            if (!$request->signature) {
                $asamblea->signature=false;
                $asamblea->save();
            }
            cache(['asamblea'=>$asamblea]);
            return back()->with('success', 'Asamblea actualizada con éxito.');
        }else{
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
    public function importPredios(String $file,$registro){
        try {
            $externalFilePathPredios = 'C:/Asambleas/Clientes/'.$file.'/predios.xlsx';
            $externalFilePathPersonas= 'C:/Asambleas/Clientes/'.$file.'/personas.xlsx';

            if (!file_exists($externalFilePathPersonas)) {
                throw new FileNotFoundException("El archivo no se encontró en la ruta: {$externalFilePathPersonas}");
            }
            if (!file_exists($externalFilePathPredios)) {
                throw new FileNotFoundException("El archivo no se encontró en la ruta: {$externalFilePathPredios}");
            }
            if ($registro){
                Excel::import(new PersonasImport,$externalFilePathPersonas);
                Excel::import(new PredioWithRegistro,$externalFilePathPredios);
            }else{
                Excel::import(new PrediosImport,$externalFilePathPredios);
            }

            Excel::import(new UsersImport, 'C:/Asambleas/usuarios.xlsx');

            return 200;
        } catch (ValidationException $e) {
            // Manejar excepciones específicas de validación de Excel
            $failures = $e->failures();
            throw new \Exception('Error: '.$failures[1]);
            //Excepcion por archivo inexistente
        } catch (\Illuminate\Contracts\Filesystem\FileNotFoundException $e) {
            throw new \Exception('Error: No se encontró una de las hojas de cálculo '.$e->getMessage());
        } catch (\Exception $e) {

            // Manejar cualquier otra excepción
             throw new \Exception($e->getMessage());
        }
    }
}
