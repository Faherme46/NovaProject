<?php

namespace App\Livewire;

use App\Http\Controllers\FileController;
use App\Models\Persona;
use App\Models\Predio;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class Main extends Component
{
    public $predios;
    public $personas;
    public $folders;

    public $role;
    public $desc=true;

    public $panels;
    public $host;

    public function mount()
    {
        $fileController = new  FileController;
        $this->folders = $fileController->getFolders();

        $this->predios = Predio::all();
        $this->personas = Persona::all();
        $this->host=Storage::get('db_host.txt');
        
        $this->role=Auth::user()->getRoleNames()[0];

        $this->setPanels();
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

    public function setPanels(){
        $this->panels=[

            [
                "directives"=> 'data-bs-toggle=modal data-bs-target=#modalCreateAsamblea @disabled($asambleaOn)',
                'icon'=> 'bi-sliders',
                'title'=> 'Programar',
                'body'=> 'Importar los archivos en este dispositivo',
                'visible'=> ($this->role=='Admin' || $this->role=='Lider'),
                'enabled'=>!(cache('asamblea',false)),
            ],[
                "directives"=> 'onclick=location.href="/asambleas/load";',
                'icon'=> 'bi-upload',
                'title'=> 'Cargar Asamblea',
                'body'=> 'Cargar y eliminar las asambleas guardadas',
                'visible'=> ($this->role=='Admin' || $this->role=='Lider'),
                'enabled'=>!(cache('asamblea',false)),
            ],[
                "directives"=> 'data-bs-toggle=modal data-bs-target=#connectionModal @disabled($asambleaOn)',
                'icon'=> 'bi-router',
                'title'=> 'Conectarse a una sesión',
                'body'=> 'Ingrese la ip del host',
                'visible'=> true,
                'enabled'=>true,
            ],[
                "directives"=> 'onclick=location.href="/elecciones";',
                'icon'=> 'bi-person-badge',
                'title'=> 'Elecciones',
                'body'=> 'Representantes para asambleas',
                'visible'=> !(cache('asamblea',false)),
                // 'enabled'=>!(cache('asamblea',false)),
                'enabled'=>false
            ],[
                "directives"=> 'onclick=location.href="/gestion/informes";',
                'icon'=> 'bi-file-earmark-richtext',
                'title'=> 'Informes',
                'body'=> 'Gestión y generación del informe',
                'visible'=> ($this->role=='Admin' || $this->role=='Lider'),
                'enabled'=>cache('asamblea',false)  ,
            ],[
                "directives"=> 'onclick=location.href="/setup";',
                'icon'=> 'bi-palette',
                'title'=> 'Configurar Diseño',
                'body'=> 'Colores, tamaños y fuentes',
                'visible'=> ($this->role=='Admin' || $this->role=='Lider'),
                'enabled'=>true,
            ],
            [
                "directives"=> 'onclick=location.href="/users";',
                'icon'=> 'bi-people',
                'title'=> 'Usuarios',
                'body'=> 'Crear y Consultar Usuarios',
                'visible'=> ($this->role=='Admin' || $this->role=='Lider'),
                'enabled'=>($this->role!='Operario'),
            ],[
                "directives"=> 'data-bs-toggle=modal data-bs-target=#modalFilePredios @disabled(!$asambleaOn)',
                'icon'=> 'bi-file-arrow-up',
                'title'=> 'Archivos',
                'body'=> 'Archivos cargados de predios y personas',
                'visible'=> ($this->role=='Admin' || $this->role=='Lider'),
                'enabled'=>($this->role!='Operario'&&(cache('asamblea',false))),
            ],
            [
                "directives"=> 'onclick=location.href="/gestion/asamblea";',
                'icon'=> 'bi-ui-checks-grid',
                'title'=> 'Asamblea',
                'body'=> 'Control y estadisticas de asamblea',
                'visible'=> true,
                'enabled'=>($this->role!='Operario'&&(cache('asamblea',false))),
            ], [
                "directives"=> 'onclick=location.href="/asistencia/registrar";',
                'icon'=> 'bi-person-check',
                'title'=> 'Registrar',
                'body'=> 'Asignar predios, controles y asistentes',
                'visible'=> (cache('inRegistro',false)),
                'enabled'=>(cache('asamblea',false)),

            ],
            [
                "directives"=> 'onclick=location.href="/asistencia/asignacion";',
                'icon'=> 'bi-building-check',
                'title'=> 'Asignar',
                'body'=> 'Asignar predios a controles',
                'visible'=> (!cache('inRegistro',true)),
                'enabled'=>(cache('asamblea',false)),
            ],[
                "directives"=> 'onclick=location.href="/votacion";',
                'icon'=> 'bi-question-circle',
                'title'=> 'Votacion',
                'body'=> 'Crear y Presentar Votaciones',
                'visible'=> true,
                'enabled'=>($this->role!='Operario'&&(cache('asamblea',false))),
            ],[
                "directives"=> 'onclick=location.href="/votacion/show";',
                'icon'=> 'bi-patch-question',
                'title'=> 'Ver Votaciones',
                'body'=> 'Ver votaciones y resultados',
                'visible'=> true,
                'enabled'=>(cache('asamblea',false)),
            ],
            [
                "directives"=> 'onclick=location.href="/consulta";',
                'icon'=> 'bi-info-circle',
                'title'=> 'Consulta',
                'body'=> 'Controles, predios y personas',
                'visible'=> true,
                'enabled'=>(cache('asamblea',false)),
            ],
            [
                "directives"=> 'onclick=location.href="/entregar";',
                'icon'=> 'bi-door-closed',
                'title'=> 'Entregar',
                'body'=> 'Recibir Controles',
                'visible'=> true,
                'enabled'=>(cache('asamblea',false)),
            ],
            [
                "directives"=> 'onclick=location.href="/asistencia/firmas";',
                'icon'=> 'bi-pen',
                'title'=> 'Firmas',
                'body'=> 'Recibir Firmas electronicas',
                'visible'=> (cache('asamblea'))?cache('asamblea')['registro']:false,
                'enabled'=>(cache('asamblea'))?cache('asamblea')['signature']:false,
            ],[
                "directives"=> 'data-bs-toggle=modal data-bs-target=#logOutModal',
                'icon'=> 'bi-box-arrow-left',
                'title'=> 'Cerrar Sesión',
                'body'=> '',
                'visible'=> true,
                'enabled'=>true,
            ]


        ];
    }


    public function verifyDevice(){

    }


}
