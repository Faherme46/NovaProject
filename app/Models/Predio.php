<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Asignacion;
class Predio extends Model
{
    use HasFactory;

    protected $guarded=[];

    protected $casts = [
        'predios_id' => 'array',
    ];

    public function persona()
    {
        return $this->belongsTo(Persona::class, 'cc_propietario', 'id');
    }

    
    public function asignacion()
    {
        return $this->belongsToMany(Asignacion::class, 'asignacion_predios');
    }

    public function apoderado(){
        return $this->belongsTo(Persona::class,'cc_apoderado','id');
    }

}
