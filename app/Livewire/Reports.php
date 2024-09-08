<?php

namespace App\Livewire;

use App\Models\Asamblea;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Carbon\Carbon;
use App\Jobs\GeneratePdf;

use App\Models\Control;
use App\Models\Predio;
use App\Models\Question;
use Illuminate\Support\Facades\Http;

class Reports extends Component
{
    public $report;
    public $prediosRegistered;
    public $prediosVote;
    public $quorumRegistered;
    public $quorumVote;
    public $allControls;
    public $asamblea;

    //report variables
    public $values;
    public $anexos;
    public $questions;
    public $question;
    public $questionResultTxt;
    public $questionIsCoefChart;
    public $questionIsValid;
    public $ordenDia='';
    public $allQuestionsVerified = false;
    public $viewGeneral = 0;

    public function mount()
    {
        $this->allControls = Control::whereNotIn('state', [4, 3])->get();

        $this->prediosRegistered = $this->allControls->sum(function ($control) {
            return $control->predios->count();
        });
        $this->prediosVote = $this->allControls->sum(function ($control) {
            return $control->predios_vote;
        });

        $this->quorumRegistered = $this->allControls->sum(function ($control) {
            return $control->sum_coef;
        });
        $this->quorumVote = $this->allControls->sum(function ($control) {
            return $control->sum_coef_can;
        });
        $this->report = cache('report', null);

        $this->asamblea = Asamblea::find(cache('id_asamblea'));

        $this->defVariables();
        $this->setQuestionsVerified();
        $this->ordenDia=($this->asamblea->ordenDia)?htmlspecialchars(implode("\n",json_decode($this->asamblea->ordenDia))):'';

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

        $this->questions = Question::where('prefab',false)->whereNot('type',6)->get();
        if (!$this->questions->isEmpty()) {
            $this->selectQuestion($this->questions->first()->id);
        }

        if ($this->asamblea->registro) {
            $this->defReportVariablesR();
        } else {
            $this->defReportVariablesV();
        }
    }

    public function defReportVariablesR()
    {

        $this->anexos = [
            'Listado de Personas Citadas a la Asamblea  ',
            'Asistencia Para Quorum',
            'Listado Total de Participantes en la Asamblea',
            'Orden del Día',
            'Informe Resultado de Votaciones'
        ];
    }
    public function defReportVariablesV()
    {


        $this->anexos = [
            'Listado de Personas Citadas a la Asamblea ',
            'Asistencia Para Quorum',
            'Listado Total de Participantes en la Asamblea',
            'Orden del Día',
            'Informe Resultado de Votaciones'
        ];
    }

    public function selectQuestion($questionId)
    {
        $this->question = $this->questions->find($questionId);
        $this->questionResultTxt = $this->question->resultTxt;
        $this->questionIsCoefChart=(bool) $this->question->coefGraph;
        $this->questionIsValid=(bool) $this->question->isValid;

    }
    public function saveOrdenDia()
    {
        $list=($this->ordenDia)?explode("\n", $this->ordenDia):[];
        $this->asamblea->update(['ordenDia'=>json_encode($list)]);

    }

    public function setView($value)
    {
        $this->viewGeneral = $value;
    }

    public function setQuestionsVerified()
    {
        if ($this->questions->isEmpty()) {
            return $this->allQuestionsVerified = false;
        }
        $nullResults = $this->questions->whereNull('resultTxt')->whereNotIn('type', [1, 7]);
        if ($nullResults->isEmpty()) {
            $this->allQuestionsVerified = true;
        } else {
            $this->allQuestionsVerified = false;
        }
    }

    public function setResult()
    {
        $this->question->resultTxt = ($this->questionResultTxt)?strtoupper($this->questionResultTxt):null;
        $this->question->coefGraph= (bool) $this->questionIsCoefChart;
        $this->question->isValid= (bool) $this->questionIsValid;
        $this->question->save();

        session()->flash('success1', 'Cambios Guardados');
    }

    public function verifyForm(){
        if ($this->questions->whereNull('resultTxt')->isNotEmpty()) {

            return session()->flash('error1','Todas las preguntas deben tener resultado');
        }
        $url=env('APP_URL').'/gestion/informes/Informe';

        $this->dispatch('submit-form-report');
        return redirect();
    }

}
