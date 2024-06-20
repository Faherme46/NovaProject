<?php

namespace App\Livewire;

use Livewire\Component;

use App\Models\Predio;
class AllPredios extends Component
{
    public $searchId='';
    public $distincts;
    public $allPredios;
    public function mount(){

        $this->distincts=[
            'descriptor1'=>Predio::distinct()->pluck('descriptor1'),
            'descriptor2'=>Predio::distinct()->pluck('descriptor2'),
            'numeral1'=>Predio::distinct()->pluck('numeral1'),
            'numeral2'=>Predio::distinct()->pluck('numeral2')
        ];
    }

    public function flash(){
        dd('hello');
        session()->flash('status', 'Post successfully updated.');

    }

    public function render()
    {
        $query = Predio::query();

        if ($this->searchId) {
            $query->where('cc_propietario', 'like', '%' . $this->cc_propietario . '%');
        }
        $this->allPredios = $query->get();


        return view('livewire.all-predios')->with([
            'allPredios'=>$this->allPredios,
            'distincts'=>$this->distincts]);

    }



}
