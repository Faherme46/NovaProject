<?php

namespace App\Livewire\Elecciones;

use App\Http\Controllers\FileController;
use App\Models\Persona;
use App\Models\Predio;
use App\Models\Torre;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Programar extends Component
{
    public $folders;
    public $predios;
    public $personas;
    public $inAsamblea;
    public $inPredios = false;
    public $desc;
    public $torres;
    public $delegados=[];
    public $delegadosAll = 0;

    public function mount()
    {
        $fileController = new  FileController;
        $this->folders = $fileController->getFolders();
        $this->inAsamblea = (bool) cache('asamblea', false);
        $this->predios = Predio::all();
        $this->personas = Persona::all();
        $this->torres = Predio::distinct()->pluck('numeral1');
        $torres=Torre::all();
        if($torres->isNotEmpty()){
            foreach ($torres as $t) {
                $this->delegados[$t->name]=$t->delegados;
            }
        }

    }
    #[Layout('layout.full-page')]
    public function render()
    {

        return view('views.elecciones.programar');
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


    public function setDelegados()
    {

        foreach ($this->torres as $value) {
            $this->delegados[$value] = $this->delegadosAll;
        };
    }
}
