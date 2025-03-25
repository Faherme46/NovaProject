<?php

namespace App\Livewire\Votacion;

use App\Exports\StatesExport;
use App\Imports\VotesImport;
use App\Models\Control;
use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Models\Question;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ShowVotacion extends Component
{
    public $questions;
    public $question;
    
    public $sizeTitle = 1.5;
    public $inCoefResult =true;
    public $asambleaName;
    public $resultToUse=[];
    public function mount()
    {
        $this->asambleaName=cache('asamblea')['name'];
        $this->questions = Question::all();
        if ($this->questions->isNotEmpty()) {
            $this->selectQuestion($this->questions->first()->id);
        }


    }
    #[Layout('layout.full-page')]
    public function render()
    {

        return view('views.votacion.show-votacion');
    }
    public function selectQuestion($questionId)
    {
        $this->question = $this->questions->find($questionId);
        $this->inCoefResult=$this->question->coefGraph;
        
        $this->resultToUse= ($this->inCoefResult)?$this->question->resultCoef:$this->question->resultNom;
        
           
    }
    public function calculatePlazas()
    {
        $total = 0;
        $options=[
            'optionA',
            'optionB',
            'optionC',
            'optionD',
            'optionE',
            'optionF',
        ];
        
        foreach ($options as $op) {
            if ($this->question[$op] != 'EN BLANCO') {
                $total += $this->resultToUse[$op];
            }
        }
        
        $umbral = $total / $this->question->plancha->plazas;
        
        if ($total <= 0) {
            foreach ($options as $option) {
                if ($this->question[$option] !== 'EN BLANCO') {
                    $this->question->plancha[$option] = 0;
                }
            }
            
        } else {
            $sumTotal=0;
            foreach ($options as $option) {
                if ($this->question[$option] !== 'EN BLANCO') {
                    $plazas=floor($this->resultToUse[$option] / $umbral);
                    $this->question->plancha[$option] = $plazas;
                    $sumTotal+=$plazas;
                } else {
                    $this->question->plancha[$option] = 0;
                }
            }


            // Calcular residuos y asignar curules adicionales
            $residuos = [];
            $plazasRestantes = $this->question->plancha->plazas - $sumTotal;

            foreach ($options as $option) {
                if ($this->question[$option] !== 'EN BLANCO') {
                    $residuos[$option] = $this->resultToUse[$option] - $umbral * $this->question->plancha[$option];
                }
            }
            // Ordenar opciones por residuos
            arsort($residuos);
            foreach (array_keys($residuos) as $option) {
                if ($plazasRestantes > 0) {
                    $this->question->plancha[$option] += 1;
                    $plazasRestantes--;
                }
            }
        }
        // $this->valuesPlanchas['total'] = $total;
        $this->question->plancha->umbral = round($umbral, 4);
        $this->question->plancha->save();

        return redirect()->route('votacion.show')->with('success','Se ha regenerado la plancha');
    }


}
