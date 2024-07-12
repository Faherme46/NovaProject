<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Asignacion extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function persona()
    {
        return $this->belongsTo(Persona::class, 'cc_asistente', 'id');
    }
    public function control()
    {
        return $this->belongsTo(Control::class, 'id', 'id_control');
    }


    public function predios()
    {
        return $this->belongsToMany(Predio::class, 'asignacion_predios');
    }

    public function retirarPredios(){
        $this->predios()->detach();
        return $this->setCoef();
    }

    public function setCoef()
    {
        $this->sum_coef = $this->predios->sum('coeficiente');
        return $this->save();
    }
}
