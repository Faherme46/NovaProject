<?php

namespace App\Livewire;

use App\Models\Control;
use Livewire\Component;

class QuorumState extends Component
{
    public $quorum;


    public function mount(){
    }
    public function render()
    {
        $this->quorum=Control::where('state',1)->sum('sum_coef');
        return view('livewire.quorum-state');
    }

    public function ver(){
        dd( $this->quorum);
    }
}
