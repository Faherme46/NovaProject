<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Component;

class Setup extends Component
{

    public $themes = [
        [
            'color' => 'Naranja',
            'rgb' => '#fd7e14'
        ],[
            'color' => 'Cian',
            'rgb' => '#0dcaf0'
        ],[
            'color' => 'Turquesa',
            'rgb' => '#20c997'
        ],[
            'color' => 'Morado',
            'rgb' => '#6f42c1'
        ],[
            'color' => 'Purpura',
            'rgb' => '#6610f2'
        ],
        [
            'color' => 'Azul',
            'rgb' => '#0d6efd'
        ],


    ];


    #[Layout('layout.full-page')]
    public function render()
    {
        return view(view: 'views.setup.setup');
    }
}
