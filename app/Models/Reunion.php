<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reunion extends Model
{
    use HasFactory;

    protected $table = 'reuniones';
    protected $primaryKey = 'id_reunion';

    protected $fillable = [
        'nombre',
        'lugar',
        'fecha',
        'hora',
        'estado',
        'registro',
        'h_inicio',
        'h_cierre',
        'nombreBd'
    ];
}
