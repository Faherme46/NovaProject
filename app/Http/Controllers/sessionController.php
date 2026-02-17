<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Session;
use App\Models\Predio;
use App\Models\Persona;
use App\Models\Control;
use App\Models\Plancha;
use App\Models\PrediosPersona;
use App\Models\Question;
use App\Models\Result;
use App\Models\Signature;
use App\Models\Terminal;
use App\Models\Torre;
use App\Models\TorresCandidato;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;

class SessionController extends Controller
{
    public function destroyAll()
    {
        $nameAsamblea = cache('asamblea')['name'];
        $logpath = storage_path('logs/myLog.log');

        if (File::exists($logpath)) {
            Storage::disk('externalAsambleas')->put($nameAsamblea . '/logs.log', file_get_contents($logpath));
            File::delete($logpath);
        }

        try {
            $fileController = new FileController;
            if (!$fileController->exportTables()) {
                return back()->withErrors('Error', 'Problema exportando las tablas de excel');
            };
        } catch (\Throwable $th) {
            return back()->withErrors('Error', 'Problema exportando las tablas de excel ' . $th->getMessage());
        }
        //se descargan las tablas
        $backupController = new BackupController();
        $response = $backupController->downloadBackup();

        if ($response != 200 && $response != []) {
            dd($response);
            return redirect()->route('home')->with('error', $response);
        };

        $sessionData = Auth::user();

        $this->deleteAsambleaFiles();
        session()->flush();

        //se limpiaran las tablas
        $this->destroyOnError();

        Auth::attempt(["username" => $sessionData->username, "password" => $sessionData["passwordTxt"]]);
        return redirect()->route('home')->with('success', 'Sesion reestablecida');
    }

    public function destroyOnError()
    {
        DB::statement('LOCK TABLES `cache` WRITE;');
        Cache::flush();
        DB::statement('UNLOCK TABLES;');
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Session::truncate();
        Predio::truncate();
        Control::truncate();
        Persona::truncate();
        Result::truncate();
        PrediosPersona::truncate();
        Question::truncate();
        Signature::truncate();
        Plancha::truncate();
        Torre::truncate();
        TorresCandidato::truncate();
        Terminal::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
    public function deleteAsambleaFiles()
    {
        $disk = Storage::disk('results');
        $asambleaName = cache('asamblea')['name'];
        // Verifica si el disco existe
        if ($disk->exists($asambleaName)) {
            Storage::disk('results')->deleteDirectory($asambleaName);
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

    public function setSession($id_asamblea, $name_asamblea)
    {
        $data = [
            'id' => 1,
            'id_asamblea' =>  $id_asamblea,
            'name_asamblea' => $name_asamblea
        ];
        $session = new Session();
        return $session->setManualData($data);
    }

    public function getSessionId()
    {
        $session = Session::all()->first();
        if ($session) {
            return $session->id_asamblea;
        } else {
            return 0;
        }
    }

    public function getSessionName()
    {
        $session = Session::all()->first();
        if ($session) {
            return $session->name_asamblea;
        } else {
            return '';
        }
    }


    public function updateSession($session, $id)
    {
        $sessionOn = Session::findOrFail($id);
        $sessionOn->update($session);
    }

    public function sessionConnect(Request $request)
    {

        $ip = $request->ip;

        $request->validate([
            'ip' => 'required|ip',
        ], [
            'ip.required' => 'El campo de la IP es requerido',
        ]);
        $connection = @fsockopen($ip, 3306, $errno, $errstr, 2);

        if ($connection) {
            fclose($connection);
            Storage::put('db_host.txt', $ip);
            return back()->with('success', 'Se ha establecido conexión');
        } else {

            return back()->with('warning', 'No se ha encontrado un servicio mysql para la ip destinada');
        }
    }

    public function sessionDisconnect()
    {

        if (Storage::exists('db_host.txt')) {
            Storage::delete('db_host.txt');
            return back()->with('success', 'Se ha realizado la desconexión');
        } else {
            return back()->with('warning', 'No hay conexiones activas');
        };
    }


    public function registerHidDevices(Request $request)
    {
        $devices = $request['devices'];
        
        //DEVICES ES UN OBJETO JSON QUE CONTIENE LA INFORMACIÓN DE LOS DISPOSITIVOS HID DETECTADOS POR EL SCRIPT PYTHON. ESTE OBJETO DEBERÍA SER ENVIADO DESDE EL SCRIPT PYTHON A TRAVÉS DE UNA SOLICITUD POST AL ENDPOINT DEFINIDO EN LAS RUTAS.
        //GUARDA EL OBJETO JSON EN EL CACHE
        cache(['hid_devices' => "devices: " . json_encode($devices)], now()->addMinutes(720)); // Guarda los dispositivos en el cache por 720 minutos (12 horas)
        return response()->json(['message' => 'Dispositivos registrados correctamente']);
    }
}
