<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;

use App\Models\Control;
use App\Models\General;
use App\Models\Question;
use App\Models\QuestionsPrefab;
use App\Models\Vote;
use Illuminate\Support\Facades\Http;
use Throwable;

class Votacion extends Component
{
    public $isQuestion;
    public $questionId;
    public $questionTitle = '';

    public $questionOptions = [
        'A' => '',
        'B' => '',
        'C' => '',
        'D' => '',
        'E' => '',
        'F' => '',
    ];

    public $plancha = false;
    public $plazas = 0;
    public $numPlazasPlancha = 0;
    public $questionType = 0;
    public $questionCoefChart;
    //todo cambiar graph por chart
    public $questionWhite = false;

    #prefab questions
    public $questionsPrefab;
    #values
    public $prediosRegistered = 0;
    public $prediosVote = 0;
    public $controlsRegistered = 0;
    public $controlsVote = 0;
    public $quorumVote = 0;
    public $quorumRegistered = 0;

    public $mins = 2;
    public $secs = 0;
    public $blockFields = [
        'optionB',
        'optionC',
        'optionD',
        'optionE',
        'optionF'
    ];
    public function mount()
    {

        $this->questionsPrefab = QuestionsPrefab::all();
        $this->getValues();
        $this->questionTitle='';
    }

    #[Layout('layout.full-page')]
    public function render()
    {
        $this->getValues();
        return view('views.votacion.votacion');
    }


    public function getValues()
    {
        $this->prediosRegistered = Control::whereIn('state', [1, 2])
            ->withCount('predios')
            ->get()
            ->sum('predios_count');

        $this->prediosVote = Control::whereIn('state', [1, 2])->sum('predios_vote');
        $this->controlsRegistered = Control::whereIn('state', [1, 2])->count();
        $this->controlsVote = Control::where('sum_coef_can', '!=', 0)->count();
        $this->quorumVote = Control::whereNotIn('state', [4])->sum('sum_coef_can');
        $this->quorumRegistered = Control::whereNotIn('state', [4])->sum('sum_coef');
    }

    public function setQuestion($questionId)
    {
        $this->resetErrorBag();
        $this->reset('questionWhite');
        $selectedQuestion = QuestionsPrefab::find($questionId);
        $this->isQuestion = true;
        $this->questionTitle = $selectedQuestion->title;
        $this->questionId = $selectedQuestion->id;
        $this->questionType = $selectedQuestion->type;
        $this->questionCoefChart = ($selectedQuestion->coefGraph) ? '1' : '0';
        $this->questionOptions = [
            'A' => $selectedQuestion->optionA,
            'B' => $selectedQuestion->optionB,
            'C' => $selectedQuestion->optionC,
            'D' => $selectedQuestion->optionD,
            'E' => $selectedQuestion->optionE,
            'F' => $selectedQuestion->optionF,
        ];
        $this->dispatch('$refresh');
        $this->dispatch('setInputs');
    }

    public function updatedquestionWhite($value)
    {
        // dd($this->questionWhite);
        $count = 0;
        foreach ($this->questionOptions as $value) {
            if ($value) {
                $count++;
            }
        }
        $options = ['A', 'B', 'C', 'D', 'E', 'F'];
        if ($this->questionWhite) {
            if (in_array($this->questionType, [2, 6, 7])) {

                $id = $options[$count];
            } elseif ($this->questionType == 3) {
                $id = 'F';
            } elseif ($this->questionType == 4) {
                $id = 'C';
            }
            $this->dispatch('setWhite', myId: $id);
        } else {
            if ($this->questionType == 2 || $this->questionType == 6) {
                foreach ($this->questionOptions as $key => $value) {
                    if ($value == 'Blanco') {
                        $id = $key;
                    }
                }
            } elseif ($this->questionType == 3) {
                $id = 'F';
            } elseif ($this->questionType == 4) {
                $id = 'C';
            }
            $this->dispatch('setNone', myId: $id);
        }
    }

    public function updatedQuestionType($value)
    {
        $this->resetErrorBag();
        $this->reset(['questionOptions', 'questionWhite']);
        $this->questionWhite = false;
        if ($this->questionId == 12) {
            $this->plancha = false;
            switch ($value) {
                case 1:
                    $this->questionOptions = [
                        'A' => '',
                        'B' => '',
                        'C' => '',
                        'D' => '',
                        'E' => '',
                        'F' => '',
                    ];

                    break;
                case 2:
                    $this->questionOptions = [
                        'A' => '',
                        'B' => '',
                        'C' => '',
                        'D' => '',
                        'E' => '',
                        'F' => '',
                    ];

                    break;
                case 3:

                    $this->questionOptions = [
                        'A' => '',
                        'B' => '',
                        'C' => '',
                        'D' => 'Aprobado',
                        'E' => 'No Aprobado',
                        'F' => '',
                    ];
                    break;
                case 4:
                    $this->questionOptions = [
                        'A' => 'Si ',
                        'B' => 'No',
                        'C' => '',
                        'D' => '',
                        'E' => '',
                        'F' => '',
                    ];
                    break;
                default:
                    $this->questionOptions = [
                        'A' => '',
                        'B' => '',
                        'C' => '',
                        'D' => '',
                        'E' => '',
                        'F' => '',
                    ];
                    break;
            }
        }
        $this->dispatch('setInputs');
    }


