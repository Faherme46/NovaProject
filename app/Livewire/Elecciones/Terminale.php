<?php

namespace App\Livewire\Elecciones;

use App\Models\Control;
use App\Models\Eleccion;
use App\Models\Persona;
use App\Models\Terminal;
use App\Models\Torre;
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

    public function mount()
    {
        $user = Auth::user();
        $this->terminal = Terminal::where('user_id', $user->id)->first();
        $this->verifyAsistente();
    }
    #[Layout('layout.presentation')]
    public function render()
    {

        if ($this->voting && !cache('asamblea',[])['h_cierre']) {
            return view('views.elecciones.voting-elecciones');
        } else {
            return view('views.elecciones.terminal');
        }
    }


    public function verifyAsistente()
    {
        $this->control = Control::where('terminal_id', $this->terminal->id)->where('state', 1)->orderBy('updated_at', 'desc')->first();

        if ($this->control) {
            $this->asistente = $this->control->persona;
        }
    }

    public function votar()
    {
        $this->voting = true;
        foreach ($this->control->predios as $predio) {
            $this->torres[] = $predio->numeral1;
        }
        $this->torre = Torre::where('name', $this->torres[0])->first();
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
        } elseif ($this->torre) {
            $candidato = Persona::find($this->candidatoId);
            if ($candidato) {
                $pivot = $candidato->torres()->wherePivot('torre_id', $this->torre->id)->first()->pivot;
                $candidato->torres()->updateExistingPivot($this->torre->id, [
                    'votos' => $pivot->votos + $this->control->predios_vote,
                    'coeficiente' => $pivot->coeficiente + $this->control->sum_coef_can
                ]);
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
                'candidato' => $this->candidatoId,
                'asistente' => $this->control->persona->id
            ]
        );
        $this->control->update(['state' => 5]);
        $next = $this->asistente->controls()->whereIn('state', [2, 4])->first();

        session()->flash('success', 'Voto almacenado con éxito');
        if ($next) {
            $next->update(['state' => 1, 'terminal_id' => $this->terminal->id]);
            $this->verifyAsistente();
            $this->voting = false;
            return redirect()->route('terminal')->with('success', 'Puede votar para la siguiente torre');
        } else {
            $this->terminal->update(['available' => true]);
            return redirect()->route('terminal')->with('success', 'Ha finalizado el proceso de votacion');
        }
    }
}
