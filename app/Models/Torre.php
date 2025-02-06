<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Torre extends Model
{
    use HasFactory;

    protected $guarded=[];


    public function candidatos(){
        return $this->belongsToMany(Persona::class,'torres_candidatos');
    }


    public function predios(){
        return $this->hasMany(Predio::class,'numeral1','name');
    }
}
