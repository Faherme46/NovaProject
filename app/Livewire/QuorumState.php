<?php

namespace App\Livewire;

use App\Models\Control;
use Livewire\Component;

class QuorumState extends Component
{
    public $quorum;


    public function mount() {
        $this->quorum = Control::where('state', 1)->sum('sum_coef');
    }
    public function render()
    {

        return view('components.quorum-state');
    }

    public function ver()
    {
        $this->quorum = Control::where('state', 1)->sum('sum_coef');
        $this->dispatch('$refresh');
    }

    public function toFullScreen(){
        return redirect()->route('quorum.show');
    }





}
