<?php

namespace App\Livewire;

use Livewire\Attributes\Validate;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;

use Illuminate\Support\Facades\Cache;

use App\Models\Control;
use App\Models\Asignacion;


class Consulta extends Component
{


    public $prediosR = [];
    public $prediosL = [];


    public $controlIdR;
    public $controlIdL;
    public $sumCoefR;
    public $sumCoefL;

    public $asignacion;
    public $maxControls;
    public $inChange=true;

    public $messageL='Sin Predios';
    public $messageR='Sin Predios';

    public $proof1;

    public $controlRInvalid=false;
    public $controlLInvalid=false;

    public $changes=false;

    public function cleanData($value)
    {
        if($value){
            $this->reset('controlIdR', 'controlIdL');
        }
        $this->reset([ 'messageL','changes','messageR','asignacion', 'prediosR', 'prediosL', 'sumCoefR', 'sumCoefL','controlRInvalid','controlLInvalid']);
        $this->mount();
    }
    public function mount() {
        $this->maxControls= Cache::get('controles');
    }

    #[Layout('layout.asistencia')]
    public function render()
    {
        $this->setSumCoef();
        return view('livewire.consulta');
    }



    public function setSumCoef()
    {
        $suma = 0;
        foreach ($this->prediosR as  $predio) {
            $suma = $suma + $predio->coeficiente;
        }
        $this->sumCoefR = $suma;
        $suma = 0;
        foreach ($this->prediosL as  $predio) {
            $suma = $suma + $predio->coeficiente;
        }
        $this->sumCoefL = $suma;
    }

    public function addPredioToR($predio)
    {
        $this->prediosR[$predio->id] = $predio;
    }

    public function addPredioToL($predio)
    {
        $this->prediosR[$predio->id] = $predio;
    }



    public function dropAllSelected(){
        $this->reset('predioSelected');
    }


    public function setControlR($controlId){
        $this->controlIdR=$controlId;
        $this->updatedControlIdR($controlId);
    }
    #[On('set-control')]
    public function setControlL($controlId){
        $this->controlIdL=$controlId;
        $this->updatedControlIdL($controlId);
    }


    public function dropPredioR($predioId)
    {
        unset($this->prediosR[$predioId]);
    }
    public function dropPredioL($predioId)
    {
        unset($this->prediosL[$predioId]);
    }

    public function dropAllPredios()
    {
        $this->reset('prediosR','prediosL');
    }

    public function updatedControlIdR($value) {
        $this->reset('messageR','controlRInvalid');
        if(!$value){$this->reset('prediosR');return;}
        if ($value>$this->maxControls) {
            $this->addError('controlIdR', 'El control no es valido');
            return;
        }
        if ($value==$this->controlIdL){
            $this->addError('controlId', 'Los controles No pueden ser iguales');
            $this->reset('controlIdR');
            return;
        }
            $this->reset('prediosR');
            $control=Control::find($value);
            $asignacion=$control->asignacion;
            if($asignacion){
                if($asignacion->estado!='retirado'){
                    $predios=$asignacion->predios;
                    $this->messageR=(!$predios->isEmpty())?'':'Sin Predios';
                    foreach ($predios as $predio) {
                        $this->prediosR[$predio->id]=$predio;
                    }
                    $this->controlRInvalid=false;
                }else{
                    $this->messageR='Control Retirado';
                    $this->controlRInvalid=true;
                }
            }

    }

    public function updatedControlIdL($value) {
        $this->reset('messageL','controlLInvalid');
        if(!$value){$this->reset('prediosL'); return;}
        if ($value>$this->maxControls) {
            $this->addError('controlIdL', 'El control no es valido');
            return;
        }
        if ($value==$this->controlIdR){
            $this->addError('controlId', 'Los controles No pueden ser iguales');
            $this->reset('controlIdL');
            return;
        }

        $this->reset('prediosL');
        $control=Control::find($value);
        $asignacion=$control->asignacion;
        if($asignacion){
            if($asignacion->estado!='retirado'){
                $predios=$asignacion->predios;
                $this->messageL=(!$predios->isEmpty())?'':'Sin Predios';
                $this->controlRInvalid=false;
                foreach ($predios as $predio) {
                    $this->prediosL[$predio->id]=$predio;
                }
            }else{
                $this->messageL='Control Retirado';
                $this->controlRInvalid=true;
            }
        }

    }

    public function proof(){
        dd($this->proof1);
    }

