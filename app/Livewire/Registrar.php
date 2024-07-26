<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Validate;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;

use App\Models\Control;
use App\Models\Persona;
use App\Models\Predio;

use Illuminate\Database\QueryException;


class Registrar extends Component
{
    #controls
    public $controlIds;

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

    public $controls;
    public $controlH;


    #asignacion
    public $controlId;
    public $sumCoef = 0;

    public function mount()
    {
        $this->tipoId = 'CC';
        $this->poderdantes = collect();
    }

    #[Layout('layout.asistencia')]
    public function render()
    {
        $this->getAvailableControls();
        return view('livewire.registrar');
    }

    public function getAvailableControls()
    {
        $availableControls = Control::where('state', 4);

        $this->controlIds = $availableControls->pluck('id')->toArray();
        $controlTurn = session('controlTurn', 0);
        if ($controlTurn) {
            $this->controlId = $controlTurn;
        } else {
            $this->controlId = reset($this->controlIds);
        }
    }

    public function cleanData($cedula)
    {
        $this->reset([
            'asistente', 'name', 'lastName', 'ccPoderdante', 'sumCoef', 'controlId','controls','controlH',
            'poderdantes', 'poderdantesIDs', 'prediosAvailable', 'predioSelected', 'selectAll'
        ]);
        if ($cedula) {
            $this->reset(['cedula']);
            return redirect()->route('asistencia.registrar');
        }
    }

    public function search()
    {
        $this->validate();

        $this->cleanData(0);

        $this->asistente = Persona::find($this->cedula);

        if ($this->asistente) {
            if (!$this->asistente->controls->isEmpty()) {
                foreach ($this->asistente->controls as $control) {
                    $this->controls[$control->id] = $control;
                }
                $keys = array_keys($this->controls);
                $this->controlH = $keys[0];
            }

            $this->name = $this->asistente->nombre;
            $this->lastName = $this->asistente->apellido;

            $this->addPredios($this->asistente->predios);

            $this->selectAll = true;
        } else {
            $this->dispatch('showModal');
        }
    }

    public function creaPersona()
    {
        try {
            $this->asistente = Persona::create([
                'tipo_id' => $this->tipoId,
                'id' => $this->cedula,
                'nombre' => $this->toFirstMayus($this->name),
                'apellido' => $this->toFirstMayus($this->lastName)
            ]);

            $this->dispatch('hideModal');
        } catch (\Exception $e) {
            return back()->withCookie('error' . $e->getMessage());
        }
    }

    public function toFirstMayus($string)
    {
        return ucwords(strtolower($string));
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

    public function addPredios($predios)
    {
        foreach ($predios as $predio) {
            if (!array_key_exists($predio->id, $this->prediosAvailable) && !$predio->control) {
                $this->prediosAvailable[$predio->id] = $predio;
                $this->predioSelected[] = $predio->id;
            }
        }
        $this->setSumCoef();
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


    public function asignar($option)
    {
        #option 0 to asign 1 to edit
        $this->validate();

        if (!$this->prediosAvailable || !$this->predioSelected) {
            return session()->flash('warning1', 'No hay predios para asignar1');
        }
        if (!$this->controlId) {
            return session()->flash('warning1', 'No hay Control Seleccionado');
        }

        $control = Control::find($this->controlId);
        session(['controlTurn' => strval($this->controlId + 1)]);
        //control Uso


        try {

            if ($option){

                $controlH = $this->controls[$this->controlH];
                $controlH->attachPredios($this->prediosAvailable);
                $controlH->setSumCoef();
            }else{
                if ($control->asignacion()) {
                    $this->getAvailableControls();
                    $this->controlId = reset($this->controlIds);
                    return session()->flash('warning1', 'El control ya esta en uso');
                };
                $control->cc_asistente = $this->cedula;
                $control->attachPredios($this->prediosAvailable);
                $control->setCoef();
                $control->state = 1;
                $control->save();
            }

        } catch (QueryException $e) {
            if ($e->errorInfo[1] == 1062) {
                // Manejar la excepción específica de "Duplicate entry"
                return  session()->flash('warning1', 'El número de control ya está asignado. Por favor, elige otro.');
            } else {
                return  session()->flash('warning1', $e->getMessage());
            }
        } catch (\Exception $e) {
            return  session()->flash('warning1', $e->getMessage());
        }



        if (count($this->prediosAvailable) == count($this->predioSelected)) {
            $this->cleanData(1);
        }
        session()->flash('success1', 'Predios Asignados con exito');
        return redirect()->route('asistencia.registrar');
    }


    public function resetControl()
    {
        $this->reset(['controlH', 'controls']);
    }

    #[On('add-predio')]
    public function addPredioToList($predioId)
    {
        $this->validate();
        $predio = Predio::find($predioId);
        if ($predio) {
            $this->prediosAvailable[$predioId] = $predio;
            $this->predioSelected[] = $predioId;
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
    public function ver()
    {
    }
}
