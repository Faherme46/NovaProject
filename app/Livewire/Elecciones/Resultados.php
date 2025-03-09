<?php

namespace App\Livewire\Elecciones;

use App\Models\Torre;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Resultados extends Component
{

    public $torres;
    public $torre;
    public function mount(){
        $this->torres=Torre::all();
        $this->torre=$this->torres->first();

    }
    #[Layout('layout.full-page')]
    public function render()
    {
        return view('views.elecciones.resultados');
    }

    public function setTorre($id){
        $this->torre=Torre::find($id);
    }
}
