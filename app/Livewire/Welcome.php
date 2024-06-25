<?php

namespace App\Livewire;

use Livewire\Component;

class Welcome extends Component
{
    public function render()
    {
        return <<<'HTML'
        <link rel="stylesheet" href="{{ asset('assets/scss/welcome.scss') }}">
        <div class="bg-image">
            <div class="centered-title">Tecnovis</div>
        </div>
        HTML;
    }
}
