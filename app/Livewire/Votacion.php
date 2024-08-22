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
    public $coefGraph=1;
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
        $this->prediosRegistered = Control::whereIn('state', [1,2])
            ->withCount('predios')
            ->get()
            ->sum('predios_count');
        $this->prediosVote = Control::whereIn('state', [1,2])->sum('predios_vote');
        $this->controlsRegistered = Control::whereIn('state', [1,2])->count();
        $this->controlsVote = Control::where('sum_coef_can', '!=', 0)->count();
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
        $this->coefGraph= ($selectedQuestion->coefGraph==='1');
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
                $id='optionF';
            } elseif ($this->questionType == 3) {
                $id='optionE';
            } elseif ($this->questionType == 4) {
                $id='optionB';
            }
            $this->dispatch('setWhite',myId:$id);
        } else {
            if ($this->questionType == 2 || $this->questionType == 6) {
                $id='optionF';
            } elseif ($this->questionType == 3) {
                $id='optionE';
            } elseif ($this->questionType == 4) {
                $id='optionB';
            }
            $this->dispatch('setNone',myId:$id);
        }

    }

    public function updatedQuestionType($value)
    {
        $this->reset(['questionOptions', 'questionWhite']);
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
         $this->dispatch('setInputs');
    }


    public function increment($min)
    {
        if ($min) {
            if ($this->mins < 59) {
                $this->mins++;
                if($this->mins==60){
                    $this->mins--;
                }

            }
        } else {
            if ($this->secs < 59) {
                $this->secs+=10;
                if($this->secs==60){
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
                $this->secs-=10;
                if ($this->secs==49) {
                    $this->secs++;
                }
            }
        }
    }

    public function proof(){
        session()->flash('success','Have a winner');
    }

    public function disableWhite(){
        $this->questionWhite = false;
    }

}
