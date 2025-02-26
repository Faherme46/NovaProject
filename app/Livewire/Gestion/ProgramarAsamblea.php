<?php

namespace App\Livewire\Gestion;

use App\Http\Controllers\FileController;
use App\Models\Persona;
use App\Models\Predio;
use Livewire\Attributes\Layout;
use Livewire\Component;

class ProgramarAsamblea extends Component
{
    public $folders;
    public $predios;
    public $personas;
    public $inAsamblea;
    public $inPredios = false;
    public $desc;

    public function mount()
    {
        $fileController = new  FileController;
        $this->folders = $fileController->getFolders();
        $this->inAsamblea = (bool) cache('asamblea', false);
        $this->predios = Predio::all();
        $this->personas = Persona::all();


    }
    #[Layout('layout.full-page')]
    public function render()
    {

        return view('views.gestion.programar-asamblea');
    }

    public function setInPredios()
    {
        $this->inPredios = !$this->inPredios;
    }

    public function orderPersonasByCc()
    {

        if ($this->desc) {
            $this->personas = Persona::query()->orderBy('id', 'desc')->get();
        } else {
            $this->personas = Persona::query()->orderBy('id', 'asc')->get();
        }
        $this->desc = !$this->desc;
    }
    public function orderPersonasByName()
    {

        if ($this->desc) {
            $this->personas = Persona::query()->orderBy('nombre', 'desc')->get();
        } else {
            $this->personas = Persona::query()->orderBy('nombre', 'asc')->get();
        }

        $this->desc = !$this->desc;
    }



}
