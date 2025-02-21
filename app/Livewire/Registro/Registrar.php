<?php

namespace App\Livewire\Registro;

use Livewire\Component;
use Livewire\Attributes\Validate;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;

use App\Models\Control;
use App\Models\Persona;
use App\Models\Predio;
use Carbon\Carbon;
use DateTimeZone;
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

    public $asistenteControls;
    public $controlH=null;


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
        return view('views.registro.registrar');
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
            'asistente',
            'name',
            'lastName',
            'ccPoderdante',
            'sumCoef',
            'controlId',
            'asistenteControls',
            'controlH',
            'poderdantes',
            'poderdantesIDs',
            'prediosAvailable',
            'predioSelected',
            'selectAll'
        ]);
        if ($cedula) {
            $this->reset(['cedula']);
            return redirect()->route('asistencia.registrar');
        }
    }

    public function search($persona = null)
    {
        $this->validate();

        $this->cleanData(0);

        $this->asistente = ($persona) ? $persona : Persona::find($this->cedula);



        if ($this->asistente) {
            if (!$this->asistente->controls->isEmpty()) {
                foreach ($this->asistente->controls as $control) {
                    $this->asistenteControls[$control->id] = $control;
                }
                $keys = array_keys($this->asistenteControls);
                $this->controlH = $keys[0];
            }

            $this->name = $this->asistente->nombre;
            $this->lastName = $this->asistente->apellido;

            $this->addPredios($this->asistente->predios);
            $this->addPredios($this->asistente->prediosEnPoder);

            $this->selectAll = true;
        } else {
            $this->dispatch('showModal');
        }
    }

    public function searchPersonaCC($cc, $name, $lastName)
    {
        $persona = Persona::find($this->cedula);
        if ($persona) {
            $this->search($persona);
        } else {
            try {
                $this->asistente = Persona::create([
                    'tipo_id' => 'CC',
                    'id' => $cc,
                    'nombre' => strtoupper($name),
                    'apellido' => strtoupper($lastName)
                ]);

                $this->dispatch('hideModal');
            } catch (\Exception $e) {
                return back()->withCookie('error' . $e->getMessage());
            }
        }
    }
    public function creaPersona()
    {
        try {
            $this->asistente = Persona::create([
                'tipo_id' => $this->tipoId,
                'id' => $this->cedula,
                'nombre' => strtoupper($this->name),
                'apellido' => strtoupper($this->lastName)
            ]);

            $this->dispatch('hideModal');
            session()->flash('success', 'Asistente creado exitosamente');
        } catch (\Exception $e) {
            return back()->withCookie('error' . $e->getMessage());
        }
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
                if (!$predio->control) {
                    $this->prediosAvailable[$predio->id] = $predio->toArray();
                    $this->predioSelected[] = $predio->id;
                }
            }
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





    public function asignar($option)
    {
        #option 0 to asign 1 to edit
        $this->validate();

        if (!$this->prediosAvailable || !$this->predioSelected) {
            return session()->flash('warning', 'No hay predios para asignar1');
        }
        if (!$this->controlId) {
            return session()->flash('warning', 'No hay Control Seleccionado');
        }

        $control = Control::find($this->controlId);
        session(['controlTurn' => strval($this->controlId + 1)]);
        //control Uso

        $prediosToAsign = array_map(fn($value) => $this->prediosAvailable[$value], $this->predioSelected);
        $prediosToAsign = array_intersect_key($this->prediosAvailable, array_flip($this->predioSelected));

        try {

            if ($option) {

                $controlH = $this->asistenteControls[$this->controlH];
                Predio::whereIn('id',array_keys($prediosToAsign))->update(['control_id'=>$controlH->id]);
                $controlH->setCoef();
                \Illuminate\Support\Facades\Log::channel('custom')->info('Agrega predios al control {control}',['control' =>$control->id,'predios'=>array_values($this->predioSelected),'persona'=>$this->cedula]);
            } else {
                if ($control->asignacion()) {
                    $this->getAvailableControls();
                    $this->controlId = reset($this->controlIds);
                    return session()->flash('warning', 'El control ya esta en uso');
                };
                $control->cc_asistente = $this->cedula;
                $control->setCoef();
                $control->h_entrega = Carbon::now(new DateTimeZone('America/Bogota'))->second(0)->format('H:i');
                Predio::whereIn('id',array_keys($prediosToAsign))->update(['control_id'=>$control->id]);
                $control->state = 1;
                $control->save();
                $control->persona()->update([
                    'registered' => true,
                    'user_id' => auth()->id()
                ]);
                \Illuminate\Support\Facades\Log::channel('custom')->info('Registra el control {control}',['control' =>$control->id,'predios'=>array_values($this->predioSelected),'persona'=>$this->cedula]);
            }
        } catch (QueryException $e) {
            if ($e->errorInfo[1] == 1062) {
                // Manejar la excepción específica de "Duplicate entry"
                return  session()->flash('warning', 'El número de control ya está asignado. Por favor, elige otro.');
            } else {
                return  session()->flash('warning', $e->getMessage());
            }
        } catch (\Exception $e) {
            return  session()->flash('warning', $e->getMessage());
        }



        if (count($this->prediosAvailable) == count($this->predioSelected)) {
            $this->cleanData(1);
        }
        session()->flash('success', 'Predios Asignados con exito');
        return redirect()->route('asistencia.registrar');
    }


    public function resetControl()
    {
        $this->reset(['controlH', 'asistenteControls']);
    }

    #[On('add-predio')]
    public function addPredioToList($predio)
    {
        if(!$this->cedula){
            return $this->addError('error','Primero debe ingresar el asistente');
        }
        if ($predio) {
            if ($predio['control_id']) {
                $this->addError('error', 'Predio ya asignado al control ' . $predio['control_id']);
            } else {
                $this->prediosAvailable[$predio['id']] = $predio;
                $this->predioSelected[] = $predio['id'];
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

    public function changePredios() {}
}
