<?php

namespace App\Livewire;

use App\Models\Control;
use Livewire\Component;

class QuorumState extends Component
{
    public $quorum;


    public function mount() {}
    public function render()
    {
        $this->quorum = Control::where('state', 1)->sum('sum_coef');
        return view('views.components.quorum-state');
    }

    public function ver()
    {
        $this->dispatch('$refresh');
    }


    public function proof()
    {
        $command = escapeshellcmd('python C:/xampp/htdocs/nova/scripts/create_plot.py "{ title : Proposicion , labels :[ SI , NO , Abstenci\u00f3n , Ausente , Nulo ], values :[10,12,2,0,1], output_path : C:\/Asambleas\/Asambleas\\Miradores_2024.07.25\\Pregunta_8\/nominalChart.png }"
');
        // Ejecutar el comando y capturar la salida y errores
        $output = shell_exec($command . ' 2>&1');
        dd($command);
    }
}
