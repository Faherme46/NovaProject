<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Asamblea;
use App\Models\Session;
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

                return back()->with('error', 'Error exportando la base de datos');
            }

            Storage::disk('externalAsambleas')->put($nameAsamblea . '/' . $nameAsamblea . '.sql', file_get_contents($ubicacionArchivoTemporal));
            \Illuminate\Support\Facades\Log::channel('custom')->info('Se descargo la informacion de la BD.');
        } catch (\Throwable $th) {
            return $th;
        }
        return 200;
    }

    // Cargar respaldo en la base de datos
    public function restoreBackup(Request $request)
    {
        $nameAsamblea = $request->name;
        $dataLog=Storage::disk('externalAsambleas')->url($nameAsamblea.'/logs.log');

        $username = env('DB_USERNAME');
        $password = env('DB_PASSWORD');
        $database = env('DB_DATABASE');

        // Almacenar temporalmente el archivo SQL cargado
        $path = storage_path("app\public\backups\\" . $nameAsamblea . '.sql');
        if (!file_exists($path)) {

            return back()->with('error', 'No se encontro el archivo de la respectiva asamblea');
        }
        $sql = file_get_contents($path);

        $comando = sprintf("%s --user=\"%s\" --password=\"%s\" --skip-lock-tables %s < %s", env("UBICACION_MYSQLDUMP"), env("DB_USERNAME"), env("DB_PASSWORD"), env("DB_DATABASE"), $sql);

        // Ejecutar el comando SQL
        try {
            exec($comando, $salida, $codigoSalida);

        } catch (\Throwable $th) {

            return redirect()->route('home')->with('success', 'Asamblea Cargada Correctamente');
        }

        cache(['asamblea'=>Asamblea::find(Session::first()->id_asamblea)]);

        // Restaurar la base de datos usando el archivo
        // exec("mysql -u $username -p $password $database < " . storage_path("app/$path"));
        \Illuminate\Support\Facades\Log::channel('custom')->info('Carga la informacion de la asamblea:'.$nameAsamblea);
        return redirect()->route('home')->with('success', 'Asamblea Cargada Correctamente');
    }
}
