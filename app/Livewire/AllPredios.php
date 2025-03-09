<?php

namespace App\Livewire;

use Livewire\Attributes\On;

use Livewire\Component;
use App\Models\Predio;
use App\Models\Control;

class AllPredios extends Component
{
    public $distincts;
    public $prediosAll = [];
    public $search=[
        'descriptor1' => '',
        'numeral1' => '',
        'descriptor2' => '',
        'numeral2' => '',
    ];


    public $consulta;
    public function mount()
    {

        $url = url()->current();
        if (strpos($url,'consulta')) {
            $this->consulta = true;
        }
        $this->distincts = [
            'descriptor1' => Predio::distinct()->pluck('descriptor1')->toArray(),
            'numeral1' => Predio::distinct()->pluck('numeral1')->toArray(),
            'descriptor2' => Predio::distinct()->pluck('descriptor2')->toArray(),
        ];

        $this->search = [
            'descriptor1' => $this->distincts['descriptor1'][0],
            'numeral1' => $this->distincts['numeral1'][0],
            'descriptor2' => $this->distincts['descriptor2'][0],
        ];
        $this->searchPredios();
    }

    public function clean()
    {
        // Restablece las variables de búsqueda
        $this->reset();

        $this->mount();
        $this->dispatch('$refresh');
        $this->render();
        // Actualiza la colección de predios para mostrar todos los disponibles

    }
    public function render()
    {
        // Inicializa la consulta base


        return view('views.registro.all-predios');
    }


    public function searchPredios()
    {   
        
        // Ejecuta la consulta y obtiene los resultados
        $this->prediosAll = Predio::where('descriptor1',$this->search['descriptor1'])
                            ->where('numeral1',$this->search['numeral1'])
                            ->where('descriptor2',$this->search['descriptor2'])->get()->toArray();
        //      dd('1');
    }

    public function searchWithNumeral2(){
        if(!array_key_exists('numeral2',$this->search)){
            return;
        }
        foreach ($this->prediosAll as $predio) {
            if(strstr($predio['numeral2'],$this->search['numeral2'])){
                $prediosAux[]=$predio;
            }
        }
        if(!$this->prediosAll){
            return;
        }
        $this->prediosAll = $prediosAux;
        unset($this->search['numeral2']);
    }
    public function updatedSearch($value){
        $this->searchPredios();
    }
    public function dispatchPredio($predio)
    {

        $this->dispatch('add-predio', predio: $predio);
        
    }
    public function dispatchPersona($id)
    {

        $this->dispatch('search-persona', personaId: $id);
    }
    public function dispatchPoderdante($id)
    {

        $this->dispatch('add-poderdante', poderdanteId: $id, personaId: $id);
    }

    public function dispatchControl($id)
    {
        $this->dispatch('set-control', controlId: $id);
    }

    // public function showPersona($id){
    //     if(!$id){
    //         return;
    //     }
    //     $this->Persona=Persona::find($id);
    //     if($this->Persona){
    //         $this->dispatch('showModalPersona',personaId:$id);
    //     }else{

    //     }
    // }

    // public function showPredio($id){
    //     if(!$id){
    //         return;
    //     }
    //     $this->Predio=Predio::find($id);
    //     if($this->Predio){
    //         $this->dispatch('showModalPredio',predioId:$id);
    //     }else{
    //         $this->addError('error','No fue encontrado');
    //     }

    // }
    // #[On('find-control')]
    // public function showControl($id)
    // {
    //     if (!$id) {
    //         return;
    //     }
    //     $this->Control = Control::find($id);
    //     if ($this->Control) {
    //         $this->dispatch('showModalControl');
    //     } else {
    //         $this->addError('error', 'No fue encontrado');
    //     }
    // }

    #[On('refresh-predios')]
    public function refreshPredios()
    {
        $this->dispatch('$refresh');
    }

    public function Proof()
    {
        dd($this->prediosAll);
    }
}
