<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;

use Illuminate\Support\Facades\Cache;

use App\Models\Control;
use Livewire\Attributes\Validate;

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

    public $proof1;

    public function cleanData($value)
    {
        if($value){
            $this->reset('controlIdR', 'controlIdL');
        }
        $this->reset([ 'asignacion', 'prediosR', 'prediosL', 'sumCoefR', 'sumCoefL']);
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
        if ($value>$this->maxControls) {
            $this->addError('controlId', 'El control no es valido');
            return;
        }
        if($value){
            $this->reset('prediosR');
            $control=Control::find($value);
            $asignacion=$control->asignacion;
            if($asignacion){
                $predios=$asignacion->predios;
                foreach ($predios as $predio) {
                    $this->prediosR[$predio->id]=$predio;
                }
            }

        }
    }

    public function updatedControlIdL($value) {
        if ($value>$this->maxControls) {
            $this->addError('controlId', 'El control no es valido');
            return;
        }
        if($value){
            $this->reset('prediosL');
            $control=Control::find($value);
            $asignacion=$control->asignacion;
            if($asignacion){
                $predios=$asignacion->predios;
                foreach ($predios as $predio) {
                    $this->prediosL[$predio->id]=$predio;
                }
            }
        }
    }

    public function proof(){
        dd($this->proof1);
    }

    public function toLeft($predioId){
        try {
            $this->prediosL[$predioId]=$this->prediosR[$predioId];
            unset($this->prediosR[$predioId]);
        } catch (\Throwable $th) {
            $this->addError('error', $th->getMessage());
        }
    }

    public function toRight($predioId){
        try {
            $this->prediosR[$predioId]=$this->prediosL[$predioId];
            unset($this->prediosL[$predioId]);
        } catch (\Throwable $th) {
            $this->addError('error', $th->getMessage());
        }
    }

    public function toLeftAll(){
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
        try {
            foreach ($this->prediosL as $key => $predio) {
                $this->prediosR[$key]=$predio;
            }
            $this->reset('prediosL');
        } catch (\Throwable $th) {
            $this->addError('error', $th->getMessage());
        }
    }

    public function exchange(){
        $temporary=$this->prediosL;
        $this->reset('prediosL');
        $this->toLeftAll();
        foreach ($temporary as $key => $predio) {
            $this->prediosR[$key]=$predio;
        }
    }

    public function undo(){
        $this->cleanData(0);
        $this->updatedControlIdL($this->controlIdL);

        $this->updatedControlIdR($this->controlIdR);
    }




    public function cambiar(){
        $this->validate();

        if (!$this->predioSelected) {
            return session()->flash('warning1', 'No hay predios para asignar');
        }

        $control = Control::find($this->controlId);

        //control Uso
        if ($control->asignacion) {
            try {
                $control->asignacion->predios()->attach(array_keys($this->predioSelected));
            } catch (\Throwable $th) {
                return  session()->flash('warning1', $th->getMessage());
            }
        }else{
            try {
                $asignacion = $control->asignacion()->create([
                    'sum_coef' => $this->sumCoef,
                    'estado' => 'activo'
                ]);
                $asignacion->predios()->attach(array_keys($this->predioSelected));
            } catch (\Exception $e) {
                return  session()->flash('warning1', $e->getMessage());
            }
        }

        session(['controlTurn'=>$this->controlId+1]);
        $this->cleanData(1);
        return session()->flash('success1', 'Predios Asignados con exito');
    }

    public function setInChange($value){
        $this->inChange=$value;
    }
}
