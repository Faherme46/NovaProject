<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Imports\PersonasImport;
<<<<<<< Updated upstream
use App\Imports\PrediosImport;
use App\Imports\PredioWithRegistro;
=======
use App\Imports\PrediosEleccionesImport;
>>>>>>> Stashed changes
use App\Imports\UsersImport;
use App\Models\Asamblea;
use App\Models\Control;
use App\Models\Predio;
use App\Models\Torre;
use Exception;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException;

class EleccionesController extends Controller
{

    public $prediosController;
    public $sessionController;
    public $fileController;
    public function __construct()
    {
        $this->prediosController = new PrediosController();
        $this->sessionController = new SessionController;
        $this->fileController = new FileController;
    }
    public function createTorres(Request $request)
    {

        if (count($request->delegados) != $request->torres) {
            return back()->withErrors('Se ha enviado diferente numero de delegaciones que torrres');
            # code...
        }
        foreach ($request->delegados as $key => $value) {
            if ($value <= 0) {
                return back()->withErrors('No se pueden enviar delegaciones en 0 para: ' . $key);
            }
        }
<<<<<<< Updated upstream
        foreach ($request->delegados as $key => $value) {
            $predios = Predio::where('numeral1', $key);
            $attributes = [
                'units' => $predios->count(),
                'coeficiente' => $predios->sum('coeficiente'),
                'votos' => $predios->sum('votos'),
                'delegados' => $value,
            ];
            $torre=Torre::where('name',$key)->first();
            if($torre){
                $torre->delegados=$value;
                $torre->save();
                $unit='actualizado';
            }else{
                $attributes['name']=$key;
                Torre::create($attributes);
                $unit='creado';
=======
        $delegados = json_decode($request->delegadosArray,true);

        foreach ($delegados as $key => $value) {
            $predios = Predio::where('numeral1', $key);

            $torre = Torre::where('name', $value['name'])->where('first')->first();
            if ($torre) {
                $torre->delegados = $value;
                $torre->save();
                $message = 'actualizado';
            } else {

                Torre::create(['name'=>$value['name'],'first'=>$value['first'],'delegados'=>$value['delegados']]);
                $message = 'creado';
>>>>>>> Stashed changes
            }

<<<<<<< Updated upstream
        }
        return back()->with('success', 'Se han '.$unit. ' las torres con sus delegados');
=======
        \Illuminate\Support\Facades\Log::channel('custom')->info('Se han ' . $message . ' las torres con sus delegados');
        return back()->with('success', 'Se han ' . $message . ' las torres con sus delegados');
>>>>>>> Stashed changes
    }


    public function store(Request $request)
    {

        // Obtener los datos originales del request
        $input = $request->all();

        // Modificar los datos del request
        $input['registro'] = true;
        $input['eleccion'] = true;
        $input['controles'] = 0;
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

        ], [
            'folder.required' => 'Debe seleccionar un cliente.',
        ]);

        try {
            $imports = $this->importPredios($request->folder);

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
            }
        } catch (QueryException $qe) {
            $this->sessionController->destroyOnError();
            if ($qe->errorInfo[1] == 1062) { // 1062 es el código de error para duplicados
                return back()->withErrors('Ya existen elecciones en la misma fecha para este cliente.');
            } else {
                return back()->withErrors($qe->getMessage());
            }
        } catch (Exception $e) {
            $this->sessionController->destroyOnError();
            return back()->withErrors($e->getMessage());
        }

        return redirect()->route('elecciones.programar')->with('success', 'Asamblea creada con éxito, documentos importados.');
    }

    public function importPredios(String $file)
    {
        try {
            $externalFilePathPredios = 'C:/Asambleas/Clientes/' . $file . '/predios.xlsx';
            $externalFilePathPersonas = 'C:/Asambleas/Clientes/' . $file . '/personas.xlsx';


            if (!file_exists($externalFilePathPersonas)) {
                throw new FileNotFoundException("El archivo no se encontró en la ruta: {$externalFilePathPersonas}");
            }
            if (!file_exists($externalFilePathPredios)) {
                throw new FileNotFoundException("El archivo no se encontró en la ruta: {$externalFilePathPredios}");
            }
            Excel::import(new PersonasImport, $externalFilePathPersonas);
            Excel::import(new PrediosEleccionesImport, $externalFilePathPredios);
            if (file_exists('C:/Asambleas/usuarios.xlsx')) {
                Excel::import(new UsersImport, 'C:/Asambleas/usuarios.xlsx');
            }


            return 200;
        } catch (ValidationException $e) {
            // Manejar excepciones específicas de validación de Excel
            $failures = $e->failures();
            return redirect()->route('elecciones.programar')->withErrors($failures[0]);
            //Excepcion por archivo inexistente
        } catch (FileNotFoundException $e) {
            return redirect()->route('elecciones.programar')->withErrors($e->getMessage());
        } catch (Exception $e) {

            // Manejar cualquier otra excepción
            return redirect()->route('elecciones.programar')->withErrors($e->getMessage());
        }
    }
<<<<<<< Updated upstream
=======


    public function setCandidatos()
    {
        try {
            $externalFilePathPersonas = 'C:/Asambleas/Clientes/' . cache('asamblea')['folder'] . '/personas.xlsx';
            if (file_exists($externalFilePathPersonas)) {
                Excel::import(new EleccionesImport, $externalFilePathPersonas);
                \Illuminate\Support\Facades\Log::channel('custom')->info('Se importaron candidatos de excel');
                return redirect()->route('elecciones.candidatos')->with('success', 'Candidatos importados Correctamente');
            } else {
                return redirect()->route('elecciones.candidatos')->with('warning', 'No se importaron candidatos, el archivo personas.xlsx no fue encontrado');
            }
        } catch (\Throwable $th) {
            return redirect()->route('elecciones.candidatos')->with('warning', 'No se importaron candidatos ' . $th->getMessage());
        }
    }
>>>>>>> Stashed changes
}
