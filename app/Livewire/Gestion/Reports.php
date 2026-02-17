<?php

namespace App\Livewire\Gestion;

use App\Models\Asamblea;
use Livewire\Component;
use Livewire\Attributes\Layout;

use App\Models\Control;

use App\Models\Question;

use Exception;
use Illuminate\Support\Facades\Http;
use Symfony\Component\CssSelector\Node\FunctionNode;
use Throwable;

class Reports extends Component
{
    public $report;

    public $asambleaa;

    //report variables
    public $values;
    public $anexos;
    public $questions;
    public $question;
    public $questionResultTxt;
    public $questionTitle;
    public $questionIsCoefChart;
    public $questionIsValid;
    public $ordenDia = '';
    public $allQuestionsVerified = false;
    public $asambleaVerified = true;
    public $viewGeneral = 1;
    public $devicesHid = array();
    public $device0 = 0;
    public $device1 = 0;

    public function mount()
    {

        $this->report = cache('report', null);

        $this->asambleaa = Asamblea::where('name', cache('asamblea')['name'])->first();
        if (!$this->asambleaa->registro) {
            cache(['prepared' => true]);
        }
        if ($this->asambleaa->controles > 400) {
            $this->updateDevices();
        }
        $this->defVariables();
        $this->setQuestionsVerified();
        $this->ordenDia = ($this->asambleaa->ordenDia) ? htmlspecialchars(implode("\n", json_decode($this->asambleaa->ordenDia))) : '';
    }

    #[Layout('layout.full-page')]
    public function render()
    {
        if ($this->viewGeneral) {
            return view('views.gestion.reports');
        } else {
            return view('views.gestion.questions-verify');
        }
    }

    public function defVariables()
    {

        $this->questions = Question::all();
        if (!$this->questions->isEmpty()) {
            $this->selectQuestion($this->questions->first()->id);
        }
    }



    public function selectQuestion($questionId)
    {
        $this->question = $this->questions->find($questionId);
        $this->questionResultTxt = $this->question->resultTxt;
        $this->questionIsCoefChart = (bool) $this->question->coefGraph;
        $this->questionIsValid = (bool) $this->question->isValid;
        $this->questionTitle = $this->question->title;
    }
    public function saveOrdenDia()
    {
        $list = ($this->ordenDia) ? explode("\n", $this->ordenDia) : [];
        $this->asambleaa->update(['ordenDia' => json_encode($list)]);
        session()->flash('success', 'Orden del dia guardado');
    }

    public function setView($value)
    {
        $this->viewGeneral = $value;
        if ($value == 1) {
            return redirect()->route('gestion.report');
        }
    }

    public function setQuestionsVerified()
    {
        $attributes = $this->asambleaa->getAttributes(); // Obtiene todos los atributos

        // Verifica si todos los campos tienen valor
        $this->asambleaVerified = true;
        foreach ($attributes as $key => $value) {
            if (is_null($value) && $key != 'siganture' && $key != 'ordenDia') {

                $this->asambleaVerified = false; // Al menos un campo es null
                break;
            }
        }
        if ($this->questions->isEmpty()) {
            return $this->allQuestionsVerified = false;
        }
        $nullResults = $this->questions->whereNull('resultTxt')->where('isValid')->whereNotIn('type', [1, 5])->whereNotIn('type', [5, 6]);
        $this->allQuestionsVerified = ($nullResults->isEmpty());
    }

    public function storeQuestion()
    {
        $this->question->title = strtoupper($this->questionTitle);
        $this->question->resultTxt = ($this->questionResultTxt) ? strtoupper($this->questionResultTxt) : null;
        $this->question->coefGraph = (bool) $this->questionIsCoefChart;
        $this->question->isValid = (bool) $this->questionIsValid;
        $this->question->save();

        session()->flash('success', 'Cambios Guardados');
    }

    public function verifyForm()
    {
        if ($this->questions->whereNull('resultTxt')->where('isValid')->whereNotIn('type', [1, 5])->isNotEmpty()) {

            return session()->flash('warning', 'Todas las preguntas deben tener resultado');
        }
        $url = env('APP_URL') . '/gestion/informes/Informe';

        $this->dispatch('submit-form-report');
        return redirect();
    }



    public function updateDevices()
    {
        try {
            $this->devicesHid = array();

            $response = Http::get('http://localhost:5000/start-detect');
            if ($response->status() == 200) {
                $devices = $response->json()['devices'];
                foreach ($devices as $device) {
                    $this->devicesHid[] = $device['serial'];
                }
                $numDevices = count($this->devicesHid);
                if (cache('hid_1') && cache('hid_0')) {
                    $this->device0 = cache('hid_0');
                    $this->device1 = cache('hid_1');
                }
                session()->flash('success', $numDevices . ' Dispositivos Encontrados');
            }
            return $response;
        } catch (Throwable $th) {
            session()->flash('error', 'Error al conectar con el servidor python: ' . $th->getMessage());
        }
    }

    public function updatedDevice1($value)
    {
        try {
            $listDevices = $this->devicesHid;
            if (!in_array($value, $listDevices)) {
                session()->flash('error', 'El dispositivo seleccionado no es válido');
                return;
            }
            $hid_0 = $listDevices[0] == $value ? $listDevices[1] : $listDevices[0];
            $this->device0 = $hid_0;

        } catch (Throwable $th) {
        }
    }
}
