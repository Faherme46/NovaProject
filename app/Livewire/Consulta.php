<?php

namespace App\Livewire;

use Livewire\Attributes\Validate;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;

use Illuminate\Support\Facades\Cache;
use Exception;

use App\Models\Control;
use App\Models\Asignacion;
use PhpParser\Error;


class Consulta extends Component
{

    public $prediosR = [];
    public $prediosL = [];


    public $controlIdR;
    public $controlIdL;
    public $sumCoefR;
    public $sumCoefL;

    public $maxControls;
    public $inChange = true;

    public $messageL = 'Sin Predios';
    public $messageR = 'Sin Predios';

    public $nameL;
    public $nameR;

    public $proof1;

    public $controlRInvalid = false;
    public $controlLInvalid = false;

    public $changes = false;

    public $cedulaSearch;
    public $controlIdSearch;


    public function cleanData($value)
    {
        if ($value) {
            $this->reset('controlIdR', 'controlIdL');
        }
        $this->reset([
            'messageL', 'changes', 'messageR', 'prediosR', 'prediosL', 'sumCoefR',
            'sumCoefL', 'controlRInvalid', 'controlLInvalid', 'nameR', 'nameL'
        ]);
        $this->mount();
    }
    public function mount()
    {
        $this->maxControls = Cache::get('controles');
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



    public function dropAllSelected()
    {
        $this->reset('predioSelected');
    }


    public function setControlR($controlId)
    {
        $this->controlIdR = $controlId;
        $this->updatedControlIdR($controlId);
    }
    #[On('set-control')]
    public function setControlL($controlId)
    {
        $this->controlIdL = $controlId;
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
        $this->reset('prediosR', 'prediosL');
    }

    public function updatedControlIdR($value)
    {
        $control = $this->handleUpdate($value, false);
        if (!$control) {
            return null;
        }

        $this->controlRInvalid = false;
        $predios = collect();
        if ($control->state == 1) {

            if (Cache::get('inRegistro')) {
                $this->nameR = $control->persona->nombre;
            }
            $predios = $control->predios;
            foreach ($predios as $predio) {
                $this->prediosR[$predio->id] = $predio;
            }
        } else {

            $this->messageR = (!$predios->isEmpty()) ? '' : 'Sin Predios';
        }
    }
    public function updatedControlIdL($value)
    {

        $control = $this->handleUpdate($value, true);
        if (!$control) {
            return;
        }

        $this->controlLInvalid = false;
        $predios = collect();
        if ($control->state == 1) {

            if (Cache::get('inRegistro')) {
                $this->nameL = $control->persona->nombre;
            }
            $predios = $control->predios;
            foreach ($predios as $predio) {
                $this->prediosL[$predio->id] = $predio;
            }
        } else {

            $this->messageL = (!$predios->isEmpty()) ? '' : 'Sin Predios';
        }
    }


    public function handleUpdate($value, $left)
    {
        $this->resetValidation();
        if($left){
            $this->reset('messageL', 'controlLInvalid', 'prediosL');
        }else{
            $this->reset('messageR', 'controlRInvalid', 'prediosR');
        }

        if (!$value) {
            return null;
        }
        if ($value > $this->maxControls) {
            $this->addError(($left) ? 'controlIdL' : 'controlIdR', 'El control no es valido');
            return null;
        }
        if ($value == $this->controlIdL && !$left || $value == $this->controlIdR && $left) {
            $this->reset(($left) ? 'controlIdL' : 'controlIdR');
            $this->addError('controlId', 'Los controles No pueden ser iguales');
            return null;
        }
        $control = Control::find($value);
        if ($control->state != 3 && $control->state != 5) {
            return $control;
        } else {
            if ($left) {
                $this->messageL = ($control->state == 5) ? 'Control Entregado' : 'Control Retirado';
            } else {
                $this->messageR = ($control->state == 5) ? 'Control Entregado' : 'Control Retirado';
            }
            $this->controlLInvalid = true;
            return null;
        }
    }

    public function proof()
    {
        dd($this->proof1);
    }

    public function toLeft($predioId)
    {
        $this->validation();
        $this->changes = true;
        try {
            $this->prediosL[$predioId] = $this->prediosR[$predioId];
            unset($this->prediosR[$predioId]);
        } catch (\Throwable $th) {
            if ($th->getCode() != 0) {
                $this->addError('error', $th->getCode());
            }
        }
    }

    public function toRight($predioId)
    {
        $this->validation();
        $this->changes = true;
        if ($this->controlRInvalid) {
            $this->addError('error', 'El control B esta retirado');
            return;
        }
        try {
            $this->prediosR[$predioId] = $this->prediosL[$predioId];
            unset($this->prediosL[$predioId]);
        } catch (\Throwable $th) {
            if ($th->getCode() != 0) {
                $this->addError('error', $th->getCode());
            }
        }
    }

    public function toLeftAll()
    {

        $this->validation();
        $this->changes = true;
        try {
            foreach ($this->prediosR as $key => $predio) {
                $this->prediosL[$key] = $predio;
            }
            $this->reset('prediosR');
        } catch (\Throwable $th) {
            $this->addError('error', $th->getMessage());
        }
    }

    public function toRightAll()
    {
        $this->validation();
        $this->changes = true;
        if ($this->controlRInvalid) {
            $this->addError('error', 'El control B esta retirado');
            return;
        }

        try {
            foreach ($this->prediosL as $key => $predio) {
                $this->prediosR[$key] = $predio;
            }
            $this->reset('prediosL');
        } catch (\Throwable $th) {
            $this->addError('error', $th->getMessage());
        }
    }


    public function undo()
    {
        $this->changes = false;
        $this->cleanData(0);
        $this->updatedControlIdL($this->controlIdL);

        $this->updatedControlIdR($this->controlIdR);
    }

    public function validation(){
        if($this->inChange){
            $this->validate(
                ['controlIdR' => 'required', 'controlIdL' => 'required'],
                ['controlIdR.required' => 'Control B requerido', 'controlIdL.required' => 'Control A requerido']
            );
        }else{
            $this->validate(
                ['controlIdL' => 'required'],
                ['controlIdL.required' => 'Control A requerido']
            );
        }
    }

    public function setInChange($value)
    {
        $this->resetValidation();
        $this->cleanData(1);
        $this->inChange = $value;
    }


    public function storeInChange()
    {
        $this->validation();
        if (!$this->changes) {
            session()->flash('warning1', 'No hay cambios para guardar');
            return;
        }
        try {
            $controlR = Control::find($this->controlIdR);
            $controlL = Control::find($this->controlIdL);

            if ($this->controlRInvalid) {
                $this->addError('error', 'El control B esta retirado');
                return;
            }

            if (!$controlL->asignacion()) {
                $this->addError('Error', 'El Control A no ha sido asignado');
                return;
            }
            if (!$this->prediosR) {
                $this->addError('Error', 'No hay predios para asignar');
                return;
            }

            if (!$controlR->asignacion()) {
                $controlR->cc_asistente = ($controlL->cc_asistente) ? $controlL->cc_asistente : null;
                $controlR->state = 1;
            }

            $controlR->predios()->sync(array_keys($this->prediosR));
            $controlR->setCoef();
            $controlR->save();
            if ($this->prediosL) {
                $controlL->predios()->sync(array_keys($this->prediosL));
                $controlL->setCoef();
                $controlL->save();
            } else {
                $controlL->retirar();
            }
            session()->flash('success1', 'Guardado');
            return redirect()->route('consulta');
        } catch (\Throwable $th) {
            $this->addError('error', $th->getMessage());
        }
    }

    public function storeDetach()
    {
        $this->validation();
        $controlL = Control::find($this->controlIdL);
        if (!$this->prediosR) {
            $this->addError('error', 'Nada para retirar');
            return;
        }

        if (!$controlL->asignacion()) {
            $this->addError('error', 'El control no fue Asignado');
            return;
        }

        if ($this->controlLInvalid) {
            $this->addError('error', 'El control esta retirado/entregado');
            return;
        }
        try {
            if ($this->prediosL) {
                $controlL->predios()->sync(array_keys($this->prediosL));
                $controlL->setCoef();
                $controlL->save();
            } else {
                $controlL->retirar();
            }

            session()->flash('success1', 'Guardado');
            $this->cleanData(1);
            return redirect()->route('consulta');
        } catch (\Throwable $th) {
            $this->addError('error', $th->getMessage());
        }
    }

    public function searchPersona()
    {
        $this->dispatch('find-persona', id: $this->cedulaSearch);
    }
    public function searchControl()
    {
        $this->dispatch('find-control', id: $this->controlIdSearch);
    }
}
