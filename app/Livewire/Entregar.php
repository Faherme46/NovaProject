<?php

namespace App\Livewire;

use Livewire\Component;

use Livewire\Attributes\Layout;

use App\Models\Control;

class Entregar extends Component
{
    public $controles;

    public function mount(){
        $this->controles=Control::all();
    }

    #[Layout('layout.full-page')]
    public function render()
    {
        return view('livewire.entregar');
    }

    public function entregar($controlId){
        $control=Control::find($controlId);
        $asignacion=$control->asignacion;
        $asignacion->estado=3;
        $asignacion->save();
    }
}
