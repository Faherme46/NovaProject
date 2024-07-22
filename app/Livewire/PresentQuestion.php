<?php

namespace App\Livewire;

use Livewire\Component;

class PresentQuestion extends Component
{
    public $question;

    public function mount($question){
        $this->question=$question;
    }

    public function render()
    {
        return view('livewire.present-question');
    }

    public function vote(){
        return redirect()->route('votacion');
    }
}
