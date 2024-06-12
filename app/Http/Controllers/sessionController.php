<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Session;
use App\Models\Predio;
use App\Models\Persona;

class SessionController extends Controller
{
    public function destroyAll(){

        //se limpiaran las tablas: personas,Predios, apoderados, votaciones,resultados,preguntas, votos
        Session::truncate();
        Predio::truncate();
        Persona::query()->delete();

        return redirect()->route('admin.asambleas')->with('success','Sesion reestablecida');
    }

    public function destroyOnError(){
        Session::truncate();
        Predio::truncate();
        Persona::query()->delete();
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
