<?php

namespace App\Livewire;

use App\Models\Asignacion;
use Livewire\Component;

class QuorumState extends Component
{
    public $quorum;


    public function mount(){
    }
    public function render()
    {
        $this->quorum=Asignacion::where('estado',1)->sum('sum_coef');
        return view('livewire.quorum-state');
    }

    public function ver(){

    }
}
