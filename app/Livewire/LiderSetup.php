<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;

use App\Models\Control;
use App\Models\Asamblea;

use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use DateTimeZone;


class LiderSetup extends Component
{
    public $allControls;
    public $prediosRegistered;

    public $prediosVote;
    public $quorumRegistered;
    public $quorumVote;


    public $started=false;
    public $finished=false;

    public $byControlAsc=false;
    public $byCoefAsc=false;

    #[Layout('layout.full-page')]
    public function render()
    {

        return view('livewire.lider-setup');
    }

    public function mount(){
        $this->allControls = Control::whereNotIn('state',[4,3])->get();

        $this->prediosRegistered=$this->allControls->sum(function ($control) {
            return $control->predios->count();
        });
        $this->prediosVote=$this->allControls->sum(function ($control) {
            return $control->predios_vote;
        });

        $this->quorumRegistered=$this->allControls->sum(function ($control) {
            return $control->sum_coef;
        });
        $this->quorumVote=$this->allControls->sum(function ($control) {
            return $control->sum_coef_can;
        });
    }


    public function iniciar()
    {
        try {
            $asamblea = Asamblea::find(Cache::get('id_asamblea'));
            $time = Carbon::now(new DateTimeZone('America/Bogota'));
            if ($asamblea->h_inicio == null) {
                $asamblea->h_inicio = $time;
                $asamblea->save();
                $this->started=true;
                session()->flash('info1', 'Se ha iniciado la asamblea en: ' . $time);
            } else {
                session()->flash('warning1', 'Ya se establecio el inicio en: ' . $asamblea->h_inicio);
            }
        } catch (\Exception $e) {
            session()->flash('error1', $e->getMessage());
        }
    }

    public function terminar()
    {

        try {
            $asamblea = Asamblea::find(Cache::get('id_asamblea'));
            $time = Carbon::now(new DateTimeZone('America/Bogota'));
            if ($asamblea->h_cierre == null) {
                $asamblea->h_cierre = $time;
                $asamblea->save();
                $this->finished=true;
                session()->flash('info1', 'Se ha terminado la asamblea en: ' . $time);
            } else {
                session()->flash('warning1', 'Ya se establecio el cierre en: ' . $asamblea->h_cierre);
            }
        } catch (\Exception $e) {
            //throw $th;
            session()->flash('error1', $e->getMessage());
        }
    }


}
