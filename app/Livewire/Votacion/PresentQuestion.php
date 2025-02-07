<?php

namespace App\Livewire\Votacion;

use Livewire\Component;
use Livewire\Attributes\Layout;

use App\Models\Control;
use App\Models\Result;

use App\Http\Controllers\FileController;
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
    public $controlsAssigned;
    public $controlAssignedIds;
    public $controlAbsent;

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

    public $start;
    public $newTitle;
    public $newOptions = [];
    public $isEditting = false;
    public function mount($questionId, $plancha = false, $plazas = 0)
    {

        $response = $this->handleVoting(action: 'run-votes');
        if (!$response) {
            return session()->flash('error', 'Problemas para conectar al servidor python');
        }
        $this->reset('inVoting', 'seconds', 'countdown', 'votes', 'inCoefResult', 'votes', 'controlsAssigned');
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

    public function setImageUrl($result, $quorum)
    {
        try {
            $path = $this->setChart($result, $quorum);

            // dd($urlImg);
            if ($result->isCoef) {
                $result->chartPath = $path;
                $this->chartCoef = $path;
            } else {
                $result->chartPath = $path;
                $this->chartNom = $path;
            }
            $result->save();
        } catch (Throwable $th) {
            $this->addError('error', 'ERROR ' . $th->getMessage());
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
        $six = count($labels) == 6;


        if ($quorum) {
            $additionalOptions = [
                'nule' => 'PRESENTE'
            ];
        } else {
            $additionalOptions = [
                'abstainted' => 'ABSTENCION',
                'absent' => 'AUSENTE',
            ];

            if ($this->question->type != 2 || !$six) {
                $additionalOptions['nule'] = 'NULO';
            }
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
        $this->start = microtime(true);
        $this->dispatch('closeModal');
        $this->playPause(true);
        if ($this->handleVoting('stop-votes')) {
            $this->createResults();
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
        $valuesCoef['absent'] += $this->controlAbsent->sum('sum_coef');
        $valuesNom['absent']  += $this->controlAbsent->sum('predios_total');
        $valuesCoef['abstainted'] += $this->controlsAssigned->sum('sum_coef_abs');
        $valuesNom['abstainted']  +=  $this->controlsAssigned->sum('predios_abs');
        $valuesCoef['abstainted'] += $this->controlsAssigned->whereNull('vote')->sum('sum_coef');
        $valuesNom['abstainted']  +=  $this->controlsAssigned->whereNull('vote')->sum('predios_total');
        if ($this->question->type == 5) {

            $valuesNom['optionA'] += $this->controlsAssigned->where('vote', 'A')->sum('predios_total');
            $valuesCoef['optionA'] += $this->controlsAssigned->where('vote', 'A')->sum('sum_coef');
            $valuesNom['optionB'] += $this->controlsAssigned->where('vote', 'B')->sum('predios_total');
            $valuesCoef['optionB'] += $this->controlsAssigned->where('vote', 'B')->sum('sum_coef');
            $valuesNom['nule'] += $this->controlsAssigned->whereNotIn('vote', ['A', 'B'])->sum('predios_total');
            $valuesCoef['nule'] += $this->controlsAssigned->whereNotIn('vote', ['A', 'B'])->sum('sum_coef');
            Control::where('state', 1)
                ->whereIn('vote', ['A', 'B'])
                ->update(['t_publico' => DB::raw('CASE WHEN vote = "A" THEN 1 ELSE 0 END')]);
        } else if ($this->question->type == 1) {
            $valuesNom['nule'] += $this->controlsAssigned->whereNotNull('vote')->sum('predios_total');
            $valuesCoef['nule'] += $this->controlsAssigned->whereNotNull('vote')->sum('sum_coef');
        } else {
            foreach ($availableOptions as $option) {
                $valuesNom['option' . $option] += $this->controlsAssigned->where('vote', $option)->sum('predios_vote');
                $valuesCoef['option' . $option] += $this->controlsAssigned->where('vote', $option)->sum('sum_coef_can');
            }
            $valuesNom['nule'] += $this->controlsAssigned->whereNotNull('vote')->whereNotIn('vote', $availableOptions)->sum('predios_vote');
            $valuesCoef['nule'] += $this->controlsAssigned->whereNotNull('vote')->whereNotIn('vote', $availableOptions)->sum('sum_coef_can');
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
            if ($this->question->resultCoef) {
                $resultNom = Result::where('id', $this->question->resultNom->id)->update($valuesNom);
                $resultCoef = Result::where('id', $this->question->resultCoef->id)->update($valuesCoef);
            } else {
                $resultNom = Result::create($valuesNom);
                $resultCoef = Result::create($valuesCoef);
            }

            $this->question->quorum = Control::where('state', 1)->sum('sum_coef');
            $this->question->predios = Control::where('state', 1)->sum('predios_vote');

            $fileController = new FileController;

            $fileController->exportResult($this->question);
            $fileController->exportVotes($this->votes, $this->question->id, $this->question->title);
        } catch (Throwable $th) {
            $this->addError('Error', 'ERROR ' . $th->getMessage());
        }
        $this->reset('votes');
        foreach ($this->question->results as $result) {
            $this->setImageUrl($result, ($this->question->type == 1));
        }
        $this->inResults();
        $end = microtime(true);
        $executionTime = $end - $this->start;
        dd($executionTime);
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
        $this->controlsAssigned = Control::where('state', 1)->get();
        $this->controlAssignedIds = $this->controlsAssigned->pluck('id')->toArray();
        $this->controlAbsent = Control::whereNotIn('state', [1, 4])->get();
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
