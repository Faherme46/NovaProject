<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Session;
use App\Models\Predio;
use App\Models\Persona;
use App\Models\Control;
use App\Models\ControlPredio;

use Illuminate\Support\Facades\Session as MySession;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class SessionController extends Controller
{
    public function destroyAll(){

        //se limpiaran las tablas: personas,Predios, apoderados, votaciones,resultados,preguntas, votos
        $this->destroyOnError();
        session()->flush();
        return redirect()->route('admin.asambleas')->with('success','Sesion reestablecida');
    }

    public function destroyOnError(){
        Session::truncate();
        Cache::flush();
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        ControlPredio::truncate();
        Predio::truncate();
        Control::truncate();
        Persona::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
    public function setSession($id_asamblea,$name_asamblea){
        $data=[
            'id'=>1,
            'id_asamblea' =>  $id_asamblea,
            'name_asamblea'=>$name_asamblea
        ];
        $session=new Session();
        return $session->setManualData($data);
    }

    public function getSessionId(){
        $session=Session::all()->first();
        if ($session){
            return $session->id_asamblea;
        }else{
            return 0;
        }

    }

    public function getSessionName(){
        $session=Session::all()->first();
        if ($session){
            return $session->name_asamblea;
        }else{
            return '';
        }
    }


    public function updateSession($session,$id){
        $sessionOn=Session::findOrFail($id);
        $sessionOn->update($session);
    }





}
