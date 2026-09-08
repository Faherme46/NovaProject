<?php

namespace App\Livewire\EleccionesV2;

use App\Models\Persona;
use App\Models\Question;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Elecciones extends Component
{
    public $title = '';
    public $search = '';
    public $searchResults = [];
    public $candidateIds = [];
    public $activeQuestion;
    public $inRegistro = false;

    public function mount()
    {
        $this->inRegistro = (bool) cache('inRegistro', false);
        $this->activeQuestion = Question::where('isEleccion', true)->first();

        if ($this->activeQuestion) {
            $this->title = $this->activeQuestion->title;
            $this->candidateIds = DB::table('questions_candidato')
                ->where('question_id', $this->activeQuestion->id)
                ->pluck('persona_id')
                ->toArray();
        }
    }

    #[Layout('layout.full-page')]
    public function render()
    {
        return view('views.elecciones-v2.elecciones', [
            'selectedCandidates' => $this->selectedCandidates(),
        ]);
    }

    public function searchPersonas()
    {
        if ($this->activeQuestion) {
            return session()->flash('warning', 'La votacion ya fue iniciada');
        }

        $term = trim($this->search);
        if (!$term) {
            $this->searchResults = [];
            return;
        }

        $this->searchResults = Persona::where('id', 'like', '%' . $term . '%')
            ->orWhere('nombre', 'like', '%' . $term . '%')
            ->orWhere('apellido', 'like', '%' . $term . '%')
            ->orderBy('nombre')
            ->limit(20)
            ->get();
    }

    public function addCandidato($personaId)
    {
        if ($this->activeQuestion) {
            return session()->flash('warning', 'La votacion ya fue iniciada');
        }

        if (!in_array($personaId, $this->candidateIds)) {
            $this->candidateIds[] = $personaId;
        }
    }

    public function dropCandidato($personaId)
    {
        if ($this->activeQuestion) {
            return session()->flash('warning', 'La votacion ya fue iniciada');
        }

        $this->candidateIds = array_values(array_diff($this->candidateIds, [$personaId]));
    }

    public function iniciar()
    {
        if ($this->activeQuestion) {
            return session()->flash('warning', 'Ya hay una votacion de eleccion iniciada');
        }

        $this->validate([
            'title' => 'required|string',
            'candidateIds' => 'required|array|min:1',
        ], [
            'title.required' => 'El titulo de la votacion es requerido',
            'candidateIds.min' => 'Debe seleccionar al menos un candidato',
        ]);

        $question = DB::transaction(function () {
            $question = Question::create([
                'title' => strtoupper($this->title),
                'type' => 2,
                'isValid' => true,
                'isEleccion' => true,
                'coefGraph' => true,
            ]);

            foreach ($this->candidateIds as $personaId) {
                DB::table('questions_candidato')->insert([
                    'question_id' => $question->id,
                    'persona_id' => $personaId,
                    'votos' => 0,
                    'coeficiente' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            return $question;
        });

        $this->activeQuestion = $question;
        session()->flash('success', 'Votacion de eleccion iniciada');

        return redirect()->route('questions.show', ['questionId' => $question->id]);
    }

    private function selectedCandidates()
    {
        if (!$this->candidateIds) {
            return collect();
        }

        return Persona::with(['predios.control', 'prediosEnPoder.control'])
            ->whereIn('id', $this->candidateIds)
            ->orderBy('nombre')
            ->get();
    }
}