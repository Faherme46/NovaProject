<?php

namespace App\Livewire\Elecciones;

use App\Models\Persona;
use App\Models\Predio;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;

use Livewire\Component;

class Registro extends Component
{
    #asistente
    #[Validate('required', message: 'Cedula requerida')]

    public $cedula = '';
    public $asistente = null;
    public $name;
    public $lastName;
    public $tipoId;

    #poderdantes
    public $ccPoderdante = '';
    public $poderdantes;
    public $poderdantesIDs = [];

    #predios
    public $prediosAvailable = [];
    public $prediosUnavailable = [];

    public $predioSelected = [];
    public $selectAll = false;



        public $sumCoef = 0;

    public function mount()
    {
        $this->tipoId = 'CC';
        $this->poderdantes = collect();
    }

    #[Layout('layout.asistencia')]
    public function render()
    {
        return view('views.elecciones.registro');
    }

    public function cleanData($cedula)
    {
        $this->reset([
            'asistente',
            'name',
            'lastName',
            'ccPoderdante',
            'sumCoef',
            'poderdantes',
            'poderdantesIDs',
            'prediosAvailable',
            'predioSelected',
            'selectAll'
        ]);
        if ($cedula) {
            $this->reset(['cedula']);
            return redirect()->route('elecciones.registrar');
        }
    }

    public function search($persona = null)
    {
        $this->validate();

        $this->cleanData(0);

        $this->asistente = ($persona) ? $persona : Persona::find($this->cedula);



        if ($this->asistente) {

            $this->name = $this->asistente->nombre;
            $this->lastName = $this->asistente->apellido;

            $this->addPredios($this->asistente->predios);
            $this->addPredios($this->asistente->prediosEnPoder);

            $this->selectAll = true;
        } else {
            $this->dispatch('showModal');
        }
    }

    public function addPredios($predios)
    {
        foreach ($predios as $predio) {
            if (!array_key_exists($predio->id, $this->prediosAvailable) && !$predio->control) {
                if (!$predio->control) {
                    $this->prediosAvailable[$predio->id] = $predio;
                    $this->predioSelected[] = $predio->id;
                }
            }
        }
        $this->setSumCoef();
    }
    public function updatedSelectAll($value)
    {
        if (!$value) {
            $this->predioSelected = [];
        } else {
            $this->predioSelected = array_keys($this->prediosAvailable);
        }
    }

    public function updatedPredioSelected()
    {
        $this->selectAll = count($this->predioSelected) === count($this->prediosAvailable);
    }

    public function setSumCoef()
    {
        $suma = 0;
        foreach ($this->prediosAvailable as $key => $predio) {
            if (in_array($key, $this->predioSelected)) {
                $suma = $suma + $predio->coeficiente;
            }
        }

        $this->sumCoef = $suma;
    }

    public function addPoderdante()
    {
        $this->validate();
        if ($this->ccPoderdante == $this->cedula) {
            return  back()->with('errorPropietarios', 'No puede ser igual al asistente');
        }
        // dd($arrayPropietarios);

        $poderdante = Persona::find($this->ccPoderdante);

        if ($poderdante) {

            if (in_array($this->ccPoderdante, $this->poderdantesIDs)) {
                return  back()->with('errorPropietarios', 'Ya fue añadido');
            }
            $this->poderdantesIDs[] = $this->ccPoderdante;
            $this->poderdantes = Persona::find($this->poderdantesIDs);
            $this->addPredios($poderdante->predios);
            $this->reset('ccPoderdante');
        } else {
            return back()->with('errorPropietarios', 'No se encontro');
        }
    }

    public function dropAllPoderdantes()
    {
        $this->reset(['ccPoderdante', 'poderdantes', 'poderdantesIDs']);
        $this->mount();
        $this->reset(['prediosAvailable', 'predioSelected']);
        if ($this->asistente) {
            $this->addPredios($this->asistente->predios);
        }
    }

    public function dropPoderdante($poderdanteId, $prediosId)
    {

        $ids = array_map(function ($predio) {
            return $predio['id'];
        }, $prediosId);

        foreach ($ids as $id) {
            unset($this->prediosAvailable[$id]);
        }
        $this->predioSelected = array_diff($this->predioSelected, $ids);
        $this->poderdantesIDs = array_diff($this->poderdantesIDs, [$poderdanteId]);
        $this->poderdantes = Persona::find(($this->poderdantesIDs));
        $this->setSumCoef();
    }


    #[On('add-predio')]
    public function addPredioToList($predioId)
    {
        if(!$this->cedula){
            return $this->addError('error','Primero debe ingresar el asistente');
        }
        $predio = Predio::find($predioId);
        if ($predio) {
            if($predio->control){
                $this->addError('error','Predio ya asignado al control '.$predio->control->id);
            }else{
                $this->prediosAvailable[$predioId] = $predio;
                $this->predioSelected[] = $predioId;
            }

        }
    }

    #[On('add-poderdante')]
    public function addPoderdanteToList($poderdanteId)
    {

        $this->ccPoderdante = $poderdanteId;
        $this->addPoderdante();
    }

    #[On('search-persona')]
    public function addPersonaToList($personaId)
    {
        $this->cleanData(0);

        $this->cedula = $personaId;

        $this->search();
    }
}