    public function increment($min)
    {
        if ($min) {
            if ($this->mins < 59) {
                $this->mins++;
                if ($this->mins == 60) {
                    $this->mins--;
                }
            }
        } else {
            if ($this->secs < 59) {
                $this->secs += 10;
                if ($this->secs == 60) {
                    $this->secs--;
                }
            }
        }
    }

    public function decrement($min)
    {
        if ($min) {
            if ($this->mins > 0) {
                $this->mins--;
            }
        } else {
            if ($this->secs > 0) {
                $this->secs -= 10;
                if ($this->secs == 49) {
                    $this->secs++;
                }
            }
        }
    }

    public function disableWhite()
    {
        $this->questionWhite = false;
    }

    public function isOneOptionAlmost($questionOptions)
    {
        foreach ($this->questionOptions as $key => $value) {
            if ($value) {
                return true;
            }
        }
        return false;
    }
    public function createQuestion()
    {
        $this->resetErrorBag();
        if (!$this->verifyDevice()) {
            return;
        }
        //Se requiere un titulo a la pregunta'
        $error = 0;
        if (!$this->questionTitle) {
            $this->addError('questionTitle', 'Se requiere el titulo de la pregunta');
            $error = 1;
        }
        //Se requiere un tipo de la pregunta'
        if (!$this->questionType) {
            $this->addError('questionTitle', 'Se requiere el tipo de la pregunta');
            $error = 1;
        }
        if ($this->questionType == 2) {
            if (!$this->isOneOptionAlmost($this->questionOptions)) {

                $this->addError('questionTitle', 'Al menos uno de los campos debe tener un valor.');
                $error = 1;
            }
        }

        //si hay plancha se requiere el numero de plazas
        if  ($this->plancha && !$this->plazas){
            $this->addError('questionTitle', 'Se requiere el numero de plazas');
            $error = 1;
        }elseif ($this->plazas<0 || !is_int($this->plazas)) {
            $this->addError('questionTitle', 'El numero de plaznas no es valido');
        }

        $quorum = Control::where('state', 1)->sum('sum_coef');
        if ($quorum <= 0) {
            $this->addError('questionTitle', 'No se han registrado asistentes');
            $error = 1;
        }
        $seconds = $this->secs + ($this->mins * 60);
        if ($seconds <= 0) {
            $this->addError('questionTitle', 'El tiempo debe ser mayor a 0');
            $error = 1;
        }

        if ($error) {
            return;
        }
        $forbidden = ['/', "\\", "*", '?', '"', ':', "<", ">", "|"];
        //'No se han registrado controles'
        $newTitle = str_replace($forbidden, "", $this->questionTitle);
        try {
            Vote::truncate();
            $question = Question::create([
                'title' => strtoupper($newTitle),
                'optionA' => ($this->questionOptions['A']) ? strtoupper(rtrim($this->questionOptions['A'])) : null,
                'optionB' => ($this->questionOptions['B']) ? strtoupper(rtrim($this->questionOptions['B'])) : null,
                'optionC' => ($this->questionOptions['C']) ? strtoupper(rtrim($this->questionOptions['C'])) : null,
                'optionD' => ($this->questionOptions['D']) ? strtoupper(rtrim($this->questionOptions['D'])) : null,
                'optionE' => ($this->questionOptions['E']) ? strtoupper(rtrim($this->questionOptions['E'])) : null,
                'optionF' => ($this->questionOptions['F']) ? strtoupper(rtrim($this->questionOptions['F'])) : null,
                
                'isValid' => ($this->questionType == 6) ? 0 : 1,
                'coefGraph' => (bool)$this->questionCoefChart,
                'quorum' => $quorum,
                'predios' => Control::where('state', 1)->sum('predios_vote'),
                'seconds' => $seconds,
                'type' => $this->questionType
            ]);

            $parametros=['questionId' => $question->id];
            if($this->plancha){
                $parametros['plancha']= $this->plancha;
                $parametros['plazas']= $this->plazas;
            }
            return redirect()->route('questions.show', $parametros);
        } catch (Throwable $th) {
            return $this->addError('questionCreate', $th->getMessage());
        }
    }
    public function verifyDevice()
    {
        $pythonUrl = General::where('key', 'PYTHON_URL')->first();
        $pythonUrl = ($pythonUrl) ? $pythonUrl : 'http://127.0.0.1:5000';
        try {
            $response = Http::get($pythonUrl . '/verify-device');
            if ($response->status() != 200) {
                $this->addError('Error', 'El dispositivo HID no se encontro conectado al servidor, por favor conectelo e inicie Quiz Freedom');
                return false;
            };

            return True;
        } catch (Throwable $th) {
            $this->addError('Error', 'Error al conectar con el servidor python ');
        }
    }

    public function  updatedPlancha($value)
    {
        $this->plancha = $value;
        $this->questionOptions = [
            'A' => 'Plancha 1',
            'B' => 'Plancha 2',
            'C' => '',
            'D' => '',
            'E' => '',
            'F' => '',
        ];
    }

    public function updatedQuestionOptions($value,$field)
    {
        $this->blockFields=[];
        foreach ($this->questionOptions as $id => $option) {
            if(!$option){
                $this->blockFields[]='option'.$id;
            }
        }
        array_shift($this->blockFields);

        if ($this->questionType == 2) {

        }
    }
}
