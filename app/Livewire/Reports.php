<?php

namespace App\Livewire;

use App\Models\Asamblea;
use Livewire\Component;
use Livewire\Attributes\Layout;

use App\Models\Control;
use App\Models\Question;
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
    }

    #[Layout('layout.full-page')]
    public function render()
    {
        return view('views.gestion.reports');
    }

    public function defVariables()
    {
        $this->values = [
            'cliente' => $this->asamblea->folder,
            'referencia' => 'Asamblea Ordinaria',
            'tipo' => 'Presencial',
            'direccion' => ($this->asamblea->lugar) ? $this->asamblea->lugar : 'No aplica',
            'fecha' => $this->asamblea->fecha,
            'hora' => $this->asamblea->hora,
            'h_inicio' =>date('h:i a', strtotime($this->asamblea->h_inicio)),
            'h_fin' => date('h:i a',strtotime($this->asamblea->h_cierre)),
            'footer1' => 'Los datos utilizados por TECNOVIS para la elaboración de los Anexos
                relacionados en este informe (incluye los cálculos para las votaciones),
                y que comprende la lista de delegados, tiene como base la información suministrada
                por la Administración de' . $this->asamblea->folder . ' a TECNOVIS, para el desarrollo de esta Asamblea.',
            'orden' => "1. Registro\n2. Verificación del quorum\n3. Aprobación reglamento de asamblea\n4. Elección presidente y secretario\n5. Aprobación orden del día\n6. Informe de comisión verificadora\n7. Elección comisión verificadora\n8. Presentación y aprobación proyecto presupuesto periodo 2024\n9. Aprobación de la cuota de expensas comunes\n10. Nombramiento de comité de convivencia\n11. Fortalecimiento del consejo de administración\n12. Informe administrativo\n13. Presentación de estados financieros\n14. Informe revisor fiscal y aprobación estados financieros\n15. Elección revisora fiscal\n16. Proposiciones y varios\n17. Cierre"
        ];

        $this->questions=Question::where('id','>',12)->get();
        if(!$this->questions->isEmpty()){

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

    public function selectQuestion($questionId){
        $this->question=$this->questions->find($questionId);
    }


}
