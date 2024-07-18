<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;

class Votacion extends Component
{

    public $questionTag='';

    public $prediosRegistered=20;
    public $prediosVote=19;
    public $controlsRegistered=20;
    public $controlsVote=19;

    public $quorumVote=1.000010;
    public $quorumRegistered=1.2345;


    #[Layout('layout.full-page')]
    public function render()
    {
        return view('livewire.votacion');
    }
}
