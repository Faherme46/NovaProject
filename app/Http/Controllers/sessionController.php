<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Session;

class sessionController extends Controller
{
    public function destroyAll(){
        Session::destroy(1);
    }
    public function setSession($id_reunion){
        $this->destroyAll();
        $data=[
            'id'=>1,
            'id_reunion' =>  $id_reunion
        ];
        $session=new Session();
        return $session->setManualData($data);
    }

    public function getSessionId(){
        $session=Session::all()->first();
        if ($session){
            return $session->id_reunion;
        }else{
            return 0;
        }

    }

    public function updateSession($session,$id){
        $sessionOn=Session::findOrFail($id);
        $sessionOn->update($session);
    }




}
