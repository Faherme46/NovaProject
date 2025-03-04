<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Asamblea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class BackupController extends Controller
{
    public function downloadBackup()
    {
        $nameAsamblea = cache('asamblea')['name'];
        $asamblea = cache('asamblea');


        $info = Storage::disk('externalAsambleas')->put($nameAsamblea . '/info.json', json_encode($asamblea));

        $ubicacionArchivoTemporal = Storage::disk('externalAsambleas')->path($nameAsamblea . '/' . $nameAsamblea . '.sql');

        $codigoSalida = 0;

        Cache::forget('asamblea');
        $tables = ['cache', 'controls', 'personas', 'predios', 'predios_personas', 'questions', 'results', 'session', 'signatures',  'torres', 'torres_candidatos','terminals'];
        // $tables = ['cache', 'controls', 'personas', 'predios', 'predios_personas', 'questions', 'results', 'session', 'signatures'];
        $comando = sprintf("%s --user=\"%s\" --password=\"%s\" --skip-lock-tables --no-create-info %s %s > %s", env("UBICACION_MYSQLDUMP"), env("DB_USERNAME"), env("DB_PASSWORD"), env('DB_DATABASE'), implode(' ', $tables), $ubicacionArchivoTemporal);
        try {
            // Storage::disk('public')->makeDirectory('backups');

            exec($comando, $salida, $codigoSalida);

            cache(['asamblea' => $asamblea]);
            if ($codigoSalida !== 0) {
                return $salida;
            }
            \Illuminate\Support\Facades\Log::channel('custom')->info('Se descargo la informacion de la BD.');
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
        return 200;
    }

    // Cargar respaldo en la base de datos
    public function restoreBackup(Request $request)
    {
        $nameAsamblea = $request->name;


        // Almacenar temporalmente el archivo SQL cargado
        $folder = $nameAsamblea . '/' . $nameAsamblea . '.sql';
        $path = Storage::disk('externalAsambleas')->path($folder);
        if (!file_exists($path)) {
            return back()->with('error', 'No se encontro el archivo "' . $path . '"');
        }

        $sql = Storage::disk('externalAsambleas')->get($folder);
        try {
            $execute = DB::unprepared($sql);
        } catch (\Throwable $th) {
            $sessionController = new SessionController;
            $sessionController->destroyOnError();
            return redirect()->route('home')->with('error', 'Error cargando la Asamblea: ' . $th->getMessage());
        }


        try {
            $asamblea = Asamblea::where('name', $nameAsamblea)->first();
            cache(['asamblea' => $asamblea]);
            cache(['id_asamblea' => $asamblea->id_asamblea]);
            $questions = Storage::disk('externalAsambleas')->directories($nameAsamblea . '/Preguntas');
            foreach ($questions as $key => $question) {
                $partes = explode("Preguntas/", $question);
                if (isset($partes[1])) {
                    $pathCoef = Storage::disk('externalAsambleas')->get($question . '/coefChart.png');
                    Storage::disk('results')->put($nameAsamblea . '/' . $partes[1] . '/coefChart.png', $pathCoef);
                    $pathNom = Storage::disk('externalAsambleas')->get($question . '/nominalChart.png');
                    Storage::disk('results')->put($nameAsamblea . '/' . $partes[1]  . '/nominalChart.png', $pathNom);
                }

                // Ejecutar el comando SQL

            }
        } catch (\Throwable $th) {

            return redirect()->route('home')->with('error', 'Error Importando las imagenes de las preguntas ' . $th->getMessage());
        }







        #Importacion del log
        $logpath = Storage::disk('externalAsambleas')->get($nameAsamblea . '/logs.log');;


        if ($logpath) {
            Storage::disk('logs')->put('myLog.log', $logpath);
        }



        // Restaurar la base de datos usando el archivo
        // exec("mysql -u $username -p $password $database < " . storage_path("app/$path"));
        \Illuminate\Support\Facades\Log::channel('custom')->info('Carga la informacion de la asamblea:' . $nameAsamblea);
        return redirect()->route('home')->with('success', 'Asamblea Cargada Correctamente');
    }
}
