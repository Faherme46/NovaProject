<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;

use App\Models\Control;
use App\Models\Question;


class Votacion extends Component
{
    public $isQuestion;
    public $questionId;
    public $questionTitle = '';

    public $questionOptions = [
        'A' => '',
        'B' => '',
        'C' => '',
        'D' => '',
        'E' => '',
        'F' => '',
    ];


    public $questionType = 0;
    public $questionCoefChart;
    //todo cambiar graph por chart
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

    public $mins = 2;
    public $secs = 0;
    public function mount()
    {

        $this->questionsPrefab = Question::where('prefab', true)->get();
        $this->getValues();
    }

    #[Layout('layout.full-page')]
    public function render()
    {
        $this->getValues();
        return view('views.votacion.votacion');
    }


    public function getValues()
    {
        $this->prediosRegistered = Control::whereIn('state', [1, 2])
            ->withCount('predios')
            ->get()
            ->sum('predios_count');

        $this->prediosVote = Control::whereIn('state', [1, 2])->sum('predios_vote');
        $this->controlsRegistered = Control::whereIn('state', [1, 2])->count();
        $this->controlsVote = Control::where('sum_coef_can', '!=', 0)->count();
        $this->quorumVote = Control::whereNotIn('state', [3, 4])->sum('sum_coef_can');
        $this->quorumRegistered = Control::whereNotIn('state', [3, 4])->sum('sum_coef');
    }

    public function setQuestion($questionId)
    {
        $this->resetErrorBag();
        $this->reset('questionWhite');
        $selectedQuestion = Question::find($questionId);
        $this->isQuestion = true;
        $this->questionTitle = $selectedQuestion->title;
        $this->questionId = $selectedQuestion->id;
        $this->questionType = $selectedQuestion->type;
        $this->questionCoefChart = ($selectedQuestion->coefGraph) ? '1' : '0';
        $this->questionOptions = [
            'A' => $selectedQuestion->optionA,
            'B' => $selectedQuestion->optionB,
            'C' => $selectedQuestion->optionC,
            'D' => $selectedQuestion->optionD,
            'E' => $selectedQuestion->optionE,
            'F' => $selectedQuestion->optionF,
        ];
        $this->dispatch('$refresh');
        $this->dispatch('setInputs');
    }

    public function updatedquestionWhite($value)
    {
        if ($value) {
            if (in_array($this->questionType, [2, 6, 7])) {
                $id = 'optionF';
            } elseif ($this->questionType == 3) {
                $id = 'optionE';
            } elseif ($this->questionType == 4) {
                $id = 'optionB';
            }
            $this->dispatch('setWhite', myId: $id);
        } else {
            if ($this->questionType == 2 || $this->questionType == 6) {
                $id = 'optionF';
            } elseif ($this->questionType == 3) {
                $id = 'optionE';
            } elseif ($this->questionType == 4) {
                $id = 'optionB';
            }
            $this->dispatch('setNone', myId: $id);
        }
    }

    public function updatedQuestionType($value)
    {
        $this->resetErrorBag();
        $this->reset(['questionOptions', 'questionWhite']);

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
        $this->dispatch('setInputs');
    }


    public function increment($min)
    {
        if ($min) {
            if ($this->mins < 59) {
                $this->mins++;
                if ($this->mins == 60) {
                    $this->mins--;
                }
            }
        } else {
            if ($this->secs < 59) {
                $this->secs += 10;
                if ($this->secs == 60) {
                    $this->secs--;
                }
            }
        }
    }

    public function decrement($min)
    {
        if ($min) {
            if ($this->mins > 0) {
                $this->mins--;
            }
        } else {
            if ($this->secs > 0) {
                $this->secs -= 10;
                if ($this->secs == 49) {
                    $this->secs++;
                }
            }
        }
    }

    public function proof()
    {
        session()->flash('success', 'Have a winner');
    }

    public function disableWhite()
    {
        $this->questionWhite = false;
    }

    public function isOneOptionAlmost($questionOptions){
        foreach ($this->questionOptions as $key => $value) {
            if ($value) {
                return true;
            }
        }
        return false;
    }
    public function createQuestion()
    {
        $this->resetErrorBag();
        //Se requiere un titulo a la pregunta'
        $error=0;
        if(!$this->questionTitle){
            $this->addError('questionTitle','Se requiere el titulo de la pregunta');
            $error=1;
        }
        //Se requiere un tipo de la pregunta'
        if(!$this->questionType){
            $this->addError('questionTitle','Se requiere el tipo de la pregunta');
            $error=1;
        }
        if(!$this->questionCoefChart){
            $this->addError('questionTitle','Debe definir si la pregunta es por Coeficiente o nominal');
            $error=1;
        }

        if(!$this->isOneOptionAlmost($this->questionOptions)){

            $this->addError('questionTitle','Al menos uno de los campos debe tener un valor.');
            $error=1;
        }
        $quorum=Control::where('state', 1)->sum('sum_coef_can');
        if($quorum<=0){
            $this->addError('questionTitle','No se han registrado asistentes');
            $error=1;
        }
        $seconds= $this->secs+($this->mins*60);
        if($seconds<=0){
            $this->addError('questionTitle','El tiempo debe ser mayor a 0');
            $error=1;
        }

        if ($error) {
            return;
        }

        //'No se han registrado controles'
        try {
            $question = Question::create([
                'title' => strtoupper($this->questionTitle),
                'optionA' => strtoupper($this->questionOptions['A']),
                'optionB' => strtoupper($this->questionOptions['B']),
                'optionC' => strtoupper($this->questionOptions['C']),
                'optionD' => strtoupper($this->questionOptions['D']),
                'optionE' => strtoupper($this->questionOptions['E']),
                'optionF' => strtoupper($this->questionOptions['F']),
                'prefab' => (false),
                'isValid' => ($this->questionType==6)?0:1,
                'coefGraph' => (bool)$this->questionCoefChart,
                'quorum' => $quorum,
                'predios' => Control::where('state', 1)->sum('predios_vote'),
                'seconds' =>$seconds,
                'type' => $this->questionType
            ]);

            return redirect()->route('questions.show')->with('question_id',$question->id);
        } catch (\Throwable $th) {
            return $this->addError('questionCreate',$th->getMessage());
        }
    }
}
