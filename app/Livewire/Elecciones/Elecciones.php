<?php

namespace App\Livewire\Elecciones;

use App\Http\Controllers\FileController;
use App\Models\Persona;
use App\Models\Predio;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Elecciones extends Component
{


    public $predios;
    public $personas;
    public $folders;

    public $role;
    public $desc = true;

    public $panels;


    public function mount()
    {
        $this->role = Auth::user()->getRoleNames()[0];

        $this->setPanels();
    }

    #[Layout('layout.full-page')]
    public function render()
    {
        return view('views.elecciones.elecciones');
    }



    public function setPanels()
    {
        $this->panels = [

            [
                "directives" => ' onclick=location.href="/"; ',
                'icon' => 'bi-caret-left',
                'title' => 'Volver',
                'body' => 'Regresar al menú principal para programar asambleas',
                'visible' => !(cache('asamblea', false)),
                'enabled' => !(cache('asamblea', false)),
            ],
            [
                "directives" => 'onclick=location.href="/elecciones/programar";',
                'icon' => 'bi-sliders',
                'title' => 'Programar Eleccion',
                'body' => 'Importar los archivos en este dispositivo ',
                'visible' => ($this->role == 'Admin' || $this->role == 'Lider'),
                'enabled' => true,
            ],
            [
                "directives" => 'onclick=location.href="/elecciones/candidatos";',
                'icon' => 'bi-person-video2',
                'title' => 'Crear Candidatos',
                'body' => 'Seleccionar los candidatos que participarán en cada torre',
                'visible' => true,
                'enabled' => true,
            ],
            [
                "directives" => 'onclick=location.href="/gestion/informes";',
                'icon' => 'bi-file-earmark-richtext',
                'title' => 'Informe de elección',
                'body' => 'Gestión y generación del informe',
                'visible' => ($this->role == 'Admin' || $this->role == 'Lider'),
                'enabled' => cache('asamblea', false),
            ],
            [
                "directives" => 'onclick=location.href="/elecciones/registrar";',
                'icon' => 'bi-person-check',
                'title' => 'Registrar',
                'body' => 'Asignar termianes de votación a los sufragantes.',
                'visible' => true,
                'enabled' => (cache('asamblea', false) && cache('asamblea')['h_inicio']),

            ],
            [
                "directives" => 'onclick=location.href="/elecciones/gestion";',
                'icon' => 'bi-ui-checks-grid',
                'title' => 'Area de Control',
                'body' => 'Gestión, control y estadisticas de las elecciones actuales',
                'visible' => true,
                'enabled' => ($this->role != 'Operario' && (cache('asamblea', false))),
            ],
            [
                "directives" => 'onclick=location.href="/elecciones/resultados";',
                'icon' => 'bi-bar-chart-line',
                'title' => 'Resultados',
                'body' => 'Calcular y presentar los resultados de las elecciones',
                'visible' => true,
                'enabled' => cache('asamblea', false) && cache('asamblea')['h_cierre'],
            ],
            [
                "directives" => 'onclick=location.href="/users";',
                'icon' => 'bi-people',
                'title' => 'Usuarios',
                'body' => 'Crear, Importar y Consultar Usuarios y terminales',
                'visible' => ($this->role == 'Admin' || $this->role == 'Lider'),
                'enabled' => ($this->role != 'Operario'),
            ],
            [
                "directives" => 'data-bs-toggle=modal data-bs-target=#logOutModal',
                'icon' => 'bi-box-arrow-left',
                'title' => 'Cerrar Sesión de Usuario ',
                'body' => 'Salir de la sesión actual del usuario',
                'visible' => true,
                'enabled' => true,
            ]


        ];
    }
}
