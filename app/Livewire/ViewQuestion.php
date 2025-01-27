<?php

namespace App\Livewire;

use App\Models\Question;
use Livewire\Attributes\Layout;
use Livewire\Component;

class ViewQuestion extends Component
{

    public $question;
    public $sizeOptions = 7;
    public $sizeHeads = 7;
    public $sizeTitle = 3.5;
    public $options = ['optionA', 'optionB', 'optionC', 'optionD', 'optionE', 'optionF'];
    public function mount($questionId){
        $this->question=Question::find($questionId);
        $this->setSizePresentation();
    }


    #[Layout('layout.presentation')]
    public function render()
    {
        return view('views.votacion.view-question');
    }


    public function setSizePresentation()
    {
        $lenTitle = strlen($this->question->title);
        if ($lenTitle < 25) {
            $this->sizeTitle = 4.7;
        } else if ($lenTitle < 35) {
            $this->sizeTitle = 3.3;
        } else if ($lenTitle < 80) {
            $this->sizeTitle = 2.5;
        } else if ($lenTitle < 140) {
            $this->sizeTitle = 2;
        } else if ($lenTitle < 180) {
            $this->sizeTitle = 1.5;
        } else {
            $this->sizeTitle = 1.3;
        }

        if ($this->question->type == 18) {
            $this->sizeOptions = 7;
        } else {

            $numOptions = 0;
            $lenOptions = [];
            foreach ($this->options as $value) {
                $numOptions += ($this->question[$value]) ? 1 : 0;
                $lenOptions[$value] = strlen($this->question[$value]);
            }
            $maxOptions = max($lenOptions);
            // dd($maxOptions);

            if ($maxOptions <= 17) {

                if ($numOptions <= 4) {
                    $this->sizeOptions = 7;
                    $this->sizeHeads = 7;
                } else {
                    $this->sizeOptions = 4.7;
                    $this->sizeHeads = 4.7;
                }
            } else if ($maxOptions <= 30) {
                $this->sizeOptions = 4.5;
                $this->sizeHeads = 5;
            } else if ($maxOptions <= 60) {
                $this->sizeOptions = 3;
                $this->sizeHeads = 7;
            } else {
                $this->sizeOptions = 2.3;
                $this->sizeHeads = 5;
            }
        }
    }
}
