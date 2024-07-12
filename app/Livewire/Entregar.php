<?php

namespace App\Livewire;

use Livewire\Component;

use Livewire\Attributes\Layout;

use App\Models\Control;

class Entregar extends Component
{
    public $controles;

    public $controlId;
    public $colors=[
        1=>'btn-primary',
        2=>'btn-info',
        3=>'btn-warning',
        4=>'btn-black'
    ];

    public function mount(){
        $this->controles=Control::all();
    }

    #[Layout('layout.full-page')]
    public function render()
    {
        return view('livewire.entregar');
    }

    public function change($value){

        $control=Control::find($this->controlId);
        $control->changeState($value);
        return redirect()->to('/entregar');
    }


    public function confirm($id){
        $this->controlId=$id;
        $this->dispatch('showModalConfirm');
    }

    public function close(){
        $this->reset('controlId');
    }
}
