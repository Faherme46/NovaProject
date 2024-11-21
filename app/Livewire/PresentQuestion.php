<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;

use App\Models\Control;
use App\Models\Result;

use App\Http\Controllers\FileController;
use App\Models\General;
use App\Models\Question;
use App\Models\Vote;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Throwable;

class PresentQuestion extends Component
{
    public $sizeOptions = 7;
    public $sizeHeads = 7;
    public $sizeTitle = 3.5;
    public $question;
    public $countdown;
    public $seconds;
    public $chartCoef;
    public $chartNom;
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
    public $options = ['optionA', 'optionB', 'optionC', 'optionD', 'optionE', 'optionF'];

    public function mount($questionId)
    {
        $this->reset('inVoting', 'seconds', 'countdown', 'votes', 'chartNom', 'inCoefResult', 'votes', 'controlsAssigned');

        // $this->question = Question::find(19);
        if (!$this->question) {
            $this->question = Question::find($questionId);
            if (!$this->question) {
                return redirect()->route('votacion')->with('error', 'La pregunta no fue encontrada');
            }
            $lenTitle = strlen($this->question->title);
            if ($lenTitle < 25) {
                $this->sizeTitle = 5.2;
            } else if ($lenTitle < 35) {
                $this->sizeTitle = 3.8;
            } else if ($lenTitle < 100) {
                $this->sizeTitle = 3;
            } else {
                $this->sizeTitle = 2.5;
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

        $this->controls = Control::all()->pluck('id')->toArray();
        $this->dispatch('full-screen-in');
        $this->inCoefResult = $this->question->coefGraph;
        $this->setControlsAssigned();

        // $this->chartNom=Storage::disk('results')->url('images/results/10/nominalChart.png');

    }
    #[Layout('layout.presentation')]
    public function render()
    {
        if ($this->inVoting == 1) {
            return view(view: 'views.votacion.voting');
        } elseif ($this->inVoting == 2) {
            return view('views.votacion.present-question');
        } elseif ($this->inVoting == 3) {
            return view('views.votacion.results');
        }
    }

    public function voting()
    {
        $response = $this->handleVoting('run-votes');
        $this->playPause(false);
        if ($response) {
            sleep(2);
            $this->seconds = $this->question->seconds;
            $this->updateCountdown();
            $this->dispatch('start-timer');
            $this->inVoting = 1;
        }
    }

    public function inResults()
    {
        $this->inVoting = 3;
    }

    public function setImageUrl($result, $quorum)
    {
        try {
            $path = $this->setChart($result, $quorum);
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
        } catch (Throwable $th) {
            $this->addError('error', $th->getMessage());
        }
    }


    public function setChart($result, $quorum)
    {
        // Array para almacenar los datos del gráfico
        $labels = [];
        $values = [];

        // Agregar las opciones A-F si tienen valor

        foreach ($this->options as $option) {
            if ($this->question->$option !== null) {
                $labels[] = $this->question->$option;
                $values[] = $result->$option;
            }
        }
        if ($quorum) {
            $additionalOptions = [
                'nule' => 'PRESENTE'
            ];
        } else {
            $additionalOptions = [
                'abstainted' => 'ABSTENCION',
                'absent' => 'AUSENTE',
                'nule' => 'NULO'
            ];
        }
        // Agregar abstained, absent y nule con sus etiquetas en español


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
        if (!$this->stopped) {
            if ($this->seconds > 0) {
                $this->seconds -= 1;
                $this->updateCountdown();
            } else {
                $this->stopVote();
            }
        }
    }

    public function updateCountdown()
    {
        $this->updateVotes();
        $minutes = floor($this->seconds / 60);
        $seconds = $this->seconds % 60;
        $this->countdown = sprintf('%02d:%02d', $minutes, $seconds);
    }


    public function playPause($value = '')
    {
        $this->stopped = (bool) $value;
    }

    public function store()
    {

        $this->dispatch('closeModal');
        $this->playPause(true);
        if ($this->handleVoting('stop-votes')) {
            $this->createResults();
        }
    }

    public function stopVote()
    {
        $this->stopped = true;
        $this->dispatch('modal-show');
        $this->dispatch('stopPolling');
        $this->playPause(true);
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
            'optionA'      => 0,
            'optionB'      => 0,
            'optionC'      => 0,
            'optionD'      => 0,
            'optionE'      => 0,
            'optionF'      => 0,
            'nule'     => 0,
            'absent'    => 0,
            'abstainted' => 0,
        ];
        $valuesNom = [
            'optionA'      => 0,
            'optionB'      => 0,
            'optionC'      => 0,
            'optionD'      => 0,
            'optionE'      => 0,
            'optionF'      => 0,
            'nule'     => 0,
            'absent'    => 0,
            'abstainted' => 0,
        ];
        $availableOptions = $this->question->getAvailableOptions();

        if ($this->question->type == 5) {
            foreach ($this->controlsAssigned as $id => $control) {
                if ($control->isAbsent()) {
                    $valuesCoef['absent'] += $control->sum_coef;
                    $valuesNom['absent']  += $control->predios->count();

                    $control->t_publico = 0;
                } else {
                    if (array_key_exists($id, $this->votes)) {
                        $vote = $this->votes[$id];
                        if ($vote == 'A') {
                            $valuesCoef['option' . $vote] += $control->sum_coef;
                            $valuesNom['option' . $vote] += $control->predios->count();
                            $control->t_publico = 1;
                        } else if ($vote == 'B') {
                            $valuesCoef['option' . $vote] += $control->sum_coef;
                            $valuesNom['option' . $vote] += $control->predios->count();
                            $control->t_publico = 0;
                        } else {
                            $valuesCoef['nule'] += $control->sum_coef;
                            $valuesNom['nule'] += $control->predios->count();
                            $control->t_publico = 0;
                        }
                    } else {
                        $valuesCoef['abstainted'] += $control->predios->count();
                        $valuesNom['abstainted']  += $control->predios->count();
                        $control->t_publico = 0;
                    }
                }
                $control->save();
            }
        } else if ($this->question->type == 1) {
            foreach ($this->controlsAssigned as $id => $control) {
                if ($control->isAbsent()) {
                    $valuesCoef['absent'] += $control->sum_coef;
                    $valuesNom['absent']  += $control->predios->count();
                } else {

                    if (array_key_exists($id, $this->votes)) {

                        $valuesCoef['nule'] += $control->sum_coef;
                        $valuesNom['nule'] += $control->predios->count();
                    } else {
                        $valuesCoef['abstainted'] += $control->sum_coef;
                        $valuesNom['abstainted']  += $control->predios->count();
                    }
                }
            }
        } else {
            foreach ($this->controlsAssigned as $id => $control) {
                if ($control->isAbsent()) {
                    $valuesCoef['absent'] += $control->sum_coef_can;
                    $valuesNom['absent']  += $control->getPrediosCan();
                } else {

                    if (array_key_exists($id, $this->votes)) {

                        $vote = $this->votes[$id];
                        if (in_array($vote, $availableOptions)) {
                            $valuesCoef['option' . $vote] += $control->sum_coef_can;
                            $valuesNom['option' . $vote] += $control->getPrediosCan();
                        } else {

                            $valuesCoef['nule'] += $control->sum_coef_can;
                            $valuesNom['nule'] += $control->getPrediosCan();
                        }
                    } else {
                        $valuesCoef['abstainted'] += $control->sum_coef_can;
                        $valuesNom['abstainted']  += $control->getPrediosCan();
                    }
                    $valuesCoef['abstainted'] += ($control->sum_coef - $control->sum_coef_can);
                    $valuesNom['abstainted']  +=  ($control->predios->count() - $control->getPrediosCan());
                }
            }
        }
        $totalCoef = 0;
        foreach ($valuesCoef as $value) {
            $totalCoef += $value;
        }
        $totalNom = 0;
        foreach ($valuesNom as $value) {
            $totalNom += $value;
        }
        try {
            $valuesNom['question_id'] = $this->question->id;
            $valuesNom['isCoef'] = false;
            $valuesNom['total'] = $totalNom;

            $valuesCoef['question_id'] = $this->question->id;
            $valuesCoef['isCoef'] = true;
            $valuesCoef['total'] = $totalCoef;
            if($this->question->resultCoef){
                $this->question->resultCoef->update($valuesCoef);
                $this->question->resultNom->update($valuesNom);
            }else{
                $resultNom = Result::create($valuesNom);
                $resultCoef = Result::create($valuesCoef);
            }


            $this->question->quorum = Control::where('state', 1)->sum('sum_coef');
            $this->question->predios = Control::where('state', 1)->sum('predios_vote');

            $fileController = new FileController;

            $fileController->exportResult($this->question);
            $fileController->exportVotes($this->votes, $this->question->id, $this->question->title);
        } catch (Throwable $th) {
            $this->addError('Error', $th->getMessage());
        }
        $this->reset('votes');
        foreach ($this->question->results as $result) {
            $this->setImageUrl($result, ($this->question->type == 1));
        }
        $this->inResults();
    }


    public function goBack()
    {
        $this->mount($this->question->id);

        $this->dispatch('$refresh');
    }

    public function goPresent()
    {
        $this->mount($this->question->id);
        $this->handleVoting('stop-votes');
        $this->dispatch('$refresh');
    }


    public function setControlsAssigned()
    {
        $controls = Control::whereHas('predios')->get();
        foreach ($controls as $control) {
            $this->controlsAssigned[$control->id] = $control;
        }
    }

    public function proof()
    {
        dd($this->votes);
    }

    public function updateVotes()
    {
        $this->votes = Vote::pluck('vote', 'control')->toArray();
    }
    public function handleVoting($action)
    {
        $pythonUrl = General::where('key', 'PYTHON_URL')->first();
        $pythonUrl = ($pythonUrl) ? $pythonUrl : 'http://127.0.0.1:5000';
        try {
            $response = Http::get($pythonUrl . '/' . $action);
            return True;
        } catch (Throwable $th) {
            $this->addError('Error', 'Error al conectar con el servidor python: ' . $th->getMessage());
            return False;
        }
    }


    public function getOut()
    {
        Vote::truncate();
        return redirect()->route('votacion')->with('success', 'Resultado almacenado correctamente');
    }
}
