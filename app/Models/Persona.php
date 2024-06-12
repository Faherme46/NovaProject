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
        return $this->hasMany(Predio::class, 'cc_propietario', 'cedula');
    }


}
