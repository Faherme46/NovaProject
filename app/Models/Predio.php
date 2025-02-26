<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Predio extends Model
{
    use HasFactory;

    protected $guarded=[];


    public function personas()
    {
        return $this->belongsToMany(Persona::class,'predios_personas');
    }


    public function control()
    {
        return $this->belongsTo(Control::class);
    }

    public function apoderado(){
        return $this->belongsTo(Persona::class,'cc_apoderado','id');
    }


    public function getRelationPersona($id){
        if ($this->personas->contains($id)) {
            return 'Propietario';
        }
        if ($this->cc_apoderado==$id) {
            return 'Ap. Registrado';
        }
        return 'Apoderado';
    }
    public function getRelationPersonaInt($id){
        if ($this->personas->contains($id)) {
            return 1; //propietario
        }
        if ($this->cc_apoderado==$id) {
            return 2;//apoderado registrado
        }
        return 3;//apoderado
    }

    public function asistente(){
        return $this->control->persona();
    }

    public function getFullName(){

        return $this->descriptor1.' '.$this->numeral1.' '.$this->descriptor2.' '.$this->numeral2;
        // return $this->descriptor2.' '.$this->numeral2;
    }

    public function torre(){
        return $this->belongsTo(Torre::class,'numeral1','name');
    }





}
