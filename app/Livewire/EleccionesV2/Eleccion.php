<?php

namespace App\Livewire\EleccionesV2;

use App\Models\Control;
use App\Models\Persona;
use App\Models\Question;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class Eleccion extends Component
{
    public $questionTitle = '';
    public $search = '';
    public $candidatos = [];
    public $activeQuestion;

    public function mount()
    {
        $this->activeQuestion = Question::where('isEleccion', true)->first();
    }

    #[Layout('layout.full-page')]
    public function render()
    {
        $personas = Persona::query();

        if (strlen($this->search) >= 3) {
            $personas->where(function ($query) {
                $query->where('id', 'like', '%' . $this->search . '%')
                    ->orWhere('nombre', 'like', '%' . $this->search . '%')
                    ->orWhere('apellido', 'like', '%' . $this->search . '%');
            });
        }

        return view('views.elecciones-v2.eleccion', [
            'candidatosSeleccionados' => Persona::with(['predios.control', 'prediosEnPoder.control'])
                ->whereIn('id', $this->candidatos)
                ->get(),
            'personas' => $personas->orderBy('nombre')->paginate(10),
        ]);
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }
    public function agregarCandidato($personaId)
    {
        if ($this->activeQuestion) {
            return session()->flash('warning', 'La votacion ya fue iniciada');
        }

        if (!in_array($personaId, $this->candidatos)) {
            $this->candidatos[] = $personaId;
        }
    }

    public function quitarCandidato($personaId)
    {
        if ($this->activeQuestion) {
            return session()->flash('warning', 'La votacion ya fue iniciada');
        }

        $this->candidatos = array_values(array_diff($this->candidatos, [$personaId]));
    }

    public function iniciarVotacion()
    {
        if ($this->activeQuestion || cache('voting')) {
            return session()->flash('warning', 'Ya hay una votacion en curso');
        }

        if (!$this->questionTitle) {
            return $this->addError('questionTitle', 'El titulo de la votacion es requerido');
        }

        if (count($this->candidatos) <= 0) {
            return $this->addError('candidatos', 'Debe agregar al menos un candidato');
        }

        $controlesRegistrados = Control::whereNot('state', 4)->get();
        if ($controlesRegistrados->isEmpty()) {
            return $this->addError('candidatos', 'No se han registrado asistentes');
        }

        $question = Question::create([
            'title' => strtoupper($this->questionTitle),
            'type' => 2,
            'isValid' => true,
            'isEleccion' => true,
            'coefGraph' => true,
            'quorum' => $controlesRegistrados->sum('sum_coef'),
            'predios' => $controlesRegistrados->sum('predios_total'),
            'seconds' => 120,
        ]);

        foreach ($this->candidatos as $personaId) {
            DB::table('questions_candidato')->insert([
                'question_id' => $question->id,
                'persona_id' => $personaId,
                'votos' => 0,
                'coeficiente' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        cache(['voting' => true], now()->addMinutes(30));
        $this->activeQuestion = $question;

        return redirect()->route('questions.show', ['questionId' => $question->id]);
    }
}