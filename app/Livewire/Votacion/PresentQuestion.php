<?php

namespace App\Livewire\Votacion;

error_reporting(E_ALL);
error_log("error_log.txt");

use Livewire\Component;
use Livewire\Attributes\Layout;

use App\Models\Control;
use App\Models\Result;


use App\Http\Controllers\QuestionController;
use App\Models\Plancha;
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
    public $seconds;
    public $stopped = false;
    public $step = 1;
    public $colors = [
        1 => 'btn-black',       //sin asignacion
        2 => 'btn-secondary',      //sin voto
        3 => 'btn-success',       //votado
    ];
    public $controls;
    public $countdown;

    public $inVoting = 2;
    public $inCoefResult = true;

    public $chartCoef;
    public $chartNom;
    public $votes = [];
    public $options = ['optionA', 'optionB', 'optionC', 'optionD', 'optionE', 'optionF'];
    public $isPlancha = false;
    public $plazasCoef;
    public $resultToUse;
    public $plancha;
    public $inRondas = false;
    public $dataRondas = [
        'currentRonda' => 1
    ];
    public $rondasExtra;


    public $newTitle;
    public $newOptions = [];
    public $isEditting = false;
    public function mount($questionId, $plancha = false, $inRondas = false)
    {
        $numcontrols = cache('asamblea')['controles'];
        $args = [
            'numControls' => $numcontrols
        ];
        if ($numcontrols > 400) {
            $args['hid_1'] = cache('hid_1', '0');
            $args['hid_0'] = cache('hid_0', '0');
        }
        $response = $this->handleVoting('run-votes', $args);
        if (!$response) {

            return redirect()->route('votacion')->with('error', 'Problemas para conectar al servidor python');
        }
        $this->reset('inVoting', 'seconds', 'countdown', 'votes', 'inCoefResult', 'votes');
        $this->step = 1;
        // $this->question = Question::find(19);

        if (!$this->question || $this->question == null) {

            $this->question = Question::find($questionId);
            $this->isPlancha = $plancha;
            $this->inRondas = $inRondas;
            if ($this->inRondas) {
                $this->dataRondas['currentRonda'] = request()->query('currentQuestion');
                $this->dataRondas['numRondas'] = request()->query('numRondas');
                $this->dataRondas['mainQuestion'] = request()->query('mainQuestion');
            }

            $this->setSizePresentation();
            if (!$this->question || $this->question == null) {
                \Illuminate\Support\Facades\Log::channel('custom')->info('Se Inicia una votacion');
                return redirect()->route('votacion')->with('error', 'La pregunta no fue encontrada');
            }
        }


        $this->newTitle = $this->question->title;
        $this->controls = Control::select('id', 'vote', 'voted', 'state')->get()->keyBy('id');
        $this->inCoefResult = $this->question->coefGraph;
        $this->plazasCoef = $this->question->coefGraph;
        // $this->setControlsAssigned();

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
        $minutes = floor($this->seconds / 60);
        $seconds = $this->seconds % 60;
        $this->countdown = sprintf('%02d:%02d', $minutes, $seconds);
        $this->dispatch('iniciarTemporizador');

        $this->inVoting = 1;
    }

    public function inResults()
    {
        $this->dispatch('modal-spinner-close');
        $this->inVoting = 3;
    }
    public function toPlanchas()
    {
        if ($this->inRondas) {
            $this->question = Question::find($this->dataRondas['mainQuestion']);;
            $this->rondasExtra = Question::where('parent_id', $this->question->id)->get();
        }

        $this->resultToUse = ($this->inCoefResult) ? $this->question->resultCoef : $this->question->resultNom;
        $this->calculatePlazas($this->inCoefResult);
        $this->inVoting = 4;
    }



    public function playPause($value = '')
    {
        $this->stopped = (bool) $value;
    }

    public function store()
    {

        $numcontrols = cache('asamblea')['controles'];
        $this->dispatch('closeModal');
        $this->playPause(true);

        $questionController = new QuestionController($this->question->id, $this->inRondas);
        try {

            $listResults = $questionController->createResults();
            if (is_array($listResults)) {
                $this->chartCoef = $listResults[0];
                $this->chartNom = $listResults[1];
                cache(['toExportVotes' => true]);
            }

        } catch (Throwable $th) {
            session()->flash('error', 'Error al generar resultados: ' . $th->getMessage() . ' ' . $th->getFile() . ' ' . $th->getLine());
            return back()->withErrors('error', $th->getMessage());
        }



        if (!$this->inRondas || ($this->dataRondas['currentRonda'] == $this->dataRondas['numRondas'])) {
            if ($this->inRondas) {
                $listResults = $questionController->storeRondas($this->dataRondas['mainQuestion']);
                if (is_array($listResults)) {
                    $this->chartCoef = $listResults[0];
                    $this->chartNom = $listResults[1];
                    cache(['toExportVotes' => true]);
                }
            }
            $this->inResults();
        } else {

            $this->dispatch('modal-spinner-close');
            $this->dataRondas['currentRonda'] += 1;
            $nextQuestionId = Question::where('parent_id', $this->dataRondas['mainQuestion'])->where('idRonda', $this->dataRondas['currentRonda'])->value('id');
            $parametros = [];
            $parametros['questionId'] = $nextQuestionId;
            $parametros['plancha'] = $this->isPlancha;
            $parametros['inRondas'] = true;
            $parametros['numRondas'] = $this->dataRondas['numRondas'];
            $parametros['mainQuestion'] = $this->dataRondas['mainQuestion'];
            $parametros['currentQuestion'] = $this->dataRondas['currentRonda'];

            return redirect()->route('questions.show', $parametros);
        }

    }

    #[On('tiempoAgotado')]
    public function stopVote()
    {
        $this->stopped = true;
        $this->seconds = 0;
        $this->dispatch('detenerTemporizador');
        $this->playPause(true);
        $this->dispatch('$refresh');
        $this->dispatch('modal-show');
    }


    public function oneMoreMinut()
    {
        $this->playPause(false);
        $this->dispatch('modal-close');
        $this->seconds = 60;
        $this->countdown = '01:00';
        $this->dispatch('iniciarTemporizador');
    }




    public function goBack()
    {
        $this->mount($this->question->id, $this->isPlancha, $this->inRondas);

        $this->dispatch('$refresh');
    }

    public function goPresent()
    {
        $this->playPause(true);


        $this->dispatch('stop-timer');
        $this->inVoting = 2;
    }

    public function sleep($value)
    {
        sleep($value);
    }


    public function setControlsAssigned()
    {

        // $this->controlAssignedIds = array_flip(Control::where('state', 1)->pluck('id')->toArray());
        // if ($this->rondasExtra > 0) {
        //     $this->controlVoted = array_flip(Control::where('state', 1)->where('voted', 1)->pluck('id')->toArray());
        // }
    }

    public function proof()
    {
        dd($this->votes);
    }

    public function updateVotes()
    {
        if (!$this->stopped) {
            $this->controls = Control::select('id', 'vote', 'voted', 'state')->get()->keyBy('id');
        }
    }
    public function handleVoting($action, $args = array())
    {

        $pythonUrl = env('PYTHON_PATH', 'http://127.0.0.1:5000');
        try {
            if ($action == 'stop-votes') {

                $response = Http::async()->get($pythonUrl . '/' . $action . '', $args);
            } else {
                $response = Http::get($pythonUrl . '/' . $action . '', $args);
                if ($response->status() == 400) {
                    $this->addError('error', 'Error Conectando al dispositivo hid');
                    $this->dispatch('modal-all-close');
                    return False;
                }
            }
            return True;
        } catch (Throwable $th) {
            $this->addError('Error', 'Error al conectar con el servidor python: ' . $th->getMessage());
            return False;
        }
    }


    public function getOut()
    {
        cache()->forget('voting');
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
        $this->inCoefResult = $value;
        $this->calculatePlazas($value);
    }

    public function calculatePlazas($value)
    {
        $total = 0;

        if ($this->inRondas) {

            $mainQuestion = Question::find($this->dataRondas['mainQuestion']);
            $plazas = $mainQuestion->plancha->plazas;
            $rondasQuestions = Question::where('parent_id', $mainQuestion->id)->get();
            $rondas = [];
            $rondas[$mainQuestion->idRonda] = $mainQuestion;
            foreach ($rondasQuestions as $ronda) {
                $rondas[$ronda->idRonda] = $ronda;
            }
            $listOptions = [];
            foreach ($rondas as $id => $ronda) {
                $result = ($value) ? $ronda->resultCoef : $ronda->resultNom;
                foreach ($this->options as $option) {
                    if ($ronda->$option && $ronda->$option != 'EN BLANCO') {
                        $total += $result[$option];
                        $listOptions[$id . $option]['id'] = $id;
                        $listOptions[$id . $option]['option'] = $option;
                        $listOptions[$id . $option]['name'] = $ronda->$option;
                        $listOptions[$id . $option]['value'] = $result[$option];
                    }
                }
            }


            $umbral = $total / $plazas;

            if ($total <= 0) {
                foreach ($rondas as $idRonda => $ronda) {
                    foreach ($this->options as $option) {
                        $ronda->plancha[$option] = 0;
                    }
                }
            } else {
                $sumTotal = 0;
                foreach ($rondas as $idRonda => $ronda) {
                    foreach ($this->options as $option) {
                        if (isset($listOptions[$idRonda . $option]) && $listOptions[$idRonda . $option]['name'] !== 'EN BLANCO') {
                            $plazas = floor($listOptions[$idRonda . $option]['value'] / $umbral);
                            $ronda->plancha[$option] = $plazas;
                            $sumTotal += $plazas;
                        } else {
                            $ronda->plancha[$option] = 0;
                        }
                    }
                }


                // Calcular residuos y asignar curules adicionales
                $residuos = [];
                $plazasRestantes = $mainQuestion->plancha->plazas - $sumTotal;
                foreach ($rondas as $idRonda => $ronda) {
                    foreach ($this->options as $option) {
                        if (isset($listOptions[$idRonda . $option])) {
                            $residuos[$idRonda . $option] = $listOptions[$idRonda . $option]['value'] - $umbral * $ronda->plancha[$option];
                        }
                    }
                }
                // Ordenar opciones por residuos
                arsort($residuos);
                foreach ($residuos as $option => $residuo) {
                    if ($plazasRestantes > 0) {
                        $idRonda = $listOptions[$option]['id'];
                        $optionName = $listOptions[$option]['option'];
                        $rondas[$idRonda]->plancha[$optionName] += 1;
                        $plazasRestantes--;
                    } else {
                        break;
                    }
                }
            }
            // $this->valuesPlanchas['total'] = $total;
            foreach ($rondas as $idRonda => $ronda) {
                $ronda->plancha->umbral = round($umbral, 4);
                $ronda->plancha->save();
            }
        } else {
            foreach ($this->options as $op) {
                if ($this->question[$op] != 'EN BLANCO') {
                    $total += $this->resultToUse[$op];
                }
            }

            $umbral = $total / $this->question->plancha->plazas;

            if ($total <= 0) {
                foreach ($this->options as $option) {
                    if ($this->question[$option] !== 'EN BLANCO') {
                        $this->question->plancha[$option] = 0;
                    }
                }
            } else {
                $sumTotal = 0;
                foreach ($this->options as $option) {
                    if ($this->question[$option] !== 'EN BLANCO') {
                        $plazas = floor($this->resultToUse[$option] / $umbral);
                        $this->question->plancha[$option] = $plazas;
                        $sumTotal += $plazas;
                    } else {
                        $this->question->plancha[$option] = 0;
                    }
                }


                // Calcular residuos y asignar curules adicionales
                $residuos = [];
                $plazasRestantes = $this->question->plancha->plazas - $sumTotal;

                foreach ($this->options as $option) {
                    if ($this->question[$option] !== 'EN BLANCO') {
                        $residuos[$option] = $this->resultToUse[$option] - $umbral * $this->question->plancha[$option];
                    }
                }
                // Ordenar opciones por residuos
                arsort($residuos);
                foreach (array_keys($residuos) as $option) {
                    if ($plazasRestantes > 0) {
                        $this->question->plancha[$option] += 1;
                        $plazasRestantes--;
                    }
                }
            }
            // $this->valuesPlanchas['total'] = $total;
            $this->question->plancha->umbral = round($umbral, 4);
            $this->question->plancha->save();
        }
    }


    public function editting()
    {
        $this->isEditting = true;
    }

    public function updateQuestion()
    {
        $this->question->title = strtoupper($this->newTitle);
        $this->question->update($this->newOptions);
        $this->question->save();
        $this->setSizePresentation();
        $this->isEditting = false;
    }
}
