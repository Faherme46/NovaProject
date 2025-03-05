<?php

namespace App\Livewire\Elecciones;

use Livewire\Attributes\Layout;
use Livewire\Component;

class Informe extends Component
{

    public function mount(){
        
    }
    #[Layout("layout.full-page")]
    public function render()
    {
        return view('views.elecciones.informe');
    }
}
