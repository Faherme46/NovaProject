<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Imports\EleccionesImport;
use App\Imports\PersonasImport;
use App\Imports\PrediosEleccionesImport;
use App\Imports\UsersImport;
use App\Models\Asamblea;
use App\Models\Control;
use App\Models\Predio;
use App\Models\Torre;
use App\Models\TorresCandidato;
use Exception;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException;
use Throwable;

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
        $delegados = json_decode($request->delegadosArray, true);
        foreach ($delegados as $key => $value) {

            $predios = Predio::where('numeral1', $key);

            $torre = Torre::where('name', $key)->first();
            if ($torre) {
                $torre->delegados = $value['delegados'];
                $torre->save();
                $message = 'actualizado';
            } else {

                Torre::create(['name' => $key, 'delegados' => $value['delegados']]);
                $message = 'creado';
            }

            \Illuminate\Support\Facades\Log::channel('custom')->info('Se han ' . $message . ' las torres con sus delegados');
        }
        return back()->with('success', 'Se han ' . $message . ' las torres con sus delegados');
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


    public function importCandidatos()
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
        } catch (Throwable $th) {
            return redirect()->route('elecciones.candidatos')->with('error', 'Error importando candidatos: ' . $th->getMessage());
        }
    }


    public function generarGraficas()
    {
        cache(['graficas' => true]);
        $torres = Torre::all();
        foreach ($torres as $torre) {
            $nombres = [];
            $valuesCoef = [];
            $valuesNom = [];
            try {
                $candidatos = $torre->candidatosCoef->toArray();
                foreach ($candidatos as $persona) {
                    $nombres[] = $persona['nombre'] . ' ' . $persona['apellido'];
                    $valuesCoef[] =( $persona['pivot']['coeficiente'])? $persona['pivot']['coeficiente']:0;
                    $valuesCoef[] = ($persona['pivot']['coeficiente']) ? $persona['pivot']['coeficiente'] : 0;
                }
                $this->createChart($torre->id, $torre->name, $nombres, $valuesCoef, 'coefChart', $torre->delegados, $torre->coeficienteBlanco);
                $nombres = [];
                $candidatos = $torre->candidatosNom->toArray();
                foreach ($candidatos as $persona) {
                    $nombres[] = $persona['nombre'] . ' ' . $persona['apellido'];
                    $valuesNom[] = ($persona['pivot']['votos']) ? $persona['pivot']['votos'] : 0;
                }

                $this->createChart($torre->id, $torre->name, $nombres, $valuesNom, 'nominalChart', $torre->delegados, $torre->votosBlanco);
            } catch (Throwable $th) {
                return redirect()->route('elecciones.resultados')->with('error', $th->getMessage());
            }
        }
        return redirect()->route('elecciones.resultados')->with('success', 'Gráficas generadas con exito');
    }


    public function createChart($torreId, $torreName, $labels, $values, $name, $delegados, $blanco)
    {
        // Datos para el gráfico

        $asambleaName = cache('asamblea')['name'];
        $path = $asambleaName . '/Preguntas/' . ($torreId);
        // Ruta donde se guardará la imagen
        $output_path = Storage::disk('externalAsambleas')->path($path);

        if (!file_exists($output_path)) {
            mkdir($output_path, 0755, true);
        }

        //todo numero de preguntas en defecto
        $localPath = $asambleaName . '/' .  ($torreId) . '/' . $name . '.png';
        // Crear un array con los datos
        $data = [
            'title' => $torreName,
            'output' => $output_path . '/' . $name . '.png',
            'labels' => $labels,
            'values' => $values,
            'nameAsamblea' => $asambleaName,
            'delegados' => $delegados,
            'blanco' => $blanco
        ];
        try {
            $response = Http::post('http://localhost:5000/create-plot-elecciones', $data);
            return $this->loadImage($output_path . '/' . $name . '.png', $localPath);
        } catch (Throwable $th) {

            throw new Exception('Error al conectar con el servidor python');
        }
    }

    public function loadImage($sourcePath, $destinationPath)
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

    public function updateElecciones(Request $request)
    {
        $messages = [

            'fecha.date' => 'El campo :attribute debe ser una fecha válida.',
            '*.date_format' => 'El campo :attribute no corresponde con el formato :format.',
        ];
        $request->validate([
            'lugar' => 'required',
            'fecha' => 'required|date',
            'hora' => 'required',
        ], $messages);

        $asamblea = Asamblea::find($request->id_asamblea);

        if ($asamblea) {
            $asamblea->update($request->all());
            if (!$request->signature) {
                $asamblea->signature = false;
                $asamblea->save();
            }
            cache(['asamblea' => $asamblea]);
            return back()->with('success', 'Asamblea actualizada con éxito.');
        } else {
            return back()->withErrors('No se encontro la asamblea')->withInput();
        }
    }

    public function verifySum()
    {
        $torres = Torre::all();
        foreach ($torres as $torre) {
            $candidatos = $torre->candidatosCoef->toArray();
            foreach ($candidatos as $persona) {
                
                $valuesCoef[$persona['id']] = ($persona['pivot']['coeficiente']) ? $persona['pivot']['coeficiente'] : 0;
            }

        }
        $controls = Control::groupBy('h_recibe')->sum('sum_coef_can');
        dd($controls);
    }
}
