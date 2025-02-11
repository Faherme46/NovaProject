<?php

namespace App\Livewire\Gestion;

use Livewire\Component;

use Livewire\Attributes\Layout;

use App\Models\Control;
use Carbon\Carbon;

class Entregar extends Component
{
    public $controls;
    public $inModal=false;
    public $controlId;
    public $colors=[
        1=>'btn-primary', //activo
        2=>'btn-info', //ausente
        4=>'btn-black', //sin asignar
        5=>'btn-danger' //entregado
    ];

    public function mount(){
        $this->controls=Control::all();

    }

    #[Layout('layout.full-page')]
    public function render()
    {
        return view('views.gestion.entregar');
    }

    public function change($value){

        $control=Control::find($this->controlId);

        $control->changeState($value);
        $this->inModal=false;
        \Illuminate\Support\Facades\Log::channel('custom')->info('Se entrega un control', ['id'=>$control->id,'estado'=>$value]);
        return redirect()->to('/entregar');
    }


    public function confirm($id){
        if(!$this->inModal){
            $this->controlId=$id;
            $this->dispatch('showModalConfirm');
            $this->inModal=true;
        }

    }

    public function close(){
        $this->reset('controlId','inModal');
    }

    public function entregarAll(){
        $now=Carbon::now(new \DateTimeZone('America/Bogota'));
        \Illuminate\Support\Facades\Log::channel('custom')->info('Se entrega un control', ['Se entregan todos los controles']);
        $this->controls=Control::whereHas('predios')->update(['h_recibe'=>$now,'state'==5]);
    }
}
