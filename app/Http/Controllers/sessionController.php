<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Session;

class sessionController extends Controller
{
    public function destroyAll(){
        Session::destroy(1);
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
