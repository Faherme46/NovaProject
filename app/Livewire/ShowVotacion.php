<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Models\Question;

class ShowVotacion extends Component
{
    public $questions;
    public $question;
    public $sizeTitle = 2.5;
    public $inCoefResult =true;
    public $asambleaName;
    public function mount()
    {
        $this->asambleaName=cache('asamblea')['name'];
        $this->questions = Question::all();
        $this->selectQuestion($this->questions->first()->id);
        $this->inCoefResult=$this->question->coefGraph;
    }
    #[Layout('layout.full-page')]
    public function render()
    {

        return view('views.votacion.show-votacion');
    }
    public function selectQuestion($questionId)
    {
        $this->question = $this->questions->find($questionId);
        $lenTitle = strlen($this->question->title);
        if ($lenTitle < 25) {
            $this->sizeTitle = 2.5;
        } else if ($lenTitle < 35) {
            $this->sizeTitle = 2.3;
        } else if ($lenTitle < 100) {
            $this->sizeTitle = 2;
        } else {
            $this->sizeTitle = 1.7;
        }
    }
}
