<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asignacion extends Model
{
    use HasFactory;
    protected $guarded=[];

    public function persona()
    {
        return $this->belongsTo(Persona::class, 'cc_asistente', 'id');
    }

    public function predios()
    {
        return $this->belongsToMany(Predio::class, 'asignacion_predios');
    }
}

