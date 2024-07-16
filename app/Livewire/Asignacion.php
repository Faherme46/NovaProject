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

    #[Validate('required', message: 'Control Requerido')]
    public $controlId;
    public $sumCoefA;
    public $sumCoef;
    public $control;

    public $maxControls;

    public function cleanData()
    {
        $this->reset(['controlId', 'control', 'predioSelected', 'prediosAsigned', 'sumCoefA', 'sumCoef']);
        $this->mount();
    }
    public function mount()
    {
        $this->controlId = session('controlTurn');
        $this->maxControls = Cache::get('controles');
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

    public function dropAllSelected()
    {
        $this->reset('predioSelected');
    }

    #[On('set-control')]
    public function setControl($controlId)
    {
        $this->controlId = $controlId;
        $this->updatedControlId($controlId);
    }

    public function addPredioToList($predio)
    {

        if (!$predio->control->isEmpty()) {
            $this->control = $predio->control[0];
            $this->updatedControl();
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
        $this->reset('prediosAsigned', 'control');
        if (!$value) {
            return;
        }

        if ($value > $this->maxControls) {
            $this->addError('controlId', 'El control no es valido');
            return;
        }
        $this->controlId = $value;

        if ($value) {
            // Obtener el control por su ID
            $control = Control::find($value);
            if ($control) {
                // Obtener la asignación del control
                $this->control = $control;
                $this->updatedControl();
            }
        }
    }
    public function updatedControl()
    {
        $this->reset('prediosAsigned');

        if (!$this->control->predios->isEmpty()) {
            // Obtener el control por su ID
            foreach ($this->control->predios as $predio) {
                # code...
                $this->prediosAsigned[$predio->id] = $predio;
            }
        }
    }

    public function proof()
    {
        $persona = Persona::find('294962');
        dd($persona->prediosAsignados());
    }









    public function asignar()
    {
        $this->validate();

        if (!$this->predioSelected) {
            return session()->flash('warning1', 'No hay predios para asignar');
        }

        $control = Control::find($this->controlId);
        try {
            $control->setCoef();
            $control->state = 1;
            $control->predios()->attach(array_keys($this->predioSelected));
            $control->save();
        } catch (\Exception $e) {
            return  session()->flash('warning1', $e->getMessage());
        }

        session(['controlTurn' => $this->controlId + 1]);
        $this->cleanData();
        session()->flash('success1', 'Predios Asignados con exito');
        return redirect()->route('asistencia.asignacion');
    }
}
