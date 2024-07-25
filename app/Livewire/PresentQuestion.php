<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;

use App\Models\Control;
use App\Models\Result;

use App\Http\Controllers\FileController;
use App\Models\Question;
use Illuminate\Support\Facades\Storage;

class PresentQuestion extends Component
{
    public $question;
    public $countdown;
    public $seconds;
    public $chartCoef;
    public $chartNom;
    public $inResults;

    public $stopped = false;
    public $colors = [
        1 => 'btn-black',       //sin asignacion
        2 => 'btn-secondary',      //sin voto
        3 => 'btn-success',       //votado
    ];
    public $controls;

    public $inVoting = 2;
    public $inCoefResult = true;


    public function mount()
    {
        $this->reset('inVoting', 'seconds', 'countdown');
        $this->question = Question::find(session('question_id'));
        if(!$this->question){
            return redirect()->route('home')->with('error','Nada para mostrar');
        }
        $this->controls = Control::all();
        $this->inCoefResult=!$this->question->nominalPriority;
    }
    #[Layout('layout.presentation')]
    public function render()
    {
        if ($this->inVoting == 1) {
            return view('livewire.votacion.voting');
        } elseif ($this->inVoting == 2) {
            return view('livewire.votacion.present-question');
        } elseif ($this->inVoting == 3) {
            return view('livewire.votacion.results');
        }
    }

    public function voting()
    {
        $this->seconds = $this->question->seconds;
        $this->updateCountdown();
        $this->inVoting = 1;
        $this->dispatch('$refresh');
        $this->dispatch('start-timer');
    }

    public function inResults()
    {

        $this->inVoting = 3;
        $this->dispatch('$refresh');
        foreach ($this->question->results as $result) {
            $this->setImageUrl(($result));
        }
    }

    public function setImageUrl($result)
    {
        try {
            $path = $this->setChart($result);

            $urlImg = Storage::disk('results')->url('images/results/'.$path);



            // dd($urlImg);
            if ($result->isCoef) {
                $this->chartCoef = $urlImg;
                $result->chartPath = $path;
            } else {
                $this->chartNom = $urlImg;
                $result->chartPath = $path;
            }
            $result->save();
        } catch (\Throwable $th) {
            dd('error1', $th->getMessage());
        }
    }


    public function setChart($result)
    {
        // Array para almacenar los datos del gráfico
        $labels = [];
        $values = [];

        // Agregar las opciones A-F si tienen valor
        $options = ['optionA', 'optionB', 'optionC', 'optionD', 'optionE', 'optionF'];
        foreach ($options as $option) {
            if ($this->question->$option !== null) {
                $labels[] = $this->question->$option;
                $values[] = $result->$option;
            }
        }

        // Agregar abstained, absent y nule con sus etiquetas en español
        $additionalOptions = [
            'abstainted' => 'Abstencion',
            'absent' => 'Ausente',
            'nule' => 'Nulo'
        ];

        foreach ($additionalOptions as $key => $label) {
            $labels[] = $label;
            $values[] = $result->$key;
        }


        $fileController = new FileController;
        $imageName = ($result->isCoef) ? 'coefChart' : 'nominalChart';
        $chart = $fileController->createChart($this->question->id, $this->question->title, $labels, $values, $imageName);

        return $chart;
    }

    public function decrement()
    {
        if ($this->seconds > 0) {
            $this->seconds -= 1;
            $this->updateCountdown();
        } else {
            $this->stopVote();
        }
    }

    public function updateCountdown()
    {
        $minutes = floor($this->seconds / 60);
        $seconds = $this->seconds % 60;
        $this->countdown = sprintf('%02d:%02d', $minutes, $seconds);
    }


    public function playPause($value = '')
    {

        if (!$value) {
            $this->dispatch('start-timer');
        } else {
            $this->dispatch('pause-timer');
        }
        $this->stopped = (bool) $value;
    }

    public function store()
    {
        $this->dispatch('closeModal');
        $this->playPause(true);
        $this->inResults();
    }

    public function stopVote()
    {
        $this->stopped = true;
        $this->dispatch('pause-timer');
        $this->playPause(true);

        $this->dispatch('modal-show');
        $this->dispatch('$refresh');
    }

    public function sleep($seconds)
    {
        sleep($seconds);
    }
    public function oneMoreMinut()
    {
        $this->playPause(false);
        $this->dispatch('modal-close');
        $this->seconds = 60;
        $this->updateCountdown();
    }

    public function createResults()
    {

        Result::create([
            'question_id' => $this->question->id,
            'optionA' => 10,
            'optionB' => 11,
            'optionC' => 12,
            'optionD' => 13,
            'optionE' => 1,
            'optionF' => 14,
            'abstainted' => 2,
            'absent' => 0,
            'nule' => 1,
            'isCoef' => false
        ]);
        Result::create([
            'question_id' => $this->question->id,
            'optionA' => 2.73,
            'optionB' => 1.41,
            'optionC' => 1.26,
            'optionD' => 1.36,
            'optionE' => 1.98,
            'optionF' => 1.84,
            'abstainted' => 2.1,
            'absent' => 0,
            'nule' => 1.12,
            'isCoef' => true
        ]);
    }
}
