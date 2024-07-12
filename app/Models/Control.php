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
        $asignacion->estado=3;
        $this->state=3;
        $asignacion->save();
        $this->save();
    }

    public function ausentar(){
        $asignacion= $this->asignacion;
        $asignacion->estado=2;
        $this->state=2;
        $asignacion->save();
        $this->save();
    }


}
