<?php

namespace App\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;

use Carbon\Carbon;
use DateTimeZone;

use App\Http\Controllers\FileController;

use App\Models\Predio;
use App\Models\Persona;
use App\Models\Asamblea;
use App\Models\Control;

use App\Imports\PersonasImport;
use App\Imports\PrediosImport;

use App\Imports\PredioWithRegistro;
use App\Imports\UsersImport;

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
    public function index()
    {

        $sessionId = $this->sessionController->getSessionId();


        if ($sessionId) {
            $predios = Predio::get();

            if(cache('registro')){
                $personas = Persona::get();
                return view('lider.session', compact('personas', 'predios'));
            }

            return view('lider.session', compact('predios'));
        } else {

            $folders = $this->fileController->getFolders();
            return view('admin.asamblea', compact('folders'));
            # code...
        }
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
            $asamblea = Asamblea::create($request->all());
            $this->sessionController->setSession($asamblea->id_asamblea, $asamblea->folder);
            $this->importPredios($asamblea->folder,$asamblea->registro);

            $data = [
                'id_asamblea'   => $asamblea->id_asamblea,
                'asambleaOn'    => true,
                'inRegistro'    => $asamblea->registro,
                'controles'     => $asamblea->controles,
                'name_asamblea' => $asamblea->name
            ];

            Cache::putMany($data);
            Control::factory()->count($asamblea->controles)->create();
            $this->fileController->createFolder($asamblea->name);
        } catch (QueryException $qe) {
            if ($qe->errorInfo[1] == 1062) { // 1062 es el código de error para duplicados
                return redirect()->route('asambleas.index')->withErrors('Ya existe una asamblea en la misma fecha.');
            } else {
                return redirect()->route('asambleas.index')->withErrors($qe->getMessage());
            }
        } catch (\Exception $e) {
            $this->sessionController->destroyOnError();
            $this->destroy($asamblea->id_asamblea);
            return redirect()->route('asambleas.index')->withErrors($e->getMessage());
        }

        return redirect()->route('asambleas.index')->with('success', 'Asamblea creada con éxito.');
    }



    public function update(Request $request, $id)
    {

        $request->validate([
            'nombre' => 'required',
            'lugar' => 'required',
            'fecha' => 'required|date',
            'hora' => 'required',
            'estado' => 'required|in:pendiente,en_progreso,finalizada,cancelada',
        ]);

        $asamblea = Asamblea::findOrFail($id);
        $asamblea->update($request->all());
        return redirect()->route('admin.asambleas')->with('success', 'Reunión actualizada con éxito.');
    }

    public function destroy($id)
    {
        $asamblea = Asamblea::findOrFail($id);
        $asamblea->delete();
        return redirect()->route('admin.asambleas');
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


    public function iniciarAsamblea(Request $request)
    {
        try {
            $asamblea = Asamblea::findOrFail($request->id_asamblea);
            $time = Carbon::now(new DateTimeZone('America/Bogota'));
            if ($asamblea->h_inicio == null) {
                $asamblea->h_inicio = $time;
                $asamblea->save();
                return back()->with('info', 'Se ha iniciado la asamblea en: ' . $time);
            } else {
                return back()->with('warning', 'Ya se establecio el inicio en: ' . $asamblea->h_inicio);
            }
        } catch (\Exception $e) {
            //throw $th;
            return back()->withErrors($e->getMessage());
        }
    }

    public function terminarAsamblea(Request $request)
    {
        try {
            $asamblea = Asamblea::findOrFail($request->id_asamblea);
            $time = Carbon::now(new DateTimeZone('America/Bogota'));
            if ($asamblea->h_cierre == null) {
                $asamblea->h_cierre = $time;
                $asamblea->save();
                return back()->with('info', 'Se ha terminado la asamblea en: ' . $time);
            } else {
                return back()->with('warning', 'Ya se establecio el cierre en: ' . $asamblea->h_cierre);
            }
        } catch (\Exception $e) {
            //throw $th;
            return back()->withErrors($e->getMessage());
        }
    }
    public function asistencia()
    {
        $allControls = Control::where('state','!=',4)->get();
        return view('asistencia', compact('allControls'));
    }


    //Metodos de manejo de archivos
    public function importPredios(String $file,$registro){
        try {
            $externalFilePathPredios = 'C:/Asambleas/Clientes/'.$file.'/predios.xlsx';
            $externalFilePathPersonas= 'C:/Asambleas/Clientes/'.$file.'/personas.xlsx';
            if ($registro){
                Excel::import(new PersonasImport,$externalFilePathPersonas);
                Excel::import(new PredioWithRegistro,$externalFilePathPredios);
            }else{
                Excel::import(new PrediosImport,$externalFilePathPredios);
            }

            Excel::import(new UsersImport, 'C:/Asambleas/usuarios.xlsx');

            return redirect()->route('asambleas.index')->with('success','Carga de datos exitosa');
        } catch (ValidationException $e) {
            // Manejar excepciones específicas de validación de Excel
            $failures = $e->failures();
            throw new \Exception('Error: '.$failures[1]);
            //Excepcion por archivo inexistente
        } catch (\Illuminate\Contracts\Filesystem\FileNotFoundException $e) {
            throw new \Exception('Error: No se encontró una de las hojas de cálculo');
        } catch (\Exception $e) {

            // Manejar cualquier otra excepción
             throw new \Exception($e->getMessage());
        }
    }
}
