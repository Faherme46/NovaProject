<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Session;
use App\Models\Predio;
use App\Models\Persona;
use App\Models\Control;
use App\Models\Eleccion;
use App\Models\PrediosPersona;
use App\Models\Question;
use App\Models\QuestionType;
use App\Models\Result;
use App\Models\Signature;
use App\Models\Terminal;
use App\Models\Torre;
use App\Models\TorresCandidato;
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
        $logpath=storage_path('logs/myLog.log');

        if(File::exists($logpath)){
            Storage::disk('externalAsambleas')->put($nameAsamblea . '/logs.log', file_get_contents($logpath));
            File::delete($logpath);
        }

        try {
            $fileController = new FileController;
            if (!$fileController->exportTables()) {
                return back()->withErrors('Error', 'Problema exportando las tablas de excel');
            };
        } catch (\Throwable $th) {
            return back()->withErrors('Error', 'Problema exportando las tablas de excel '.$th->getMessage());
        }
        //se descargan las tablas
        $backupController=new BackupController();
        $response=$backupController->downloadBackup();

        if($response!=200 && $response!=[]){
            dd($response);
            return redirect()->route('home')->with('error', $response);
        };

        $sessionData = Auth::user();

        $this->deleteAsambleaFiles();
        session()->flush();

        //se limpiaran las tablas
        $this->destroyOnError();

        Auth::attempt([ "username"=> $sessionData->username,"password"=> $sessionData["passwordTxt"]]);
        return redirect()->route('home')->with('success', 'Sesion reestablecida');
    }

    public function destroyOnError()
    {
        Cache::flush();
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Session::truncate();
        Predio::truncate();
        Control::truncate();
        Persona::truncate();
        Result::truncate();
        PrediosPersona::truncate();
        Question::truncate();
        Signature::truncate();
        Torre::truncate();
        TorresCandidato::truncate();
        Terminal::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
    public function deleteAsambleaFiles()
    {
        $disk = Storage::disk('results');
        $asambleaName=cache('asamblea')['name'];
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

    public function downloadTables(){

    }
}
