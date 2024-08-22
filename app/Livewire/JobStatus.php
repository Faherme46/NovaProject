<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Component;

class JobStatus extends Component
{
    public $jobStatus=[];
    #[Layout("layout.presentation")]
    public function render()
    {
        return view('views.gestion.job-status');
    }

    public function getJobStatus()
    {
        // Consultar el estado del trabajo desde la base de datos
        $this->jobStatus = cache('jobStatus',[]);
    }
}
