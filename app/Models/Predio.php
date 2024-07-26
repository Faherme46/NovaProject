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




}
