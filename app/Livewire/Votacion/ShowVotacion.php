<?php

namespace App\Livewire\Votacion;

use App\Exports\StatesExport;
use App\Imports\VotesImport;
use App\Models\Control;
use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Models\Question;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

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
        if ($this->questions->isNotEmpty()) {
            $this->selectQuestion($this->questions->first()->id);
            $this->inCoefResult=$this->question->coefGraph;
        }


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
