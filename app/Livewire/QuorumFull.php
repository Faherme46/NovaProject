<?php

namespace App\Livewire;

use App\Models\Control;
use Livewire\Component;
use Livewire\Attributes\Layout;
class QuorumFull extends Component
{
    public $quorum;
    public function mount(){
        $this->quorum = Control::where('state', 1)->sum('sum_coef');
    }
    #[Layout('layout.presentation')]
    public function render()
    {
        return view('views.registro.quorum-full');
    }


}