    public function toLeft($predioId){
        $this->changes=true;
        try {
            $this->prediosL[$predioId]=$this->prediosR[$predioId];
            unset($this->prediosR[$predioId]);
        } catch (\Throwable $th) {
            if($th->getCode()!=0){
                $this->addError('error', $th->getCode());
            }
        }
    }

    public function toRight($predioId){
        $this->changes=true;
        if ($this->controlRInvalid) {
            $this->addError('error','El control B esta retirado');
            return;
        }
        try {
            $this->prediosR[$predioId]=$this->prediosL[$predioId];
            unset($this->prediosL[$predioId]);
        } catch (\Throwable $th) {
            if($th->getCode()!=0){
                $this->addError('error', $th->getCode());
            }
        }
    }

    public function toLeftAll(){
        $this->changes=true;
        try {
            foreach ($this->prediosR as $key => $predio) {
                $this->prediosL[$key]=$predio;
            }
            $this->reset('prediosR');
        } catch (\Throwable $th) {
            $this->addError('error', $th->getMessage());
        }
    }

    public function toRightAll(){
        $this->changes=true;
        if ($this->controlRInvalid) {
            $this->addError('error','El control B esta retirado');
            return;
        }

        try {
            foreach ($this->prediosL as $key => $predio) {
                $this->prediosR[$key]=$predio;
            }
            $this->reset('prediosL');
        } catch (\Throwable $th) {
            $this->addError('error', $th->getMessage());
        }
    }


    public function undo(){
        $this->changes=false;
        $this->cleanData(0);
        $this->updatedControlIdL($this->controlIdL);

        $this->updatedControlIdR($this->controlIdR);
    }

    public function setInChange($value){
        $this->cleanData(1);
        $this->inChange=$value;

    }


    public function storeInChange(){
        $this->validate(['controlIdR'=>'required','controlIdL'=>'required'],
        ['controlIdR.required'=>'Control B requerido','controlIdL.required'=>'Control A requerido']);
        if(!$this->changes){
            session()->flash('warning1','No hay cambios para guardar');
            return;
        }
        try{
            $controlR=Control::find($this->controlIdR);
            $controlL=Control::find($this->controlIdL);
            $asignacionR=$controlR->asignacion;
            $asignacionL=$controlL->asignacion;

            if ($this->controlRInvalid) {
                $this->addError('error','El control B esta retirado');
                return;
            }

            if( !$asignacionL){
                $this->addError('Error','El Control A no ha sido asignado');
                return;
            }
            if(!$this->prediosR){
                $this->addError('Error','No hay predios para asignar');
                return;
            }

            if (!$asignacionR) {
                $asignacionR=$controlR->asignacion()->create([
                    'cc_asistente'=>($controlL->asignacion->cc_asistente)?$controlL->asignacion->cc_asistente:null,
                    'sum_coef' => $this->sumCoefR,
                    'estado' => 'activo'
                ]);
            }

            $asignacionR->predios()->sync(array_keys($this->prediosR));
            $asignacionR->sum_coef=$this->sumCoefR;
            $asignacionR->save();
           if($this->prediosL){
                $asignacionL->predios()->sync(array_keys($this->prediosL));
                $asignacionL->setCoef();
                $asignacionL->save();
           }else{
                $controlL->retirar();
                $controlL->asignacion->retirarPredios();
           }

           session()->flash('success1','Guardado');
           $this->cleanData(1);
           return redirect()->route('consulta');

        }catch(\Throwable $th){
            $this->addError('error',$th->getMessage());
        }
    }

    public function storeDetach(){
        $controlL=Control::find($this->controlIdL);
        if (!$this->prediosR) {
            $this->addError('error','Nada para retirar');
            return;
        }

        if (!$controlL->asignacion) {
            $this->addError('error','El control no fue Asignado');
            return;
        }

        if ($this->controlLInvalid) {
            $this->addError('error','El control esta retirado');
            return;
        }
        try {
            if($this->prediosL){
                $controlL->asignacion->predios()->sync(array_keys($this->prediosL));
                $controlL->asignacion->setCoef();
            }else{
                $controlL->asignacion->predios()->detach();
                $controlL->asignacion->setCoef();
                $controlL->retirar();
            }

            session()->flash('success1','Guardado');
           $this->cleanData(1);
           return redirect()->route('consulta');
        } catch (\Throwable $th) {
            $this->addError('error',$th->getMessage());
        }

    }
}
