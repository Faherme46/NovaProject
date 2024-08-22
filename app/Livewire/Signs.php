<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Component;

class Signs extends Component
{
    protected $listeners=['proof'=>'getProof'];
    #[Layout(('layout.presentation'))]
    public function render()
    {
        return view('views.registro.signs');
    }

    public function goBack(){
        return redirect(route('home'));
    }

    public function proof(){
        $this->dispatch('proof');
    }
    
    public function getProof(){
        return session()->flash('success1','Bienveniodo');
    }
}
