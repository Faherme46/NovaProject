<?php

namespace App\Livewire\Elecciones;

use App\Models\Session;
use App\Models\Terminal;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Terminales extends Component
{

    public $terminales;
    public $terminal;
    public function mount()
    {
        $this->terminales = Terminal::all();
        foreach ($this->terminales as $terminal) {

            $session=DB::table('sessions')->where('user_id', $terminal->user_id);
            if($session){
                $terminal->delete();
            }
        }
    }
    #[Layout("layout.full-page")]
    public function render()
    {
        return view('views.elecciones.terminales');
    }

    public function selectTerminalToDelete($id)
    {
        $this->terminal = $this->terminales->find($id);
        $this->dispatch('show-delete-modal');
    }
    public function deleteSession()
    {
        $this->dispatch('hide-delete-modal');
        $this->terminal->delete();

        DB::table('sessions')->where('user_id', $this->terminal->user_id)->delete();
        return redirect()->route('elecciones.terminales')->with('success','Sesión cerrada exitosamente');
    }
}
