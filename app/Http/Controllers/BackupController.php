<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Asamblea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BackupController extends Controller
{
    public function downloadBackup()
    {
        $nameAsamblea = cache('asamblea')['name'];

        $ubicacionArchivoTemporal = storage_path("app\public\backups\\" . $nameAsamblea . '.sql');
        $codigoSalida = 0;
       
        $tables = ['cache', 'controls', 'personas', 'predios', 'predios_personas', 'questions', 'results', 'session', 'signatures', 'votes'];
        $comando = sprintf("%s --user=\"%s\" --password=\"%s\" --skip-lock-tables --no-create-info %s %s > %s", env("UBICACION_MYSQLDUMP"), env("DB_USERNAME"), env("DB_PASSWORD"), env('DB_DATABASE'), implode(' ', $tables), $ubicacionArchivoTemporal);
        try {

            exec($comando, $salida, $codigoSalida);

            if ($codigoSalida !== 0) {

                return $codigoSalida;
            }

            Storage::disk('externalAsambleas')->put($nameAsamblea . '/' . $nameAsamblea . '.sql', file_get_contents($ubicacionArchivoTemporal));
            Storage::disk('public')->delete('backups/'.$nameAsamblea.'.sql');
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
        $path = $nameAsamblea . '/' . $nameAsamblea . '.sql';
        $sql = Storage::disk('externalAsambleas')->get($path);
        if (!$path) {
            return back()->with('error', 'No se encontro el archivo "C:/Asambleas/Asambleas/' . $path . '"');
        }
        try {
            $questions = Storage::disk('externalAsambleas')->directories($nameAsamblea . '/Preguntas');
            foreach ($questions as $question) {
                $pathCoef = Storage::disk('externalAsambleas')->get($question . '/coefChart.png');
                Storage::disk('results')->put($question . '/coefChart.png', $pathCoef);
                $pathNom = Storage::disk('externalAsambleas')->get($question . '/nominalChart.png');
                Storage::disk('results')->put($question . '/nominalChart.png', $pathNom);
                // Ejecutar el comando SQL

            }
            $execute = DB::unprepared($sql);
            \Illuminate\Support\Facades\Log::channel('custom')->info('Carga la informacion de la asamblea:' . $nameAsamblea);
        } catch (\Throwable $th) {

            return redirect()->route('home')->with('success', 'Asamblea Cargada Correctamente');
        }


        // Restaurar la base de datos usando el archivo
        // exec("mysql -u $username -p $password $database < " . storage_path("app/$path"));
        \Illuminate\Support\Facades\Log::channel('custom')->info('Carga la informacion de la asamblea:' . $nameAsamblea);
        return redirect()->route('home')->with('success', 'Asamblea Cargada Correctamente');
    }
}
