<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Session;
use App\Models\Predio;
use App\Models\Persona;
use App\Models\Control;
use App\Models\ControlPredio;
use App\Models\Question;
use App\Models\Result;

use Database\Seeders\QuestionSeeder;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

use Illuminate\Support\Facades\Storage;

class SessionController extends Controller
{
    public function destroyAll()
    {
        //se descargan las tablas
        $this->downloadTables();

        //se limpiaran las tablas: personas,Predios, apoderados, votaciones,resultados,preguntas, votos
        $this->destroyOnError();
        return redirect()->route('admin.asambleas')->with('success', 'Sesion reestablecida');
    }

    public function destroyOnError()
    {
        Session::truncate();
        Cache::forget('id_asamblea');
        Cache::forget('asambleaOn');
        Cache::forget('inRegistro');
        Cache::forget('controles');
        Cache::forget('name-asamblea');
        session()->flush();
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Predio::truncate();
        Control::truncate();
        Persona::truncate();
        Result::truncate();
        Question::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $seeder= new QuestionSeeder();
        $seeder->run();
        $this->deleteAllFiles();
    }
    public function deleteAllFiles()
    {
        $disk = Storage::disk('results');
        // Verifica si el disco existe
        if ($disk->exists('')) {
            $this->deleteDirectory($disk, '');
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
