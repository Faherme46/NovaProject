<?php

namespace App\Livewire;

use App\Models\Persona;
use Livewire\Component;
use Livewire\Attributes\On;

class Busqueda extends Component
{
    public $name;
    public $lastName;


    public $cedula='';
    public $asistente=null;



    public function clear(){
        $this->reset();
    }


    public function search(){

        $this->asistente=Persona::find($this->cedula);
        if($this->asistente){
            $this->name=$this->asistente->nombre;
            $this->lastName=$this->asistente->apellido;
            $this->dispatch('set-assistant',assistant:$this->asistente->toArray);
        }else{
            $this->dispatch('showModal',cedula:$this->cedula);
        }
    }


    public function render()
    {

        return view('livewire.busqueda');
    }
}
