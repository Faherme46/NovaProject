<?php

namespace App\Livewire\Votacion;

use Livewire\Component;
use Livewire\Attributes\Layout;

use App\Models\Control;
use App\Models\Result;

use App\Http\Controllers\FileController;
use App\Http\Controllers\QuestionController;
use App\Models\Question;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Throwable;

class PresentQuestion extends Component
{
    public $sizeOptions = 7;
    public $sizeHeads = 7;
    public $sizeTitle = 3.5;
    public $question;
    public $countdown;
    public $seconds;
    public $stopped = false;
    public $step = 1;
    public $colors = [
        1 => 'btn-black',       //sin asignacion
        2 => 'btn-secondary',      //sin voto
        3 => 'btn-success',       //votado
    ];
    public $controls;
    public $controlAssignedIds;

    public $inVoting = 2;
    public $inCoefResult = true;

    public $chartCoef;
    public $chartNom;
    public $votes = [];
    public $options = ['optionA', 'optionB', 'optionC', 'optionD', 'optionE', 'optionF'];
    public $plancha = false;
    public $plazas = false;
    public $plazasCoef;
    public $resultToUse;
    public $valuesPlanchas;

    public $newTitle;
    public $newOptions = [];
    public $isEditting = false;
    public function mount($questionId, $plancha = false, $plazas = 0)
    {

        $response = $this->handleVoting(action: 'run-votes');
        if (!$response) {
            return session()->flash('error', 'Problemas para conectar al servidor python');
        }
        $this->reset('inVoting', 'seconds', 'countdown', 'votes', 'inCoefResult', 'votes', );
        $this->step = 1;
        // $this->question = Question::find(19);
        if (!$this->question) {

            $this->question = Question::find($questionId);
            $this->plancha = $plancha;
            $this->plazas = $plazas;
            $this->setSizePresentation();
            if (!$this->question) {
                return redirect()->route('votacion')->with('error', 'La pregunta no fue encontrada');
            }
        }
        $this->newTitle = $this->question->title;
        $this->controls = Control::all()->pluck('id')->toArray();
        $this->inCoefResult = $this->question->coefGraph;
        $this->plazasCoef = $this->question->coefGraph;
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
        } elseif ($this->inVoting == 4) {
            return view('views.votacion.planchas');
        }
    }

    public function voting()
    {

        $this->playPause(false);
        $this->seconds = $this->question->seconds;
        $this->updateCountdown();
        $this->dispatch('start-timer');
        $this->inVoting = 1;
    }

    public function inResults()
    {
        $this->dispatch('modal-spinner-close');
        $this->inVoting = 3;
    }
    public function toPlanchas()
    {
        $this->resultToUse = ($this->inCoefResult) ? $this->question->resultCoef : $this->question->resultNom;
        $this->calculatePlazas();
        $this->inVoting = 4;
    }






    public function decrement()
    {
        if (!$this->stopped) {
            if ($this->seconds > 0) {
                $this->seconds -= $this->step;

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
        if ($value) {
            $this->step = $this->step / 2;
        }
    }

    public function store()
    {

        $this->dispatch('closeModal');
        $this->playPause(true);
        if ($this->handleVoting('stop-votes')) {
            $questionController=new QuestionController($this->question->id);
            try {
                if(is_array($questionController->createResults())){
                    $this->chartCoef=$questionController->createResults()[0];
                    $this->chartNom=$questionController->createResults()[1];
                };
            } catch (Throwable $th) {
                dd($th->getMessage());
                return back()->withErrors('error',$th->getMessage());
            }



            $this->inResults();
        }
    }

    public function stopVote()
    {

        $this->seconds = 0;
        $this->dispatch('modal-show');
        $this->playPause(true);
        $this->dispatch('$refresh');
    }


    public function oneMoreMinut()
    {
        $this->playPause(false);
        $this->dispatch('modal-close');
        $this->seconds = 60;
        $this->updateCountdown();
    }




    public function goBack()
    {
        $this->mount($this->question->id, $this->plancha, $this->plazas);

        $this->dispatch('$refresh');
    }

    public function goPresent()
    {
        $this->mount($this->question->id, $this->plancha, $this->plazas);
        $this->handleVoting('stop-votes');
        $this->dispatch('$refresh');
    }

    public function sleep($value)
    {
        sleep($value);
    }


    public function setControlsAssigned()
    {

        $this->controlAssignedIds = Control::where('state', 1)->pluck('id')->toArray();


    }

    public function proof()
    {
        dd($this->votes);
    }

    public function updateVotes()
    {
        $this->votes = Control::whereNotNull('vote')->pluck('id')->toArray();
    }
    public function handleVoting($action)
    {

        $pythonUrl = env('PYTHON_PATH', 'http://127.0.0.1:5000');
        try {
            $response = Http::get($pythonUrl . '/' . $action);
            if ($response->status() == 400) {
                $this->addError('error', 'Error Conectando al dispositivo hid');
                $this->dispatch('modal-all-close');
                return False;
            }
            return True;
        } catch (Throwable $th) {
            $this->addError('Error', 'Error al conectar con el servidor python: ' . $th->getMessage());
            return False;
        }
    }


    public function getOut()
    {
        Control::query()->update(['vote' => null]);
        return redirect()->route('votacion')->with('success', 'Resultado almacenado correctamente');
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

    public function updatePlazasCoef($value)
    {
        $this->resultToUse = ($value) ? $this->question->resultCoef : $this->question->resultNom;
        $this->calculatePlazas();
    }

    public function calculatePlazas()
    {
        $total = 0;
        $this->valuesPlanchas = [];
        foreach ($this->options as $op) {
            if ($this->question[$op] != 'EN BLANCO') {
                $total += $this->resultToUse[$op];
            }
        }

        $umbral = $total / $this->plazas;
        if ($total <= 0) {
            foreach ($this->options as $option) {
                if ($this->question[$option] !== 'EN BLANCO') {
                    $this->valuesPlanchas[$option] = 1;
                }
            }
        } else {

            foreach ($this->options as $option) {
                if ($this->question[$option] !== 'EN BLANCO') {

                    $this->valuesPlanchas[$option] = floor($this->resultToUse[$option] / $umbral);
                } else {
                    $this->valuesPlanchas[$option] = 0;
                }
            }


            // Calcular residuos y asignar curules adicionales
            $residuos = [];
            $plazasRestantes = $this->plazas - array_sum($this->valuesPlanchas);

            foreach ($this->options as $option) {
                if ($this->question[$option] !== 'En blanco') {
                    $residuos[$option] = $this->resultToUse[$option] - $umbral * $this->valuesPlanchas[$option];
                }
            }


            // Ordenar opciones por residuos
            arsort($residuos);
            foreach (array_keys($residuos) as $option) {
                if ($plazasRestantes > 0) {
                    $this->valuesPlanchas[$option] += 1;
                    $plazasRestantes--;
                }
            }
        }
        $this->valuesPlanchas['total'] = $total;
        $this->valuesPlanchas['umbral'] = round($umbral, 4);
    }


    public function editting()
    {
        $this->isEditting = true;
    }

    public function updateQuestion()
    {
        $this->question->title = $this->newTitle;


        $this->question->update($this->newOptions);

        $this->question->save();
        $this->setSizePresentation();
        $this->isEditting = false;
    }
}
