<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asamblea extends Model
{
    use HasFactory;

    protected $table = 'asambleas';
    protected $primaryKey = 'id_asamblea';

    protected $guarded=[];
}
