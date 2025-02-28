<?php

namespace App\Livewire\Registro;

use App\Models\Control;
use Livewire\Component;
use Livewire\Attributes\Layout;
class QuorumFull extends Component
{
    public $quorum;
    public function mount(){
        if(cache('asamblea',false)&&cache('asamblea')['eleccion']){
            $this->quorum = Control::sum('sum_coef');
        }else{
            $this->quorum = Control::where('state', 1)->sum('sum_coef');
        }

    }
    #[Layout('layout.presentation')]
    public function render()
    {
        return view('views.registro.quorum-full');
    }


}
