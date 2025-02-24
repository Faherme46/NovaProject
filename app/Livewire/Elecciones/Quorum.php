<?php

namespace App\Livewire\Elecciones;

use App\Models\Control;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Quorum extends Component
{
    public $quorum;
    public $nominal;


    public function mount(){
        $this->quorum['total']=Control::all()->sum('sum_coef_can');
        $this->nominal['total']=Control::all()->sum('predios_total');
    }

    #[Layout('layout.full-page')]
    public function render()
    {
        return view('views.elecciones.quorum');
    }


}
