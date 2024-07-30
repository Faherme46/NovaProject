<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    use HasFactory;

    protected $guarded=[];

    // public $states=[
    //     1=>'Activo',
    //     2=> 'Ausente',
    //     3=> 'Retirado',
    //     4=> 'No Asignado',
    //     5=> 'Entregado'
    // ];
}
