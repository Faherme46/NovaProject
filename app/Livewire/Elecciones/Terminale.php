<?php

namespace App\Livewire\Elecciones;

use App\Models\Control;
use App\Models\Eleccion;
use App\Models\Persona;
use App\Models\Terminal;
use App\Models\Torre;
use Exception;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Terminale extends Component
{
    public $terminal;
    public $asistente;
    public $control;
    public $voting = false;
    public $torres = [];
    public $torre = null;
    public $candidatoId = 0;
    public $candidatos = [];
    public $inTratamiento = true;
    public $tratamientoDatos = 0;
    public $resumen = [];

    public function mount()
    {

        $user = Auth::user();

        $this->terminal = Terminal::where('user_id', $user->id)->first();

        if (!$this->terminal) {
            Auth::logout();
        }
        $this->verifyAsistente();
    }
    #[Layout('layout.presentation')]
    public function render()
    {

        if ($this->voting && !cache('asamblea', [])['h_cierre']) {
            return view('views.elecciones.voting-elecciones');
        } else {
            return view('views.elecciones.terminal');
        }
    }


    public function verifyAsistente()
    {
        if (!$this->terminal) {
            Auth::logout();
            return redirect(route('home'));
        }
        // session()->flash('voted','voted');
        $this->control = Control::where('terminal_id', $this->terminal->id)->where('state', 1)->orderBy('updated_at', 'desc')->first();

        if ($this->control) {
            $this->resumen = [];
            $this->asistente = $this->control->persona;
        }
    }

    public function votar()
    {
        $this->voting = true;

        $this->torre = Torre::where('name', $this->control->vote)->first();
        $this->reset('candidatoId', 'candidatos');

        foreach ($this->torre->candidatos as $candidato) {
            $this->candidatos[$candidato->id] = $candidato->fullName();
        }
    }


    public function storeVote()
    {

        $votosBlanco = $this->control->predios_abs;
        $coeficienteBlanco = $this->control->sum_coef_abs;
        if ($this->candidatoId == -1) {
            $votosBlanco += $this->control->predios_vote;
            $coeficienteBlanco += $this->control->sum_coef_can;
            $this->control->h_recibe = -1;
            $this->control->save();
        } elseif ($this->torre) {
            $candidato = Persona::find($this->candidatoId);
            if ($candidato) {

                $pivot = $candidato->torres()->wherePivot('torre_id', $this->torre->id)->first()->pivot;
                $candidato->torres()->updateExistingPivot($this->torre->id, [
                    'votos' => $pivot->votos + $this->control->predios_vote,
                    'coeficiente' => $pivot->coeficiente + $this->control->sum_coef_can
                ]);
                $this->control->h_recibe = $candidato->id;
                $this->control->save();
            }
        }

        $this->torre->update([
            'votosBlanco' => $this->torre->votosBlanco + $votosBlanco,
            'coeficienteBlanco' => $this->torre->coeficienteBlanco + $coeficienteBlanco
        ]);

        \Illuminate\Support\Facades\Log::channel('custom')->info(
            'Vota:',
            [
                'control' => $this->control->id,
                'asistente' => $this->control->persona->id,
                'candidato' => $this->candidatoId,
            ]
        );
        $this->control->update(['state' => 5]);
        $next = $this->asistente->controls()->whereIn('state', [2, 4])->first();

        // session()->flash('success', 'Voto almacenado con éxito para '.$this->control->vote);
        if ($next) {

            $next->update(['state' => 1, 'terminal_id' => $this->terminal->id]);
            $this->verifyAsistente();
            $this->control = $next;
            $this->votar();
        } else {
            $this->terminal->update(['available' => true]);
            $this->resumen['asistente'] = $this->asistente->fullName();
            $this->voting = false;
            try {
                foreach ($this->asistente->controls as $control) {
                    $this->resumen[$control->id] = [
                        'torre' => $control->vote,
                        'coeficiente' => $control->sum_coef_can,
                        'votos' => $control->predios_vote,
                        'predios' => $control->predios->count(),
                        'candidato' => ($control->h_recibe && $control->h_recibe == '-1') ? 'EN BLANCO' : Persona::find($control->h_recibe)->fullName(),
                    ];
                    # code...
                }
            } catch (Exception $e) {
                $x=$e->getMessage();
            }
            session()->flash('voted', 'Ha votado');
            $this->reset('candidatos', 'torres', 'torre', 'asistente', 'control', 'candidatoId', 'tratamientoDatos', 'inTratamiento');
            return;
        }
    }

    public function dropAlert()
    {
        session()->forget('warning');
        $this->dispatch('$refresh');
    }

    public function setTratamiento()
    {
        if ($this->tratamientoDatos == 1) {
            $this->asistente->controls()->update(['t_publico' => 1]);
        } else {
            $this->asistente->controls()->update(['t_publico' => 0]);
        }


        $this->inTratamiento = false;
    }
}
