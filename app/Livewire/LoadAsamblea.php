<?php

namespace App\Livewire;

use App\Http\Controllers\AsambleaController;
use App\Models\Asamblea;
use Livewire\Component;

use Livewire\Attributes\Layout;
class LoadAsamblea extends Component
{
    public $asambleas;
    public $asambleaName;

    public function mount(){
        $this->asambleas=Asamblea::all();
    }
    #[Layout('layout.full-page')]
    public function render()
    {
        return view('views.gestion.load-asamblea');
    }

    public function setNameAsamblea($value){

        $this->asambleaName=$value;
        $this->dispatch('loadModalShow');
    }
    public function deleteAsamblea($value){

        $this->asambleaName=$value;
        $this->dispatch('deleteModalShow');
    }

    public function loadAsambleas(){

    }
}
