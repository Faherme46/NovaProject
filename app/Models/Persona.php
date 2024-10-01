<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    use HasFactory;

    protected $guarded=[];

    public function predios()
    {
        return $this->belongsToMany(Predio::class, 'predios_personas');
    }


    public function controls()
    {
        return $this->hasMany(Control::class, 'cc_asistente', 'id');
    }

    public function prediosAsignados()
    {
        $predios=$this->controls()->with('predios')->get()->pluck('predios')->flatten();
        return $predios;
    }


    public function prediosEnPoder(){
        return $this->hasMany(Predio::class, 'cc_apoderado', 'id');
    }


    public function user(){
        return $this->belongsTo(User::class);
    }

    public function signature(){
        return $this->hasOne(Signature::class);
    }

    public function fullName(){
        return $this->nombre.' '.$this->apellido;
    }

}
