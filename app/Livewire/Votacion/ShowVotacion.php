<?php

namespace App\Livewire\Votacion;

use App\Imports\VotesImport;
use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Models\Question;
use App\Models\Vote;
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

    public function importarVotos(){


        $nameAsamblea=cache('asamblea')['name'];
        $externalFilePathVotes = Storage::disk('externalAsambleas')->path($nameAsamblea.'/Preguntas/'.$this->question->id.'/votos.xlsx');

        if (!file_exists($externalFilePathVotes)) {
            session()->flash('error',"El archivo no se encontró en la ruta: {$externalFilePathVotes}");
        }
        Vote::truncate();
        $import=Excel::import(new VotesImport, $externalFilePathVotes);
        return redirect()->route('questions.show',['questionId'=>$this->question->id])->with('info','Continue con la votación para generar los resultados');
    }
}
