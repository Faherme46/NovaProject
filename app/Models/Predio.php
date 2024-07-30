<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Predio extends Model
{
    use HasFactory;

    protected $guarded=[];


    public function persona()
    {
        return $this->belongsTo(Persona::class, 'cc_propietario', 'id');
    }


    public function control()
    {
        return $this->belongsTo(Control::class);
    }

    public function apoderado(){
        return $this->belongsTo(Persona::class,'cc_apoderado','id');
    }


    public function getRelationPersona($id){
        if ($this->cc_propietario==$id) {
            return 'P';
        }
        if ($this->cc_apoderado==$id) {
            return 'AR';
        }

        return 'A';
    }

    public function getFullName(){

        return $this->descriptor1.' '.$this->numeral1.' '.$this->descriptor2.' '.$this->numeral2;
    }



}
