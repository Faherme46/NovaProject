<?php

namespace App\Livewire\Registro;

use App\Http\Controllers\FileController;
use App\Models\Persona;
use App\Models\Signature;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;

class Signing extends Component
{
    #[Url]
    public $persona_id=0;
    #[Url]
    public $tratamiento=0;
    public $fullscreen=false;
    protected $listeners=['uploadCanvasImage'=>'getImage'];

    public function mount(){

    }

    #[Layout("layout.presentation")]
    public function render()
    {
        return view('views.registro.signing');
    }

    public function getImage($image){
        $asamblea=cache('asamblea');

        $path=$asamblea['name']."/Firmas/".$this->persona_id.'.png';
        $fileController= new FileController;
        $validImage = $this->dataUrlToImage($image  );
        $fileController->exportPdf($path,$validImage);
        $sign=Signature::create([
            'signature'=>$image,
            'persona_id'=>$this->persona_id
        ]);


        session()->flash('success','Firma Guardada Correctamente');
        return redirect()->route('asistencia.signs');
    }
    private function dataUrlToImage($dataUrl)
    {
        // Separar el tipo de datos y el contenido de la data URL
        list($type, $data) = explode(';', $dataUrl);
        list(, $data) = explode(',', $data);

        // Decodificar la cadena base64 a datos binarios
        return base64_decode($data);
    }
    public function goBack(){
        return redirect()->route('asistencia.signs');
    }

    public function toSign(){
        $this->fullscreen=true;
    }
}
