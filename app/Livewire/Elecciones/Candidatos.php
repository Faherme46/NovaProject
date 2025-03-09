<?php

namespace App\Livewire\Elecciones;

use Illuminate\Support\Str;
use App\Models\Persona;
use App\Models\Torre;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Candidatos extends Component
{
    public $candidatosPrevios = [];

    public $candidatosTorre;
    public $torres;
    public $personas;
    public $torre;
    public $candidato;
    public $candidatoToDelete = [
        'tipoId' => '',
        'id' => '',
        'nombre' => '',
        'apellido' => '',
    ];
    public $candidatos = [];
    public $attributesCandidato = [
        'tipoId' => '',
        'id' => '',
        'name' => '',
        'lastName' => '',
    ];
    public $cedula;


    #[Layout('layout.full-page')]


    public function mount()
    {
        if(cache('asamblea')&& ((cache('asamblea')['h_inicio'] || cache('asamblea')['h_cierre']))){
            return redirect()->route('home.elecciones')->with('warning', 'Las elecciones ya se han abierto');
        }

        $this->torres = Torre::all();
        if ($this->torres->isEmpty()) {
            return redirect()->route('elecciones.programar')->with('error', 'No se ha determinado el número de delegados para cada torre');
        }
        $this->torre = $this->torres->first();

        $this->setPersonasTorre();
    }

    public function render()
    {
        return view('views.elecciones.candidatos');
    }

    public function cleanData($cedula)
    {
        $this->reset([
            'candidato',
            'attributesCandidato',
        ]);
        if ($cedula) {
            $this->reset(['cedula']);
            return redirect()->route('elecciones.candidatos');
        }
    }

    public function setPersonasTorre($id = 0)
    {
        $this->reset('candidatosTorre', 'candidatosPrevios');
        if ($id) {
            $this->torre = Torre::find($id);
        }
        $prediosTorre = $this->torre->predios;

        $propietarios = $prediosTorre->pluck('personas')->flatten()->unique('id');
        $apoderados = $prediosTorre->pluck('apoderado')->flatten()->unique('id');
        if ($apoderados->first()) {
            $this->personas = $propietarios->merge($apoderados)->unique('id');
        } else {

            $this->personas = $propietarios;
        }
        $this->resetCandidatosPrevios();
    }

    public function search($cedula = null)
    {
        $this->reset([
            'candidato',
            'attributesCandidato',

        ]);

        $this->candidato = Persona::find($cedula ? $cedula : $this->cedula);

        if ($this->candidato) {
            $this->cedula = $this->candidato->id;
            $this->attributesCandidato = [
                'tipoId' => $this->candidato->tipo_id,
                'id' => $this->candidato->id,
                'name' => $this->candidato->nombre,
                'lastName' => $this->candidato->apellido
            ];
        } else {
            $this->attributesCandidato['id'] = $this->cedula;
            $this->dispatch('showModal');
        }
        $this->dispatch('$refresh');
    }

    public function creaPersona()
    {
        try {
            $this->candidato = Persona::create([
                'tipo_id' => $this->attributesCandidato['tipoId'],
                'id' => $this->attributesCandidato['id'],
                'nombre' => strtoupper($this->attributesCandidato['name']),
                'apellido' => strtoupper($this->attributesCandidato['lastName'])
            ]);

            $this->dispatch('hideModal');
            session()->flash('success', 'Asistente creado exitosamente');
        } catch (\Exception $e) {
            return back()->withCookie('error' . $e->getMessage());
        }
    }


    public function addCandidato($cedula = null)
    {
        if ($cedula) {
            $this->candidatos[$cedula] = Persona::find($cedula);
        } else {
            $this->candidatos[$this->candidato->id] = $this->candidato;
        }
    }

    public function storeCandidatos()
    {
        $this->torre->candidatos()->attach(array_keys($this->candidatos));
        return redirect()->route('elecciones.candidatos')->with('success', 'Candidatos guardados con exito');
    }

    public function dropAllCandidatos()
    {
        $this->reset('candidatos');
    }

    public function dropCandidato($cc)
    {
        unset($this->candidatos[$cc]);
    }

    public function deleteCandidato($candidato)
    {
        $this->candidatoToDelete = $candidato;
        $this->dispatch('showDeleteModal');
    }

    public function detachCandidato($cc)
    {
        $this->torre->candidatos()->detach($cc);
        $this->resetCandidatosPrevios();
        $this->dispatch('$refresh');
        $this->dispatch('hideDeleteModal');
        $this->reset('candidatoToDelete');
        return redirect()->route('elecciones.candidatos')->with('success', 'Canditato eliminado correctamente');
    }

    public function resetCandidatosPrevios()
    {
        foreach ($this->torre->candidatos as $cand) {
            $this->candidatosPrevios[$cand->id] = $cand;
        }
    }
}
