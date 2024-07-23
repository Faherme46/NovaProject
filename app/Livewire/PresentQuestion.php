<?php

namespace App\Livewire;

use Livewire\Component;

use App\Models\Control;
class PresentQuestion extends Component
{
    public $question;
    public $colors=[
        1=>'btn-black',       //sin asignacion
        2=>'btn-secondary',      //sin voto
        3=>'btn-success',       //votado
    ];
    public $controls;

    public $inVoting=false;
    public function mount($question){
        $this->question=$question;
        $this->controls=Control::all();
    }
    public function render()
    {
        if($this->inVoting){
            return view('livewire.votacion.voting');
        }else{
            return view('livewire.votacion.present-question');
        }

    }

    public function voting(){
        $this->inVoting=true;
        $this->dispatch('$refresh');
    }


}
