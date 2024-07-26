<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Asignacion;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Control extends Model
{
    use HasFactory;

    protected $guarded=[];
    public function asignacion(){
        return ($this->state==1);
    }

    public function predios()
    {
        return $this->HasMany(Predio::class);
    }
    public function persona()
    {
        return $this->belongsTo(Persona::class, 'cc_asistente', 'id');
    }


    public function retirar(){
        $this->state=3;
        $this->setCoef();
        $this->predios()->delete();
        $this->cc_asistente=null;
        $this->save();
    }

    public function isAbsent(){
        if($this->state==2 || $this->state==5){
            return true;
        }
        return false;
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
        $this->sum_coef_can = $this->predios()->where('vota',true)->sum('coeficiente');
        $this->sum_coef = $this->predios()->sum('coeficiente');
        $this->predios_vote= $this->predios()->where('vota',true)->count();
        return $this->save();
    }

    public function getPrediosCan(){
        return $this->predios()->where('vota',true)->count();
    }

    public function attachPredios($arrayPredios){
        foreach ($arrayPredios as $predio) {
            $this->predios()->save($predio);
        }
        return $this;
    }
}
