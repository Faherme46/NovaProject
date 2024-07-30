<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;

class ListUsers extends Component
{
    public $users;
    public $height100=false;

    public function mount($height100){
        $this->users=User::all();
        $this->height100=$height100;
    }
    public function render()
    {
        return view('views.gestion.list-users');
    }
}
