<?php

namespace App\Livewire;

use App\Http\Controllers\FileController;
use Livewire\Component;
use Livewire\Attributes\Layout;

use App\Models\Control;
use App\Models\Asamblea;
use App\Models\Predio;
use App\Models\Question;
use Carbon\Carbon;
use DateTimeZone;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class LiderSetup extends Component
{
    public $allControls;
    public $prediosRegistered;

    public $prediosVote;
    public $quorumRegistered;
    public $quorumVote;

    public $asamblea;
    public $started = false;
    public $finished = false;

    public $byControlAsc = false;
    public $byCoefAsc = false;

    #[Layout('layout.full-page')]
    public function render()
    {

        return view('views.gestion.lider-setup');
    }

    public function mount()
    {
        $this->allControls = Control::whereNotIn('state', [4])->get();

        $this->prediosRegistered = $this->allControls->sum(function ($control) {
            return $control->predios->count();
        });
        $this->prediosVote = $this->allControls->sum(function ($control) {
            return $control->predios_vote;
        });

        $this->quorumRegistered = $this->allControls->sum(function ($control) {
            return $control->sum_coef;
        });
        $this->quorumVote = $this->allControls->sum(function ($control) {
            return $control->sum_coef_can;
        });
        $this->asamblea = Asamblea::find(cache('id_asamblea'));

        $this->started = ($this->asamblea->h_inicio);
        $this->finished = ($this->asamblea->h_cierre);
    }


    public function iniciar()
    {
        if ($this->allControls->isEmpty()) {
            return $this->addError('error', 'No se han registrado controles');
        }
        try {

            $time = Carbon::now(new DateTimeZone('America/Bogota'))->second(0);
            if (!$this->asamblea->h_inicio) {

                $this->started = true;
                cache(['predios_init' =>  Predio::whereHas('control')->count()]);
                cache(['quorum_init' => Control::whereNotIn('state', [4])->sum('sum_coef')]);


                Predio::whereHas('control')->update(['quorum_start' => true]);
                $this->asamblea->h_inicio = $time;
                $this->asamblea->save();
                cache(['asamblea' => $this->asamblea]);
                \Illuminate\Support\Facades\Log::channel('custom')->notice('Se Inicia la asamblea');
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
        $logpath=storage_path('logs/myLog.log');

        if(File::exists($logpath)){
            Storage::disk('externalAsambleas')->put($this->asamblea->name . '/logs.log', file_get_contents($logpath));

        }
            $time = Carbon::now(new DateTimeZone('America/Bogota'))->second(0);
            if (!$this->asamblea->h_cierre) {

                Predio::whereHas('control', function ($query) use ($time) {
                    $query->where('state', 1);
                })->update(['quorum_end' => true]);
                Control::whereHas('predios')->update(['h_recibe' => $time->format('H:i')]);
                cache(['predios_end' =>  Predio::where('quorum_end', true)->count()]);
                cache(['quorum_end' => Control::whereNotIn('state', [4,5])->sum('sum_coef')]);


                $fileController = new FileController;
                if(!$fileController->exportTables()){
                    $this->addError('Error','Problema exportando las tablas de excel');
                };

                $this->asamblea->h_cierre = $time;
                $this->asamblea->save();
                cache(['asamblea' => $this->asamblea]);
                $this->finished = true;
                \Illuminate\Support\Facades\Log::channel('custom')->notice('Se termina la asamblea');
                session()->flash('info', 'Se ha terminado la asamblea en: ' . $time);
            } else {
                session()->flash('warning', 'Ya se establecio el cierre en: ' . $this->asamblea->h_cierre);
            }
        } catch (\Exception $e) {
            //throw $th;
            $this->addError('error', 'Error terminando la asamblea: ' . $e->getTraceAsString());
        }
    }

    public function desterminar()
    {
        try {

            $this->asamblea = Asamblea::find(cache('id_asamblea'));
            cache(['predios_end' => -1]);
            $this->asamblea->h_cierre = null;
            $this->asamblea->save();
            $this->finished = false;
            session()->flash('info', 'Se volvio a abrir la asamblea');
            \Illuminate\Support\Facades\Log::channel('custom')->notice('Se destermina la asamblea');
            return back();
        } catch (\Exception $e) {
            //throw $th;
            $this->addError('error', 'Error: ' . $e->getMessage());
        }
    }
}
