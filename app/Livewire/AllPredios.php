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

    public $iconButton='bi-plus-circle-fill';
    public function mount()
    {

        $url=url()->current();
        if ($url=='http://nova.local/consulta'){
            $this->iconButton='bi-question-circle-fill';
        }
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
        $this->dispatch('$refresh');
        $this->mount();
        // Actualiza la colección de predios para mostrar todos los disponibles

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

        return view('views.registro.all-predios');
    }


    public function dispatchPredio($id){
        $this->dispatch('add-predio', predioId: $id);
    }
    public function dispatchPersona($id){

        $this->dispatch('search-persona', personaId: $id);
    }
    public function dispatchPoderdante($id){

        $this->dispatch('add-poderdante', poderdanteId: $id,personaId:$id);
    }

    public function dispatchControl($id){
        $this->dispatch('set-control', controlId: $id);
    }

    public function showPersona($id){
        if(!$id){
            return;
        }
        $this->Persona=Persona::find($id);
        if($this->Persona){
            $this->dispatch('showModalPersona',personaId:$id);
        }else{

        }
    }

    public function showPredio($id){
        if(!$id){
            return;
        }
        $this->Predio=Predio::find($id);
        if($this->Predio){
            $this->dispatch('showModalPredio',predioId:$id);
        }else{
            $this->addError('error','No fue encontrado');
        }

    }
    #[On('find-control')]
    public function showControl($id){
        if(!$id){
            return;
        }
        $this->Control=Control::find($id);
        if($this->Control){
            $this->dispatch('showModalControl');
        }else{
            $this->addError('error','No fue encontrado');
        }
    }

    #[On('refresh-predios')]
    public function refreshPredios(){
        $this->dispatch('$refresh');
    }


}
