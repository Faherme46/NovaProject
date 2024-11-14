<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BackupController extends Controller
{
    public function downloadBackup()
    {
        $username = env('DB_USERNAME');
        $password = env('DB_PASSWORD');
        $database = env('DB_DATABASE');
        $name=cache('asamblea')['name'];
        $backupFile = storage_path('app/'.$name.'.sql');

        // Ejecutar mysqldump para crear el respaldo
        exec("mysqldump -u $username -p $password $database > $backupFile");

        // Descargar el archivo generado
        $response=Storage::disk('backups')->put($name,$backupFile);

        return response()->download($backupFile)->deleteFileAfterSend(true);
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
