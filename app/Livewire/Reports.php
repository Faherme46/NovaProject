<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;

use App\Models\Control;
class Reports extends Component
{
    public $report;
    public $prediosRegistered;

    public $prediosVote;
    public $quorumRegistered;
    public $quorumVote;

    public $allControls;

    public function mount(){
        $this->allControls = Control::whereNotIn('state',[4,3])->get();

        $this->prediosRegistered=$this->allControls->sum(function ($control) {
            return $control->predios->count();
        });
        $this->prediosVote=$this->allControls->sum(function ($control) {
            return $control->predios_vote;
        });

        $this->quorumRegistered=$this->allControls->sum(function ($control) {
            return $control->sum_coef;
        });
        $this->quorumVote=$this->allControls->sum(function ($control) {
            return $control->sum_coef_can;
        });
        $this->report = cache('report',null);
    }

    #[Layout('layout.full-page')]
    public function render()
    {
        return view('views.gestion.reports');
    }
}
