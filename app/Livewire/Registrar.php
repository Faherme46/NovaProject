<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Validate;

use App\Models\Control;
use App\Models\Persona;

use Livewire\Attributes\Layout;

class Registrar extends Component
{
    #controles
    public $controlIds;
    public $controlTurn='';
    #[Validate('required',message: 'Cedula requerida')]

    #asistente
    public $cedula='';
    public $asistente=null;
    public $name;
    public $lastName;
    public $tipoId;

    #poderdantes
    public $ccPoderdante='';
    public $poderdantes;
    public $poderdantesIDs=[];

    #predios
    public $prediosAvailable;

    public function mount(){
        $this->tipoId='CC';
        $this->prediosAvailable=collect();
        $this->poderdantes=collect();

    }

    #[Layout('layout.asistencia')]
    public function render()
    {
        if($this->asistente){
            $this->prediosAvailable=$this->asistente->predios;
            $this->getPrediosAvailable();
        }
        $this->getAvailableControls();
        return view('livewire.registrar');
    }

    public function getAvailableControls()
    {
        $availableControls = Control::whereDoesntHave('asignacion')->get();
        $this->controlIds = $availableControls->pluck('id')->toArray();

    }

    public function cleanData(){
        $this->reset(['asistente','cedula','name','lastName','ccPoderdante',
            'poderdantes','poderdantesIDs','prediosAvailable']);
        $this->mount();
    }




    public function search(){
        $this->validate();
        $this->asistente=Persona::find($this->cedula);

        if($this->asistente){
            $this->name=$this->asistente->nombre;

            $this->lastName=$this->asistente->apellido;

        }else{
            $this->dispatch('showModal');
        }

    }

    public function creaPersona(){
        try {
            $this->asistente = Persona::create([
                'tipo_id' => $this->tipoId,
                'id' => $this->cedula,
                'nombre' => $this->toFirstMayus($this->name),
                'apellido' => $this->toFirstMayus($this->lastName)
            ]);

            $this->dispatch('hideModal');
        } catch (\Exception $e) {
            dd('error'.$e->getMessage());
        }
    }

    public function toFirstMayus($string){
        return ucwords(strtolower($string));
    }

    public function addPoderdante(){
        $this->validate();
        // dd($arrayPropietarios);

        $poderdante = Persona::find($this->ccPoderdante);

        if ($poderdante) {

            if (in_array($this->ccPoderdante,$this->poderdantesIDs)) {
                return  back()->with('errorPropietarios', 'Ya fue añadido');
            }
            $this->poderdantesIDs[]=$this->ccPoderdante;
            $this->poderdantes=Persona::find(($this->poderdantesIDs));
            $this->reset('ccPoderdante');

        } else {
            return back()->with('errorPropietarios', 'No se encontro');
        }
    }


    public function dropAllPoderdantes(){
        $this->reset('ccPoderdante','poderdantes','poderdantesIDs');
        $this->mount();
    }

    public function dropPoderdante($poderdanteId){
        $this->poderdantesIDs = array_diff($this->poderdantesIDs, [$poderdanteId]);
        $this->poderdantes=Persona::find(($this->poderdantesIDs));
    }


    public function getPrediosAvailable(){
        if(!$this->poderdantes->isEmpty()){
            foreach ($this->poderdantes as $poderdante) {
                $this->prediosAvailable = $this->prediosAvailable->merge($poderdante->predios);

            }
        }
    }


}
