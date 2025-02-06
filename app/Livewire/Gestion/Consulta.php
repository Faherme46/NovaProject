<?php

namespace App\Livewire\Gestion;


use Livewire\Attributes\Layout;
use Livewire\Component;

use Illuminate\Support\Facades\Cache;

use App\Models\Control;
use App\Models\Persona;
use App\Models\Predio;


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

        1 => 'Cambiar Control',
        2 => 'Retirar Control',
        3 => 'Consultar Predio',
        4 => 'ConsultarPersonas',
        5 => 'Consultar Control',
    ];

    public $tiposId = [];


    public $previousTab;
    public $Predio;
    public $Persona;
    public $Control;

    public $cedulaPersonita;
    public $namePersonita;
    public $lastNamePersonita;
    public $tipoId = 'CC';
    public $personaFound = null;

    public $messageL = 'Sin Predios';
    public $messageR = 'Sin Predios';
    public $controlRInvalid = false;
    public $controlLInvalid = false;
    public $changes = false;
    public $cedulaSearch;
    public $controlIdSearch;

    public $predioIdSearch=null;

    public $distincts =[
        'descriptor1'=>[],
        'descriptor2'=>[],
        'numeral1'=>[]
    ];


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
        2 => 'Control Ausente',
        1 => ''
    ];


    public function cleanData($value,$tab=5)
    {

        if ($value) {
            $this->reset('controlIdR', 'controlIdL');
        }
        $this->reset([
            'messageL',
            'changes',
            'messageR',
            'prediosR',
            'prediosL',
            'sumCoefR',
            'previousTab',
            'sumCoefL',
            'controlRInvalid',
            'controlLInvalid',
            'Predio',
            'Persona',
            'controlIdSearch',
            'Control',
            'predioIdSearch'
        ]);
        $this->mount($tab);
        $this->dispatch('$refresh');
    }
    public function mount($tab=5)
    {
        $this->tab = $tab;
        $this->tiposId = Persona::distinct()->pluck('tipo_id');
        $this->maxControls = cache('asamblea')['controles'];
    }

    #[Layout('layout.asistencia')]
    public function render()
    {
        $this->setSumCoef();
        return view('views.gestion.consulta');
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
    //todo borrar si no hacen nada
    // public function addPredioToR($predio)
    // {
    //     $this->prediosR[$predio->id] = $predio;
    // }

    // public function addPredioToL($predio)
    // {
    //     $this->prediosR[$predio->id] = $predio;
    // }



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
        $this->handleUpdate($value, false);
        $this->controlIdR = $value;
        return;
    }
    public function updatedControlIdL($value)
    {
        $this->handleUpdate($value, true);
        $this->controlIdL = $value;
        return;
    }


    public function handleUpdate($controlId, $left)
    {
        $this->resetValidation();
        if ($left) {
            $this->reset('messageL', 'controlLInvalid', 'prediosL');
        } else {
            $this->reset('messageR', 'controlRInvalid', 'prediosR');
        }

        if (!$controlId) {
            return null;
        }
        if ($controlId > $this->maxControls) {
            $this->addError(($left) ? 'controlIdL' : 'controlIdR', 'El control no es valido');
            return null;
        }
        if ($controlId == $this->controlIdL && !$left || $controlId == $this->controlIdR && $left) {
            $this->reset(($left) ? 'controlIdL' : 'controlIdR');
            $this->addError('controlId', 'Los controles No pueden ser iguales');
            return null;
        }
        $control = Control::find($controlId);
        if (!$control) {
            return null;
        }


        if ($control->state != 4) {
            $this->controlLInvalid = false;
            $predios = $control->predios;
            if ($left) {
                $this->messageL = $this->messages[$control->state];
                foreach ($predios as $predio) {
                    $this->prediosL[$predio->id] = $predio;
                }
            } else {
                $this->messageL = $this->messages[$control->state];
                foreach ($predios as $predio) {
                    $this->prediosR[$predio->id] = $predio;
                }
            }
        }
        return $control;
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
                $this->addError('error', '1 ' . $th->getMessage());
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
                $this->addError('error', '2 ' . $th->getMessage());
            }
        }
    }
    //todo borrar si no hace nada
    // public function toLeftAll()
    // {

    //     $this->validation();
    //     $this->changes = true;
    //     try {
    //         foreach ($this->prediosR as $key => $predio) {
    //             $this->prediosL[$key] = $predio;
    //         }
    //         $this->reset('prediosR');
    //     } catch (\Throwable $th) {
    //         $this->addError('error', '3 ' . $th->getMessage());
    //     }
    // }

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
            $this->messageL = 'Se retirará el control';
            $this->reset('prediosL');
        } catch (\Throwable $th) {
            $this->addError('error', '4 ' . $th->getMessage());
        }
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
            session()->flash('warning', 'No se han guardado los cambios');
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
            session()->flash('warning', 'No hay cambios para guardar');
            return;
        }
        try {
            $controlR = Control::find($this->controlIdR);
            $controlL = Control::find($this->controlIdL);
            $prediosChange=array_diff(array_keys($this->prediosR),$controlR->predios->pluck('id')->toArray());



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
                $controlR->state = 1;
                if (cache('asamblea')['registro']) {
                    $controlR->cc_asistente = ($controlL->cc_asistente) ? $controlL->cc_asistente : null;
                }
            }

            $controlL->deletePredios($this->prediosR);
            $controlR->attachPredios($this->prediosR);
            $controlL->setCoef();
            if (!$this->prediosL) {
                $controlL->retirar();
            }

            \Illuminate\Support\Facades\Log::channel('custom')->info('Se cambian los predios del control {controlA} al control {controlB}',['controlA' =>$controlL->id,'controlB' =>$controlR->id,'predios'=>array_values($prediosChange)]);
            $this->success();
        } catch (\Throwable $th) {
            $this->addError('error', '5 ' . $th->getMessage());
        }
    }
    public function success()
    {
        session()->flash('success', 'Cambios Guardados');
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
                $controlL->deletePredios($this->prediosR);
                $controlL->save();
            } else {
                $controlL->retirar();
            }
            $controlL->setCoef();
            \Illuminate\Support\Facades\Log::channel('custom')->info('Se le retiran los predios al control {control}',['control' =>$controlL->id,'predios'=>array_keys($this->prediosR)]);
            $this->success();
        } catch (\Throwable $th) {
            $this->addError('error', '6 ' . $th->getMessage());
        }
    }
    public function searchPredio($predioId=null)
    {
        if(!$predioId){
            $predioId=$this->predioIdSearch;
        }
        if(!$predioId){
    return;
        }
        $this->updatedTab(3);
        if (!$predioId) {
            return;
        }
        $this->Predio = Predio::find($predioId);
        if(!$this->Predio){
            session()->flash('warning','No se encontró');
        }
    }


    public function forgetPredio(){
        $this->reset(['Predio','predioIdSearch']);
        $this->updatedTab(3);
    }

    public function createPredio(){
        $this->distincts = [
            'descriptor1' => Predio::distinct()->pluck('descriptor1'),
            'numeral1' => Predio::distinct()->pluck('numeral1'),
            'descriptor2' => Predio::distinct()->pluck('descriptor2'),
        ];
        $this->dispatch('crearPredioModalShow');

    }


    public function searchControl()
    {
        $this->Control = Control::find($this->controlIdSearch);
        $this->Control->setCoef();
        if (!$this->Control) {
            $this->addError('noFound', 'No se encontro el control');
        }
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
        if (!$personaId) {
            return;
        }
        $this->updatedTab(4);

        $this->Persona = Persona::find(($personaId == 'CC') ? $this->cedulaSearch : $personaId);

        if (!$this->Persona) {
            return $this->addError('noFound', 'No se encontro');
        }
        $this->prediosL = $this->Persona->predios;
        if (!$this->prediosL) {
            $this->messageL = $this->messages[$this->Persona->control->state];
        }
        $this->prediosR = $this->Persona->prediosAsignados();

        if (!$this->prediosR) {
            $this->messageR = $this->messages[$this->Persona->control->state];
        }
    }


    public function searchPersonita()
    {
        $personita = Persona::find($this->cedulaPersonita);
        if ($personita) {
            $this->personaFound = $personita->nombre . ' ' . $personita->apellido;
        } else {

            $this->dispatch('addPropietarioModalHide');
            $this->dispatch('crearPropietarioModalShow');
        }
        $this->dispatch('addPropietarioModalShow');
    }

    public function addPropietario()
    {

        $this->Predio->personas()->attach($this->cedulaPersonita);
        $this->reset('cedulaPersonita', 'personaFound');
        session()->flash('success', 'Propietario agregado');
    }


    public function dropPropietario()
    {
        $this->Predio->personas()->detach($this->ccToDrop);
        session()->flash('warning', 'Propietario Eliminado');
    }

    public $nameToDrop;
    public $ccToDrop;
    public function setPropietarioToDrop($persona)
    {
        $this->nameToDrop = $persona['nombre'] . ' ' . $persona['apellido'];
        $this->ccToDrop = $persona['id'];
        $this->dispatch('dropPersonaModalShow');
    }

    public function dropControl()
    {
        $this->reset('Control', 'controlIdSearch');
    }

    public function creaPersona()
    {
        $this->dispatch('addPropietarioModalHide');
        try {
            Persona::create(
                [
                    'id' => $this->cedulaPersonita,
                    'tipo_id' => $this->tipoId,
                    'nombre' => strtoupper($this->namePersonita),
                    'apellido' => strtoupper($this->lastNamePersonita)
                ]
            );
            session()->flash('success', 'Persona creada y agregada correctamente');
            $this->dispatch('crearPropietarioModalHide');
            $this->reset('namePersonita', 'lastNamePersonita');
            $this->addPropietario();
            \Illuminate\Support\Facades\Log::channel('custom')->info('Crea una persona', ['cc' => $this->cedulaPersonita, 'nombre' => strtoupper($this->namePersonita), 'apellido' => strtoupper($this->lastNamePersonita)]);
        } catch (\Throwable $th) {
            $this->addError('error', 'Error al crear la persona: ' . $th->getMessage());
        }
        $this->dispatch('crearPropietarioModalHide');
    }
}
