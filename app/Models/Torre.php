<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Torre extends Model
{
    use HasFactory;

    protected $guarded=[];


    public function candidatos(){
        return $this->belongsToMany(Persona::class,'torres_candidato')->withPivot('votos','coeficiente');
    }

    public function candidatosCoef(){
        return $this->belongsToMany(Persona::class,'torres_candidato')->withPivot('coeficiente')->orderBy('pivot_coeficiente');
    }

    public function candidatosNom(){
        return $this->belongsToMany(Persona::class,'torres_candidato')->withPivot('votos')->orderBy('pivot_votos');
    }



    public function predios(){
        return $this->hasMany(Predio::class,'numeral1','name');
    }


}
