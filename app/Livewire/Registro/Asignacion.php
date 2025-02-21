<?php

namespace App\Livewire\Registro;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Attributes\On;

use Illuminate\Support\Facades\Cache;

use Illuminate\Database\QueryException;

use App\Models\Control;
use App\Models\Persona;
use App\Models\Predio;

use Carbon\Carbon;
use DateTimeZone;

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
        $this->maxControls = cache('asamblea')['controles'];
        $this->updatedControlId($this->controlId);
    }

    #[Layout('layout.asistencia')]
    public function render()
    {


        return view('views.registro.asignacion');
    }





    #[On('add-predio')]
    public function addPredio($predio)
    {

        if ($predio) {
            if ($predio['control']) {
                $this->addError('error', 'Predio ya asignado al control ' . $predio['id']);
            } else {
                $this->addPredioToList($predio);
            }
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

        if ($predio['control_id']) {
            $this->control = $predio['control_id'];
            $this->updatedControl();
        } else {
            $this->predioSelected[$predio['id']] = $predio;
        }
        
    }

    #[On('add-poderdante')]
    public function addPoderdanteToList($poderdanteId)
    {
        $persona = Persona::find($poderdanteId);
        if ($persona) {
            foreach ($persona->predios as  $predio) {
                $this->addPredioToList($predio);
            }
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
                $this->prediosAsigned[$predio->id] = $predio->toArray();
            }
        }
    }



    public function proofAsignacion()
    {
        $ids = [
            1 => 4,
            2 => 5,
            3 => 31,
            4 => 41,
            6 => 51,
            55 => 53,
            59 => 59,
            97 => 97,
            44 => 44,
            35 => 35,
            77 => 77,
            92 => 92,
            73 => 73,
            21 => 21,

        ];

        foreach ($ids as $key => $id) {
            $control = Control::find($key);
            $control->state = 1;

            $predio = Predio::find($id);
            $control->predios()->save($predio);
            $control->setCoef();
            $control->save();
        }

        return redirect()->route('home');
    }



    public function asignar()
    {
        $this->validate();

        if (!$this->predioSelected) {
            return session()->flash('warning', 'No hay predios para asignar');
        }

        $control = Control::find($this->controlId);
        if (!$control) {
            return $this->addError('error','Control no valido');
        }
        try {

            $control->state = 1;
            Predio::whereIn('id',array_keys($this->predioSelected))->update(['control_id'=>$control->id]);
            $control->setCoef();
            $control->h_entrega = Carbon::now(new DateTimeZone('America/Bogota'))->second(0)->format('H:i');
            $control->save();



        } catch (\Exception $e) {
            return  session()->flash('warning', $e->getMessage());
        }
        \Illuminate\Support\Facades\Log::channel('custom')->info('Registra el control {control}',['control' =>$control->id,'predios'=>array_keys($this->predioSelected)]);
        session(['controlTurn' => $this->controlId + 1]);
        $this->cleanData();
        session()->flash('success', 'Predios Asignados con exito');
        return redirect()->route('asistencia.asignacion');
    }
}
