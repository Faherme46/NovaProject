<?php

namespace App\Livewire;

use App\Models\General;
use App\Models\Question;
use App\Models\QuestionType;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Setup extends Component
{
    public $themeId;
    public $themes =
    [
        [
            'color' => 'Naranja',
            'rgb' => '#fd7e14',
            'id' => '1'

        ],
        [
            'color' => 'Cian',
            'rgb' => '#0dcaf0',
            'id' => '2'

        ],
        [
            'color' => 'Turquesa',
            'rgb' => '#20c997',
            'id' => '3'

        ],
        [
            'color' => 'Rosado',
            'rgb' => '#d63384',
            'id' => '4'

        ],
        [
            'color' => 'Purpura',
            'rgb' => '#6610f2',
            'id' => '5'

        ],
        [
            'color' => 'Azul',
            'rgb' => '#0d6efd',
            'id' => '6'

        ],
        [
            'color' => 'Vinotinto',
            'rgb' => '#842029',
            'id' => '7'

        ],


    ];

    public $selectedQuestion = null;
    public $questionsPrefab;
    public $questionTypes;

    public $tab = 1;

    public $titles = [
        1 => 'Tema de Color',
        2 => 'Preguntas Prediseñadas'
    ];

    public function mount()
    {
        $this->themeId = cache('themeId', 5);
        $this->questionsPrefab = Question::where('prefab', true)->get();

        $this->questionTypes=QuestionType::all();
    }

    #[Layout('layout.full-page')]
    public function render()
    {
        return view(view: 'views.setup.setup');
    }

    public function updatedThemeId()
    {
        cache(['themeId' => $this->themeId]);
        return redirect()->route('setup.main');
    }
    public function setTab($id)
    {
        $this->tab = $id;
    }

    public function setQuestion($questionId)
    {
        $this->selectedQuestion = Question::find($questionId);
        $this->dispatch('$refresh');
        $this->dispatch('set-question',question:$this->selectedQuestion);
    }

    public function newQuestion(){
        $this->selectedQuestion=null;
        $this->dispatch('new-question');
    }
    public function deleteQuestion(){
        $this->selectedQuestion->delete();
        $this->selectedQuestion=null;
        $this->dispatch('$refresh');
        $this->dispatch('close-delete-modal');
    }
}
