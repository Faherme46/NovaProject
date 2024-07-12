<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Attributes\On;

use Illuminate\Support\Facades\Cache;

use Illuminate\Database\QueryException;

use App\Models\Control;
use App\Models\Persona;
use App\Models\Predio;

class Asignacion extends Component
{
    public $predioSelected = [];
    public $prediosAsigned = [];
    public $prediosToDrop = [];

    #[Validate('required',message:'Control Requerido')]
    public $controlId;
    public $sumCoefA;
    public $sumCoef;

    public $asignacion;
    public $maxControls;

    public function cleanData()
    {
        $this->reset([ 'controlId','asignacion', 'predioSelected', 'prediosAsigned', 'prediosToDrop', 'sumCoefA', 'sumCoef']);
        $this->mount();
    }
    public function mount() {
        $this->controlId=session('controlTurn',0);
        $this->maxControls= Cache::get('controles');
    }

    #[Layout('layout.asistencia')]
    public function render()
    {

        $this->setSumCoef();
        return view('livewire.asignacion');
    }



    public function setSumCoef()
    {
        $suma = 0;
        foreach ($this->predioSelected as  $predio) {
            $suma = $suma + $predio->coeficiente;
        }
        $this->sumCoef = $suma;
        foreach ($this->prediosAsigned as  $predio) {
            $suma = $suma + $predio->coeficiente;
        }
        $this->sumCoefA = $suma;
        $suma = 0;

    }

    #[On('add-predio')]
    public function addPredio($predioId)
    {
        $predio = Predio::find($predioId);
        if ($predio) {
            $this->addPredioToList($predio);
        }
    }

    public function dropAllSelected(){
        $this->reset('predioSelected');
    }

    #[On('set-control')]
    public function setControl($controlId){
        $this->controlId=$controlId;
        $this->updatedControlId($controlId);
    }

    public function addPredioToList($predio)
    {

        if (!$predio->asignacion->isEmpty()) {
            $this->asignacion = $predio->asignacion[0];
            $this->updatedAsignacion();
        } else {
            $this->predioSelected[$predio->id] = $predio;
        }
    }

    #[On('add-poderdante')]
    public function addPoderdanteToList($poderdanteId)
    {
        try {
            $persona = Persona::findOrFail($poderdanteId);
            foreach ($persona->predios as  $predio) {
                $this->addPredioToList($predio);
            }
        } catch (\Throwable $th) {
            session()->flash('error1', $th->getMessage());
        }
    }

    public function dropPredio($predioId)
    {
        unset($this->predioSelected[$predioId]);
    }


    public function dropAllPredios()
    {
        $this->reset('predioSelected');
    }

    public function updatedControlId($value)
    {

        if ($value>$this->maxControls) {
            $this->addError('controlId', 'El control no es valido');
            return;
        }
        $this->controlId = $value;

        if ($value) {
            // Obtener el control por su ID
            $control = Control::find($value);
            if($control){
                // Obtener la asignación del control
                $this->asignacion = $control->asignacion;
                $this->updatedAsignacion();
            }
        }
    }
    public function updatedAsignacion()
    {
        $this->reset('prediosAsigned');
        if($this->asignacion){
            if (!$this->asignacion->predios->isEmpty()) {
                // Obtener el control por su ID
                foreach ($this->asignacion->predios as $predio) {
                    # code...
                    $this->prediosAsigned[$predio->id]=$predio;
                }
            }
        }
    }

    public function proof(){
        dd($this->prediosAsigned);
    }

    public function toDropList($predioId){
        try {
            $this->prediosToDrop[$predioId]=$this->prediosAsigned[$predioId];
            unset($this->prediosAsigned[$predioId]);
        } catch (\Throwable $th) {
            //throw $th;
        }

    }
    public function toDropListAll(){
        try {
            foreach ($this->prediosAsigned as $key => $predio) {
                $this->prediosToDrop[$key]=$predio;
            }

            $this->reset('prediosAsigned');
        } catch (\Throwable $th) {
            //throw $th;
        }

    }

    public function toAsignList($predioId){
        try {
            $this->prediosAsigned[$predioId]=$this->prediosToDrop[$predioId];
            unset($this->prediosToDrop[$predioId]);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function toAsignListAll(){
        try {
            foreach ($this->prediosToDrop as $key => $predio) {
                $this->prediosAsigned[$key]=$predio;
            }

            $this->reset('prediosToDrop');
        } catch (\Throwable $th) {
            //throw $th;
        }

    }


    public function asignar()
    {
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
        $this->cleanData();
        return session()->flash('success1', 'Predios Asignados con exito');



    }



}
