<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Asamblea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BackupController extends Controller
{
    public function downloadBackup()
    {
        $nameAsamblea=Asamblea::where('id_asamblea',cache('id_asamblea'))->pluck('name')->first();

        $ubicacionArchivoTemporal = storage_path("app\public\backups\\".$nameAsamblea.'.sql');
        $codigoSalida = 0;
        $comando = sprintf("%s --user=\"%s\" --password=\"%s\" %s > %s", env("UBICACION_MYSQLDUMP"), env("DB_USERNAME"), env("DB_PASSWORD"), env("DB_DATABASE"), $ubicacionArchivoTemporal);

        exec($comando, $salida, $codigoSalida);

        if ($codigoSalida !== 0) {

            return back()->withErrors('error','Error exportando la base de datos');
        }

        Storage::disk('externalAsambleas')->put($nameAsamblea.'/'.$nameAsamblea.'.sql', file_get_contents($ubicacionArchivoTemporal));

        return back();
    }

    // Cargar respaldo en la base de datos
    public function restoreBackup(Request $request)
    {
        $request->validate([
            'backup_file' => 'required|file|mimes:sql',
        ]);

        $username = env('DB_USERNAME');
        $password = env('DB_PASSWORD');
        $database = env('DB_DATABASE');

        // Almacenar temporalmente el archivo SQL cargado
        $path = $request->file('backup_file')->storeAs('backups', 'restore.sql');

        // Restaurar la base de datos usando el archivo
        exec("mysql -u $username -p$password $database < " . storage_path("app/$path"));

        return response()->json(['message' => 'Database restored successfully']);
    }
}
