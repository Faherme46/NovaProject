<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Component;

class ListUsers extends Component
{
    public $users;

    public $sortField = 'name'; // Campo por defecto para ordenar
    public $sortDirection = 'asc'; // Dirección de orden por defecto
    public $toDeleteId;

    public function mount(){
        $this->users=User::orderBy($this->sortField, $this->sortDirection)->get();
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

        $this->users = User::query()
            ->with('roles') // Cargar los roles asociados
            ->when($this->sortField == 'role', function ($query) {
                // Unir con la tabla de roles y ordenar por el nombre del rol
                $query->join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
                      ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
                      ->orderBy('roles.name', $this->sortDirection);
            }, function ($query) {
                // Si no es por rol, ordenar por el campo habitual
                $query->orderBy($this->sortField, $this->sortDirection);
            })
            ->get();
    }

    public function confirmDelete($userId){
        $this->toDeleteId=$userId;
        $this->dispatch('show-modal-delete');
    }
}
