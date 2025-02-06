<?php

namespace App\Livewire\Registro;


use App\Models\Persona;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Signs extends Component
{

    public $count = 0;
    public $tratamientoPublico;
    public $persona;
    public $tratamiento=false;
    public $enabled=false;

    public function mount(){
        $this->getPersona();
        //todo proof
    }

    #[Layout(('layout.presentation'))]
    public function render()
    {

        return view('views.registro.signs');

    }

    public function goBack(){
        return redirect(route('home'));
    }
    public function goTratamiento(){
        $this->tratamiento=true;
    }

    public function getPersona(){
        $this->count++;
        $this->persona = Persona::where('registered',true)->where('user_id',auth()->id())
        ->whereDoesntHave('signature')->orderBy('updated_at','desc')->first();
    }

    public function changed(){
        $this->enabled=true;
    }

    public function setTratamiento(){
        if($this->tratamientoPublico){
            $this->persona->controls()->update(['t_publico' => 1]);
        }else{
            $this->persona->controls()->update(['t_publico' =>0]);
        }


        $this->dispatch('submit-form');
    }



}
