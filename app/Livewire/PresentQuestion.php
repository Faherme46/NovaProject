<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;

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
    public $controlsAssigned = [];

    public $inVoting = 2;
    public $inCoefResult = true;

    public $votes = [];
    public function mount()
    {
        $this->reset('inVoting', 'seconds', 'countdown', 'votes');
        $this->question = Question::find(session('question_id'));
        if (!$this->question) {
            return redirect()->route('home')->with('error', 'Nada para mostrar');
        }
        $this->controls = Control::all()->pluck('id')->toArray();
        $this->inCoefResult = !$this->question->nominalPriority;
        $this->setControlsAssigned();

        // $this->chartNom=Storage::disk('results')->url('images/results/10/nominalChart.png');

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
        $this->dispatch('start-timer');
        $this->inVoting = 1;
    }

    public function inResults()
    {

        $this->inVoting = 3;
        foreach ($this->question->results as $result) {
            $this->setImageUrl(($result));
        }
    }

    public function setImageUrl($result)
    {
        try {
            $path = $this->setChart($result);

            $urlImg = Storage::disk('results')->url('images/results/' . $path);



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
        $this->createResults();
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

        $valuesCoef = [
            'A'    => 0,
            'B'    => 0,
            'C'    => 0,
            'D'    => 0,
            'E'    => 0,
            'F'    => 0,
            'nule'  => 0
        ];
        $valuesNom = [
            'A'    => 0,
            'B'    => 0,
            'C'    => 0,
            'D'    => 0,
            'E'    => 0,
            'F'    => 0,
            'nule'  => 0
        ];
        $availableOptions = $this->question->getAvailableOptions();

        foreach ($this->votes as $id => $vote) {
            $control = $this->controlsAssigned[$id];
            if (in_array($vote, $availableOptions)) {
                $valuesCoef[$vote] += $control->sum_coef_can;
                $valuesNom[$vote] += $control->getPrediosCan();
            } else {
                $valuesCoef['nule'] += $control->sum_coef_can;
                $valuesNom['nule'] += $control->getPrediosCan();
            }
        }

        $absentNom = Control::whereIn('state', [2, 5])->sum('predios_vote');
        $absentCoef = Control::whereIn('state', [2, 5])->sum('sum_coef_can');

        $abstainted = array_diff(array_keys($this->controlsAssigned), array_keys($this->votes));
        $abstaintedCoef = 0;
        $abstaintedNom=0;
        foreach ($abstainted as $control) {

            $abstaintedNom  +=  $this->controlsAssigned[$control]['predios_vote'];
            $abstaintedCoef +=  $this->controlsAssigned[$control]['sum_coef_can'];
        }

        $resultNom = Result::create([
            'question_id' => $this->question->id,
            'optionA' => $valuesNom['A'],
            'optionB' => $valuesNom['B'],
            'optionC' => $valuesNom['C'],
            'optionD' => $valuesNom['D'],
            'optionE' => $valuesNom['E'],
            'optionF' => $valuesNom['F'],
            'abstainted' => count($abstainted),
            'absent' => $absentNom,
            'nule' =>  $valuesNom['nule'],
            'isCoef' => false
        ]);
        $resultCoef = Result::create([
            'question_id' => $this->question->id,
            'optionA' => $valuesCoef['A'],
            'optionB' => $valuesCoef['B'],
            'optionC' => $valuesCoef['C'],
            'optionD' => $valuesCoef['D'],
            'optionE' => $valuesCoef['E'],
            'optionF' => $valuesCoef['F'],
            'abstainted' => $abstaintedCoef,
            'absent' => $absentCoef,
            'nule'  =>   $valuesCoef['nule'],
            'isCoef' => true
        ]);
        $this->inResults();
    }


    public function goBack()
    {
        $this->reset();
        return redirect()->route('votacion');
    }

    public function goPresent()
    {
        $this->mount();
        $this->dispatch('$refresh');
    }

    public $auxId = 1;



    public function proofGenerateResults()
    {
        $aux = [
            1 => 'A',
            2 => 'B',
            4 => 'C',
            8 => 'D',
            16 => 'E',
            32 => 'F',
        ];
        $options = [1, 2, 4, 8, 16, 32];
        $auxId = $this->auxId;

        if (array_key_exists($auxId, $this->controlsAssigned)) {
            $control = $this->controlsAssigned[$auxId];
            if (!$control->isAbsent()) {
                $this->votes[$control->id] = $aux[$options[array_rand($options)]];
            }
        }
        $this->auxId = array_rand($this->controlsAssigned);
    }

    

    public function setControlsAssigned()
    {
        $controls = Control::whereHas('predios')->get();
        foreach ($controls as $control) {
            $this->controlsAssigned[$control->id] = $control;
        }
    }


    public function proof1(){
        dd($this->votes);
    }

}
