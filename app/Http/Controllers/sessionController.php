<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Session;
use App\Models\Propiedad;
use App\Models\Persona;

class SessionController extends Controller
{
    public function destroyAll(){

        //se limpiaran las tablas: personas,propiedades, apoderados, votaciones,resultados,preguntas, votos
        Session::truncate();
        Persona::truncate();
        Propiedad::truncate();
        return redirect()->route('admin.crearAsamblea')->with('success','Sesion reestablecida');
    }
    public function setSession($id_asamblea){
        $this->destroyAll();
        $data=[
            'id'=>1,
            'id_asamblea' =>  $id_asamblea
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

    public function updateSession($session,$id){
        $sessionOn=Session::findOrFail($id);
        $sessionOn->update($session);
    }




}
