<?php

namespace App\Livewire\Elecciones;

use App\Models\Control;
use App\Models\Persona;
use App\Models\Predio;
use App\Models\Terminal;
use Carbon\Carbon;
use DateTimeZone;
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
    public $torres=[];


        public $sumCoef = 0;

    public function mount()
    {
        $this->tipoId = 'CC';
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
            'prediosAvailable',
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
        $this->reset(['ccPoderdante', 'poderdantes', 'poderdantesIDs', 'prediosAvailable']);
        $this->mount();
        $this->reset(['predioSelected']);
        if ($this->asistente) {
            $this->addPredios($this->asistente->predios);
        }
    }

    public function dropPoderdante($poderdanteId, $prediosId)
    {

        $predios = Predio::whereHas('personas', function ($query) use ($poderdanteId) {
            $query->where('persona_id', $poderdanteId);
        })->pluck('id')->toArray();
        $this->predioSelected = array_diff($this->predioSelected, $predios);
        unset($this->prediosAvailable, $predios);
        $this->poderdantesIDs = array_diff($this->poderdantesIDs, [$poderdanteId]);
        $this->poderdantes = Persona::find(($this->poderdantesIDs));
    }
    public function addPredios($predios)
    {
        foreach ($predios as $predio) {
            if (!array_key_exists($predio->id, $this->prediosAvailable) && !$predio->control) {
                $this->prediosAvailable[$predio->id] = $predio->toArray();
                $this->predioSelected[] = $predio->id;
                if(!in_array($predio->numeral1,$this->torres)){
                    $this->torres[]=$predio->numeral1;
                }
            }
        }
    }

    #[On('add-predio')]
    public function addPredioToList($predio)
    {

        if (!$this->cedula) {
            return $this->addError('error', 'Primero debe ingresar el asistente');
        }

        if ($predio['control_id']) {
            $this->addError('error', 'Este predio ya se ha registrado');
        } else {
            if(!in_array($predio['numeral1'],$this->torres)){
                $this->torres[]=$predio['numeral1'];
            }
            $this->prediosAvailable[$predio['id']] = $predio;
            $this->predioSelected[] = $predio['id'];
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


    public function registrar()
    {

        if (!$this->prediosAvailable || !$this->predioSelected) {
            return session()->flash('warning', 'No hay predios para asignar');
        }
        $prediosToAsign = array_intersect($this->predioSelected, array_keys($this->prediosAvailable));


        $terminalFree=Terminal::where('available',true)->exists();
        if(!$terminalFree){
            return session()->flash('warning', 'No hay terminales libres, espere a que se libere');
        }
        foreach ($this->torres as $torre) {

            $control = Control::create(['cc_asistente' => $this->asistente->id,  'state' => 2]);

            try {

                Predio::where('numeral1',$torre)->whereIn('id',$prediosToAsign)->update(['control_id'=>$control->id]);
                $control->setCoef();
                $control->vote = $torre;
                $control->h_entrega = Carbon::now(new DateTimeZone('America/Bogota'))->format('H:i:s');
                $control->save();
                \Illuminate\Support\Facades\Log::channel('custom')->info('Registra el control {control}', ['control' => $control->id, 'predios' => $prediosToAsign, 'persona' => $this->cedula]);
            } catch (\Exception $e) {
                $control->predios()->update(['control_id' => null]);
                $control->delete();
                return  session()->flash('warning', $e->getMessage());
            }
        }
        $terminal = $control->getATerminal();
        if ($terminal) {
            return redirect()->route('elecciones.registrar')->with('terminal', $terminal);
        } else {
            return session()->flash('warning', 'No hay terminales libres, espere a que se libere');
        }
    }
}
