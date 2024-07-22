<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;

use App\Models\Control;
use App\Models\Question;


class Votacion extends Component
{

    public $questionTag = '';
    public $questionId;
    public $questionOptions = [
        'A' => '',
        'B' => '',
        'C' => '',
        'D' => '',
        'E' => '',
        'F' => '',
    ];
    public $questionType = 0;
    public $questionNominal;
    public $questionWhite = false;

    #prefab questions
    public $questionsPrefab;
    #values
    public $prediosRegistered = 0;
    public $prediosVote = 0;
    public $controlsRegistered = 0;
    public $controlsVote = 0;
    public $quorumVote = 0;
    public $quorumRegistered = 0;

    public function mount()
    {
        $this->questionsPrefab = Question::where('prefab', true)->get();
        $this->getValues();
    }

    #[Layout('layout.full-page')]
    public function render()
    {
        $this->getValues();
        return view('livewire.votacion');
    }


    public function getValues()
    {
        $this->prediosRegistered = Control::where('state', 1)
            ->withCount('predios')
            ->get()
            ->sum('predios_count');
        $this->prediosVote = Control::where('state', 1)
            ->withCount(['predios as predios_votan' => function ($query) {
                $query->where('vota', true);
            }])
            ->get()
            ->sum('predios_votan');
        $this->controlsRegistered = Control::where('state', 1)->count();
        $this->controlsVote = Control::where('sum_coef_can', '!=', 0)
            ->whereNotIn('state', [3, 4])
            ->count();

        $this->quorumVote = Control::whereNotIn('state', [3, 4])->sum('sum_coef_can');
        $this->quorumRegistered = Control::whereNotIn('state', [3, 4])->sum('sum_coef');
    }

    public function setQuestion($questionId)
    {
        $this->reset('questionWhite');
        $selectedQuestion = Question::find($questionId);
        $this->questionTag = $selectedQuestion->title;
        $this->questionId = $selectedQuestion->id;
        $this->questionType = $selectedQuestion->type;
        $this->questionNominal = $selectedQuestion->nominalPriotiry;


        $this->questionOptions = [
            'A' => $selectedQuestion->optionA,
            'B' => $selectedQuestion->optionB,
            'C' => $selectedQuestion->optionC,
            'D' => $selectedQuestion->optionD,
            'E' => $selectedQuestion->optionE,
            'F' => $selectedQuestion->optionF,
        ];
        $this->dispatch('$refresh');
    }

    public function updatedquestionWhite($value)
    {
        if ($value) {
            if ($this->questionType == 2 || $this->questionType == 6) {
                $this->questionOptions['F'] = 'En blanco';
            } elseif ($this->questionType == 3) {
                $this->questionOptions['E'] = 'En blanco';
            } elseif ($this->questionType == 4) {
                $this->questionOptions['B'] = 'En blanco';
            }
        } else {
            if ($this->questionType == 2 || $this->questionType == 6) {
                $this->questionOptions['F'] = '';
            } elseif ($this->questionType == 3) {
                $this->questionOptions['E'] = '';
            } elseif ($this->questionType == 4) {
                $this->questionOptions['B'] = '';
            }
        }
    }

    public function updatedQuestionType($value)
    {
        $this->reset(['questionOptions','questionWhite']);
        $this->dispatch('resetInputs');
        if ($this->questionId == 12) {
            switch ($value) {
                case 1:

                    $this->questionOptions = [
                        'A' => '',
                        'B' => '',
                        'C' => '',
                        'D' => '',
                        'E' => '',
                        'F' => '',
                    ];
                    break;
                case 2:
                    $this->questionOptions = [
                        'A' => '',
                        'B' => '',
                        'C' => '',
                        'D' => '',
                        'E' => '',
                        'F' => '',
                    ];
                    break;
                case 3:

                    $this->questionOptions = [
                        'A' => '',
                        'B' => '',
                        'C' => '',
                        'D' => 'Aprobado',
                        'E' => '',
                        'F' => 'No Aprobado',
                    ];
                    break;
                case 4:
                    $this->questionOptions = [
                        'A' => 'Si ',
                        'B' => '',
                        'C' => 'No',
                        'D' => '',
                        'E' => '',
                        'F' => '',
                    ];
                    break;
                default:
                    $this->questionOptions = [
                        'A' => '',
                        'B' => '',
                        'C' => '',
                        'D' => '',
                        'E' => '',
                        'F' => '',
                    ];
                    break;
            }
        }
        $this->dispatch('$refresh');

    }

    
}
