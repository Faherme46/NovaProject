<?php

namespace App\Livewire\Elecciones;

use App\Models\Asamblea;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Informe extends Component
{
    public $asambleaVerified=true;
    public $asambleaa;
    public function mount(){
        $this->asambleaa = Asamblea::where('name',cache('asamblea')['name'])->first();
        
        foreach ($this->asambleaa->toArray() as $key => $value) {
            if (is_null($value)&&$key!='siganture'&&$key!='ordenDia') {

                $this->asambleaVerified = false; // Al menos un campo es null
                break;
            }
        }   
    }
    #[Layout("layout.full-page")]
    public function render()
    {
        return view('views.elecciones.informe');
    }
}
