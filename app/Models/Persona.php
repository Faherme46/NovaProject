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
        return $this->hasMany(Predio::class, 'cc_propietario', 'id');
    }

    public function controles()
    {
        return $this->hasMany(Control::class, 'cc_asistente', 'id');
    }

    public function prediosAsignados()
    {
        $cedula=$this->id;
        $predios=Predio::whereHas('asignacion', function ($query) use ($cedula) {
            $query->where('cc_asistente', $cedula);
        })->get();

        return $predios;
    }


    public function prediosEnPoder(){
        return $this->hasMany(Predio::class, 'cc_apoderado', 'id');
    }


}
