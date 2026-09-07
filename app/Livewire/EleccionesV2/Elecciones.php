<?php

namespace App\Livewire\EleccionesV2;

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
        return view('views.elecciones-v2.elecciones');
    }



    public function setPanels()
    {
        // dd((bool) cache('asamblea', false),(bool) cache('asamblea')['h_cierre'], $this->role != 'Operario');
        $this->panels = [

            [
                "directives" => ' onclick=location.href="/"; ',
                'icon' => 'bi-caret-left',
                'title' => 'Volver',
                'body' => 'Regresar al menÃº principal para programar asambleas',
                'visible' => !(cache('asamblea', false)),
                'enabled' => !(cache('asamblea', false)),
            ],
            [
                "directives" => 'onclick=location.href="/elecciones-v2/programar";',
                'icon' => 'bi-sliders',
                'title' => 'Programar Eleccion',
                'body' => 'Importar los archivos en este dispositivo ',
                'visible' => ($this->role == 'Admin' || $this->role == 'Lider'),
                'enabled' => true,
            ],
            [
                "directives" => 'onclick=location.href="/elecciones-v2/candidatos";',
                'icon' => 'bi-person-video2',
                'title' => 'Crear Candidatos',
                'body' => 'Seleccionar los candidatos que participarÃ¡n en cada torre',
                'visible' => true,
                'enabled' => (cache('asamblea', false) && !cache('asamblea')['h_inicio']),
            ],
            [
                "directives" => 'onclick=location.href="/elecciones-v2/informe";',
                'icon' => 'bi-file-earmark-richtext',
                'title' => 'Informe de elecciÃ³n',
                'body' => 'GestiÃ³n y generaciÃ³n del informe',
                'visible' => ($this->role == 'Admin' || $this->role == 'Lider'),
                'enabled' => cache('asamblea', false),
            ],
            [
                "directives" => 'onclick=location.href="/elecciones-v2/registrar";',
                'icon' => 'bi-person-check',
                'title' => 'Registrar',
                'body' => 'Asignar termianes de votaciÃ³n a los sufragantes.',
                'visible' => true,
                'enabled' => (cache('asamblea', false) && cache('asamblea')['h_inicio']),

            ],
            [
                "directives" => 'onclick=location.href="/elecciones-v2/gestion";',
                'icon' => 'bi-ui-checks-grid',
                'title' => 'Area de Control',
                'body' => 'GestiÃ³n, control y estadisticas de las elecciones actuales',
                'visible' => true,
                'enabled' => ($this->role != 'Operario' && (cache('asamblea', false))),
            ],
            [
                "directives" => 'onclick=location.href="/elecciones-v2/resultados";',
                'icon' => 'bi-bar-chart-line',
                'title' => 'Resultados',
                'body' => 'Calcular y presentar los resultados de las elecciones',
                'visible' => true,
                'enabled' => cache('asamblea', false) && cache('asamblea')['h_cierre'] && $this->role != 'Operario',
            ],
            [
                "directives" => 'onclick=location.href="/elecciones-v2/terminales";',
                'icon' => 'bi-pc-display-horizontal',
                'title' => 'Terminales',
                'body' => 'Verificar los terminales conectados actualmente a la sesiÃ³n',
                'visible' => true,
                'enabled' => cache('asamblea', false)
            ],
            [
                "directives" => 'onclick=location.href="/setup";',
                'icon' => 'bi-palette',
                'title' => 'Configurar DiseÃ±o',
                'body' => 'Cambiar colores, crear preguntas predeterminadas',
                'visible' => ($this->role == 'Admin' || $this->role == 'Lider'),
                'enabled' => true,
            ],
            [
                "directives" => 'onclick=location.href="/consulta";',
                'icon' => 'bi-info-circle',
                'title' => 'Consulta',
                'body' => 'Obtener informacion de los predios y sus propietarios',
                'visible' => true,
                'enabled' => (cache('asamblea', false)),
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
                'title' => 'Cerrar SesiÃ³n de Usuario ',
                'body' => 'Salir de la sesiÃ³n actual del usuario',
                'visible' => true,
                'enabled' => true,
            ]


        ];
    }
}

