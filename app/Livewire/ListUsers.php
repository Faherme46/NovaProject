<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

class ListUsers extends Component
{
    public $users;
    public $showImport=false;
    public $sortField = 'name'; // Campo por defecto para ordenar
    public $sortDirection = 'asc'; // Dirección de orden por defecto
    public $toDeleteId;
    public $editting=false;
    public $isAdmin;
    public function mount()
    {
        $this->users = User::whereNot('id',1)->whereNot('id',2)->orderBy($this->sortField, $this->sortDirection)->get();
        $serverIp = request()->server('SERVER_ADDR');
        $this->showImport= ($serverIp == '127.0.0.1');
        $this->isAdmin=(bool)Auth::user()->getRoleNames()[0]=="Admin";
    }
    #[Layout('layout.full-page')]
    public function render()
    {

        return view('views.gestion.create-users');
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            // Si se hace clic en el mismo campo, cambia la dirección
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            // Si es un campo diferente, establecer la dirección en ascendente
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }

        $this->users = User::query()->whereNot('id',1)->whereNot('id',2)->orderBy($this->sortField, $this->sortDirection)->get();
    }

    public function confirmDelete($userId)
    {
        $this->toDeleteId = $userId;
        $this->dispatch('show-modal-delete');
    }


    public function editUser($userId){
        $userToEdit=User::find($userId);
        $this->editting=true;

        $this->dispatch('set-edit-values',user:$userToEdit);
    }
}
