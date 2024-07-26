<?php

namespace App\Livewire;

use Livewire\Attributes\Validate;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;

use Illuminate\Support\Facades\Cache;
use Exception;

use App\Models\Control;
use App\Models\Persona;
use App\Models\Predio;
use Livewire\Features\SupportLegacyModels\EloquentCollectionSynth;


class Consulta extends Component
{

    public $prediosR = [];
    public $prediosL = [];


    public $controlIdR;
    public $controlIdL;
    public $sumCoefR;
    public $sumCoefL;

    public $maxControls;
    public $tab = 1;
    public $tabNames = [
        1 => 'Cambiar',
        2 => 'Retirar',
        3 => 'Predio',
        4 => 'Personas'
    ];

    public $tiposId=[];


    public $previousTab;
    public $Predio = null;
    public $Persona = null;

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


    protected $listeners = [
        'showModalPredio' => 'searchPredio',
        'add-predio' => 'searchPredio',
        'add-poderdante' => 'searchPersona',
        'showModalPersona' => 'searchPersona',
        'set-control' => 'setControlL'
    ];
    protected $messages = [
        6 => 'Sin predios',
        5 => 'Control Entregado',
        4 => 'Control no asignado',
        3 => 'Control retirado'
    ];


    public function cleanData($value)
    {

        if ($value) {
            $this->reset('controlIdR', 'controlIdL');
        }
        $this->reset([
            'messageL', 'changes', 'messageR', 'prediosR', 'prediosL', 'sumCoefR', 'previousTab',
            'sumCoefL', 'controlRInvalid', 'controlLInvalid', 'nameR', 'nameL', 'Predio', 'Persona'
        ]);
        $this->mount();
        $this->dispatch('$refresh');
    }
    public function mount()
    {
        $this->Predio = Predio::find(3);
        $this->tiposId=Persona::distinct()->pluck('tipo_id');
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
        $this->dispatch('$refresh');
    }

    public function setControlL($controlId)
    {
        $this->controlIdL = $controlId;
        $this->updatedTab(1);

        $this->updatedControlIdL($controlId);
        $this->dispatch('$refresh');
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
        return $this->handleUpdate($value, false);
    }
    public function updatedControlIdL($value)
    {

        return $this->handleUpdate($value, true);
    }


    public function handleUpdate($value, $left)
    {
        $this->resetValidation();
        if ($left) {
            $this->reset('messageL', 'controlLInvalid', 'prediosL');
        } else {
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
        if (!$control) {
            return null;
        }
        if ($control->state == 1 || $control->state == 2) {
            $this->controlLInvalid = false;
            $predios = $control->predios;
            if ($left) {
                $this->nameL = (Cache::get('inRegistro')) ? $control->persona->nombre : '';
                $this->messageL = (!$predios->isEmpty()) ? '' : $this->messages[6];
                foreach ($predios as $predio) {
                    $this->prediosL[$predio->id] = $predio;
                }
            } else {
                $this->nameR = (Cache::get('inRegistro')) ? $control->persona->nombre : '';
                $this->messageL = (!$predios->isEmpty()) ? '' : $this->messages[6];
                $this->nameL = (Cache::get('inRegistro')) ? $control->persona->nombre : '';
                foreach ($predios as $predio) {
                    $this->prediosR[$predio->id] = $predio;
                }
            }
            return $control;
        } else {
            if ($left) {
                $this->messageL = $this->messages[$control->state];
            } else {
                $this->messageR =  $this->messages[$control->state];
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

    public function validation()
    {
        if ($this->tab == 1) {
            $this->validate(
                ['controlIdR' => 'required', 'controlIdL' => 'required'],
                ['controlIdR.required' => 'Control B requerido', 'controlIdL.required' => 'Control A requerido']
            );
        } elseif ($this->tab == 2) {
            $this->validate(
                ['controlIdL' => 'required'],
                ['controlIdL.required' => 'Control A requerido']
            );
        }
    }

    public function updatedTab($value)
    {
        if ($this->changes) {
            session()->flash('warning1', 'No se han guardado los cambios');
            $this->tab = $this->previousTab;
            return;
        } else {
            $this->resetValidation();
            $this->cleanData(1);
            $this->tab = $value;
            $this->previousTab = $value;
        }
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
            $this->success();
        } catch (\Throwable $th) {
            $this->addError('error', $th->getMessage());
        }
    }
    public function success()
    {
        session()->flash('success1', 'Guardado');
        $this->cleanData(1);
        $this->dispatch('refresh-predios');
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


            $this->success();
        } catch (\Throwable $th) {
            $this->addError('error', $th->getMessage());
        }
    }
    public function searchPredio($predioId)
    {
        $this->updatedTab(3);
        if (!$predioId) {
            return;
        }
        $this->Predio = Predio::find($predioId);
    }


    public function exchange()
    {
        $auxL = $this->controlIdL;

        $auxR = $this->controlIdR;
        $this->setControlL($auxR);
        $this->setControlR($auxL);
    }



    public function undoPredioChanges($id)
    {
        $this->searchPredio($id);
        $this->changes = false;
        $this->dispatch('$refresh');
    }

    public function undoPersonaChanges($id)
    {
        $this->changes = false;
    }

    public function searchPersona($personaId)
    {

        $this->updatedTab(4);
        if (!$personaId) {
            return;
        }


        $this->Persona = Persona::find(($personaId == 'CC') ? $this->cedulaSearch : $personaId);

        if (!$this->Persona) {
            return $this->addError('noFound', 'No se encontro');
        }
        $this->prediosL = $this->Persona->predios;
        if ($this->prediosL->isEmpty()) {
            $this->messageL = $this->messages[6];
        }
        $this->prediosR = $this->Persona->prediosAsignados();

        if ($this->prediosR->isEmpty()) {
            $this->messageR = $this->messages[6];
        }
    }
    public function searchControl()
    {
        $this->dispatch('find-control', id: $this->controlIdSearch);
    }
}
