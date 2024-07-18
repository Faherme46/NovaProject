<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Asignacion;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Control extends Model
{
    use HasFactory;

    protected $guarded=[];
    public function asignacion(){
        return ($this->state==1);
    }

    public function predios()
    {
        return $this->belongsToMany(Predio::class, 'control_predios');
    }
    public function persona()
    {
        return $this->belongsTo(Persona::class, 'cc_asistente', 'id');
    }


    public function retirar(){
        $this->state=3;
        $this->setCoef();
        $this->predios()->detach();
        $this->cc_asistente=null;
        $this->save();
    }


    public function changeState($value){
        $this->state=$value;
        $this->save();
    }

    public function ausentar(){
        $this->state=2;
        $this->save();
    }

    public function setCoef()
    {
        $this->sum_coef = $this->predios()->sum('coeficiente');
        return $this->save();
    }
}
