<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Asignacion;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Control extends Model
{
    use HasFactory;

    public function asignacion(){
        return $this->hasOne(Asignacion::class);
    }
    public function retirar(){
        $asignacion= $this->asignacion;
        $asignacion->estado='retirado';
        return $asignacion->save();
    }

    
}
