<?php

namespace App\Livewire\Elecciones;

use App\Http\Controllers\FileController;
use App\Models\Asamblea;
use App\Models\Control;
use App\Models\Eleccion;
use App\Models\Torre;
use Carbon\Carbon;
use DateTimeZone;
use Livewire\Attributes\Layout;
use Livewire\Component;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class Manager extends Component
{

    public $started = false;
    public $asamblea;
    public $finished = false;
    public $controles;
    public function mount()
    {
        $this->asamblea = Asamblea::find(cache('asamblea')['id_asamblea']);
        $this->started = (bool) ($this->asamblea->h_inicio);
        $this->finished =   (bool) $this->asamblea->h_cierre;
        $this->controles = Control::all();
    }

    #[Layout('layout.full-page')]
    public function render()
    {
        return view('views.elecciones.manager');
    }


    public function iniciar()
    {

        $torres = Torre::all();
        $error = null;
        if($torres->isEmpty()){
            return $this->addError('error','No se han creado las torres y sus delegados');
        }
        foreach ($torres as $torre) {
            if ($torre->candidatos->count() <=0) {
                $this->addError('error', 'El número de candidatos no puede ser 0 para ' . $torre->name );
                $error = true;
            }
        }
        if ($error) {
            return;
        }
        try {
            $time = Carbon::now(new DateTimeZone('America/Bogota'))->format('H:i:s');
            if (!$this->asamblea->h_inicio) {
                $this->started = true;
                $this->asamblea->h_inicio = $time;
                $this->asamblea->save();
                cache(['asamblea' => $this->asamblea]);
                \Illuminate\Support\Facades\Log::channel('custom')->notice('Se Inician las elecciones', ['HORA' => $time]);
                session()->flash('info', 'Se ha iniciado la asamblea en: ' . $time);
            } else {
                session()->flash('warning', 'Ya se establecio el inicio en: ' . $this->asamblea->h_inicio);
            }
        } catch (\Exception $e) {
            $this->addError('error', $e->getMessage());
        }
    }

    public function terminar()
    {

        try {
            $logpath = storage_path('logs/myLog.log');

            if (File::exists($logpath)) {
                Storage::disk('externalAsambleas')->put($this->asamblea->name . '/logs.log', file_get_contents($logpath));
            }
            $time = Carbon::now(new DateTimeZone('America/Bogota'))->format('H:i:s');
            if (!$this->asamblea->h_cierre) {

                // $fileController = new FileController;
                // if(!$fileController->exportTables()){
                //     $this->addError('Error','Problema exportando las tablas de excel');
                // };

                $this->asamblea->h_cierre = $time;
                $this->asamblea->save();
                cache(['asamblea' => $this->asamblea]);
                $this->finished = true;
                \Illuminate\Support\Facades\Log::channel('custom')->notice('Se terminan las elecciones', ['HORA' => $time]);
                session()->flash('info', 'Se ha terminado la asamblea en: ' . $time);
            } else {
                session()->flash('warning', 'Ya se establecio el cierre en: ' . $this->asamblea->h_cierre);
            }
        } catch (\Exception $e) {
            //throw $th;
            $this->addError('error', 'Error terminando la asamblea: ' . $e->getMessage());
        }
    }
    public function desiniciar()
    {
        try {

            $this->asamblea = Asamblea::find(cache('asamblea')['id_asamblea']);
            $this->asamblea->h_inicio = null;
            $this->asamblea->save();
            cache(['asamblea' => $this->asamblea]);
            $this->started = false;
            session()->flash('info', 'Se desinicio la asamblea');
            \Illuminate\Support\Facades\Log::channel('custom')->notice('Se desinicio la asamblea');
            return back();
        } catch (\Exception $e) {
            //throw $th;
            $this->addError('error', 'Error: ' . $e->getMessage());
        }
    }

    public function desterminar()
    {
        try {

            $this->asamblea = Asamblea::find(cache('asamblea')['id_asamblea']);
            cache(['predios_end' => -1]);
            $this->asamblea->h_cierre = null;
            $this->asamblea->save();
            $this->finished = false;
            cache(['asamblea' => $this->asamblea]);
            session()->flash('info', 'Se volvio a abrir la asamblea');
            \Illuminate\Support\Facades\Log::channel('custom')->notice('Se destermina la asamblea');
            return back();
        } catch (\Exception $e) {
            //throw $th;
            $this->addError('error', 'Error: ' . $e->getMessage());
        }
    }


    public function updateTerminal($id)
    {
        $control = Control::find($id);
        if ((bool)$control->getATerminal()) {


            return redirect()->route('elecciones.gestion')->with('terminal', $control->terminal->user_name);

        } else {
            session()->flash('warning', 'Registro exitoso, espere a que se libere un terminal');
        }
    }

    public function releaseTerminal($id)
    {
        $control = Control::find($id);
        $control->releaseTerminal();
        return redirect()->route('elecciones.gestion')->with('success','Terminal liberado');

    }
}
