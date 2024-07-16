<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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


    public function control()
    {
        return $this->belongsToMany(Control::class, 'control_predios');
    }

    public function apoderado(){
        return $this->belongsTo(Persona::class,'cc_apoderado','id');
    }

}
