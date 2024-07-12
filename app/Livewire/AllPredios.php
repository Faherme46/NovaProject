<?php

namespace App\Livewire;
use Livewire\Attributes\On;

use Livewire\Component;
use App\Models\Predio;
use App\Models\Persona;
use App\Models\Control;

class AllPredios extends Component
{
    public $distincts;
    public $searchId = '';
    public $prediosAll;

    public $descriptor1 = '';
    public $descriptor2 = '';
    public $numeral1 = '';
    public $numeral2 = '';

    public $Predio;
    public $Persona;

    public $Control;


    public function mount()
    {

        $this->distincts = [
            'descriptor1' => Predio::distinct()->pluck('descriptor1'),
            'numeral1' => Predio::distinct()->pluck('numeral1'),
            'descriptor2' => Predio::distinct()->pluck('descriptor2'),
            'numeral2' => Predio::distinct()->pluck('numeral2'),
        ];
        $this->prediosAll = Predio::all();
    }

    public function clean()
    {
        // Restablece las variables de búsqueda
        $this->reset(['descriptor1','descriptor2','numeral2','numeral1','searchId']);

        // Actualiza la colección de predios para mostrar todos los disponibles
        $this->prediosAll = Predio::all();
    }
    public function render()
    {
        // Inicializa la consulta base
        $query = Predio::query();

        // Aplica los filtros condicionalmente
        if ($this->searchId) {
            $query->where('cc_propietario', 'like', '%' . $this->searchId . '%');
        }
        if ($this->descriptor1) {
            $query->where('descriptor1', 'like', '%' . $this->descriptor1 . '%');
        }
        if ($this->descriptor2) {
            $query->where('descriptor2', 'like', '%' . $this->descriptor2 . '%');
        }
        if ($this->numeral1) {
            $query->where('numeral1', 'like', '%' . $this->numeral1 . '%');
        }
        if ($this->numeral2) {
            $query->where('numeral2', 'like', '%' . $this->numeral2 . '%');
        }

        // Ejecuta la consulta y obtiene los resultados
        $this->prediosAll = $query->get();

        return view('livewire.all-predios');
    }


    public function dispatchPredio($id){

        $this->dispatch('add-predio', predioId: $id);
    }
    public function dispatchPersona($id){

        $this->dispatch('search-persona', personaId: $id);
    }
    public function dispatchPoderdante($id){

        $this->dispatch('add-poderdante', poderdanteId: $id);
    }

    public function dispatchControl($id){

        $this->dispatch('set-control', controlId: $id);
    }
    #[On('find-persona')]
    public function showPersona($id){
        $this->Persona=Persona::find($id);
        $this->dispatch('showModalPersona');
    }

    public function showPredio($id){
        $this->Predio=Predio::find($id);
        $this->dispatch('showModalPredio');
    }
    #[On('find-control')]
    public function showControl($id){
        $this->Control=Control::find($id);
        $this->dispatch('showModalControl');
    }

}
