<?php

namespace App\Livewire;

use App\Http\Controllers\FileController;
use App\Models\Persona;
use App\Models\Predio;
use Livewire\Attributes\Layout;
use Livewire\Component;


class Main extends Component
{
    public $predios;
    public $personas;
    public $folders;


    public $desc=true;

    public $panels=[

        [
            "directives"=> 'data-bs-toggle=modal data-bs-target=#modalCreateAsamblea @disabled($asambleaOn)',
            'icon'=> 'bi-sliders',
            'title'=> 'Programar',
            'body'=> 'Configurar y programar una asamblea',
            'onlyAdmin'=>true,
            'nonOperario'=>true,
            'onlyRegistro'=>0
        ],[
            "directives"=> 'onclick=location.href="/gestion/informes";',
            'icon'=> 'bi-file-earmark-richtext',
            'title'=> 'Informes',
            'body'=> 'Gestión y generación del informe',
            'onlyAdmin'=>true,
            'nonOperario'=>true,
            'onlyRegistro'=>0
        ],[
            "directives"=> 'data-bs-toggle=modal data-bs-target=#modalDeleteSession @disabled(!$asambleaOn)',
            'icon'=> 'bi-palette',
            'title'=> 'Configurar Diseño',
            'body'=> 'Colores, tamaños y fuentes',
            'onlyAdmin'=>true,
            'nonOperario'=>true,
            'onlyRegistro'=>0
        ],
        [
            "directives"=> 'onclick=location.href="/users";',
            'icon'=> 'bi-people',
            'title'=> 'Usuarios',
            'body'=> 'Crear y Consultar Usuarios',
            'onlyAdmin'=>false,
            'nonOperario'=>true,
            'onlyRegistro'=>0
        ],[
            "directives"=> 'data-bs-toggle=modal data-bs-target=#modalFilePredios @disabled(!$asambleaOn)',
            'icon'=> 'bi-file-arrow-up',
            'title'=> 'Archivos',
            'body'=> 'Archivos cargados de predios y personas',
            'onlyAdmin'=>false,
            'nonOperario'=>true,
            'onlyRegistro'=>0
        ],
        [
            "directives"=> 'onclick=location.href="/gestion/asamblea";',
            'icon'=> 'bi-ui-checks-grid',
            'title'=> 'Asamblea',
            'body'=> 'Control y estadisticas de asamblea',
            'onlyAdmin'=>false,
            'nonOperario'=>true,
            'onlyRegistro'=>0
        ],[
            "directives"=> 'onclick=location.href="/votacion";',
            'icon'=> 'bi-question-circle',
            'title'=> 'Votacion',
            'body'=> 'Crear y Presentar Votaciones',
            'onlyAdmin'=>false,
            'nonOperario'=>false,
            'onlyRegistro'=>0
        ],
        [
            "directives"=> 'onclick=location.href="/asistencia/registrar";',
            'icon'=> 'bi-person-check',
            'title'=> 'Registrar',
            'body'=> 'Asignar predios a personas',
            'onlyAdmin'=>false,
            'nonOperario'=>false,
            'onlyRegistro'=>1
        ],
        [
            "directives"=> 'onclick=location.href="/asistencia/asignar";',
            'icon'=> 'bi-building-check',
            'title'=> 'Asignar',
            'body'=> 'Asignar controles a predios',
            'onlyAdmin'=>false,
            'nonOperario'=>true,
            'onlyRegistro'=>2
        ],
        [
            "directives"=> 'onclick=location.href="/consulta";',
            'icon'=> 'bi-info-circle',
            'title'=> 'Consulta',
            'body'=> 'Controles, predios y personas',
            'onlyAdmin'=>false,
            'nonOperario'=>false,
            'onlyRegistro'=>0
        ],
        [
            "directives"=> 'onclick=location.href="/entregar";',
            'icon'=> 'bi-door-closed',
            'title'=> 'Entregar',
            'body'=> 'Recibir Controles',
            'onlyAdmin'=>false,
            'nonOperario'=>false,
            'onlyRegistro'=>1
        ],
    ];


    public function mount()
    {
        $fileController = new  FileController;
        $this->folders = $fileController->getFolders();

        $this->predios = Predio::all();
        $this->personas = Persona::all();

    }
    #[Layout('layout.full-page')]
    public function render()
    {
        return view('views.main');
    }

    public function orderPersonasByCc(){

        if ($this->desc) {
            $this->personas=$this->personas->sortByDesc('id');
        }else{
            $this->personas=$this->personas->sortBy('id');
        }
        $this->desc = !$this->desc;
        $this->dispatch('showModalFilePersonas');
    }
    public function orderPersonasByName(){
        if ($this->desc) {
            $this->personas=$this->personas->sortByDesc('name');
        }else{
            $this->personas=$this->personas->sortBy('name');
        }
        $this->desc = !$this->desc;
        $this->dispatch('showModalFilePersonas');
    }

}
