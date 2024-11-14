<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Asignacion;
use Carbon\Carbon;
use DateTimeZone;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Control extends Model
{
    use HasFactory;


    public function asignacion()
    {
        return ($this->state != 4 );
    }

    public function predios()
    {
        return $this->HasMany(Predio::class);
    }
    public function persona()
    {
        return $this->belongsTo(Persona::class, 'cc_asistente', 'id');
    }


    public function retirar()
    {
        $this->state = 4;
        $this->setCoef();
        $this->predios()->delete();
        $this->cc_asistente = null;
        $this->save();
    }

    public function isAbsent()
    {
        if ($this->state == 2 || $this->state == 5) {
            return true;
        }
        return false;
    }
    public function changeState($value)
    {
        $this->state = $value;
        if ($value == 5) {
            $this->h_recibe = Carbon::now(new DateTimeZone('America/Bogota'));
        }
        $this->save();
    }

    public function ausentar()
    {
        $this->state = 2;
        $this->save();
    }

    public function setCoef()
    {
        $this->sum_coef_can = $this->predios()->where('vota', true)->sum('coeficiente');
        $this->sum_coef = $this->predios()->sum('coeficiente');
        $this->predios_vote = $this->predios()->where('vota', true)->count();
        return $this->save();
    }

    public function getPrediosCan()
    {
        return $this->predios()->where('vota', true)->count();
    }

    #agrega predios desde un array de predios
    public function attachPredios($arrayPredios)
    {
        foreach ($arrayPredios as $predio) {
            $this->predios()->save($predio);
        }
        $this->setCoef();
        return $this;
    }


    #elimina predios desde un array de predios
    public function deletePredios($prediosArray)
    {
        foreach ($prediosArray as $predio) {
            if ($predio->control_id == $this->id) {
                $predio->control_id = null;
                $predio->save();
            }
            # code...
        }
        $this->setCoef();
    }

    public function getStateTxt()
    {
        $states = [
            1 => 'Activo',
            2 => 'Ausente',
            4 => 'No Asignado',
            5 => 'Entregado'
        ];

        return $states[$this->state];
    }
}
