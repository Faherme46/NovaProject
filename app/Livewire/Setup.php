<?php

namespace App\Livewire;

use App\Models\General;
use App\Models\Question;
use App\Models\QuestionsPrefab;
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

    public $selectedQuestion =
    [
        'id' => 0,
        'title'=>'',
        'optionA'=>'',
        'optionB'=>'',
        'optionC'=>'',
        'optionD'=>'',
        'optionE'=>'',
        'optionF'=>'',
        'type'=>2
    ];
    public $questionsPrefab;
    public $questionTypes;

    public $tab = 2;

    public $titles = [
        1 => 'Tema de Color',
        2 => 'Preguntas Prediseñadas'
    ];

    public $options=['optionA', 'optionB', 'optionC', 'optionD', 'optionE', 'optionF', ];

    public function mount()
    {
        $this->themeId = cache('themeId', 5);
        $this->questionsPrefab = QuestionsPrefab::all();

        $this->questionTypes = QuestionType::all();
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
        $question = QuestionsPrefab::find($questionId);
        $this->selectedQuestion=[
            'id' => $question->id,
            'title'=>$question->title,
            'optionA'=>$question->optionA,
            'optionB'=>$question->optionB,
            'optionC'=>$question->optionC,
            'optionD'=>$question->optionD,
            'optionE'=>$question->optionE,
            'optionF'=>$question->optionF,
            'type'=>$question->type
        ];
        $this->dispatch('$refresh');
        $this->dispatch('set-question', question: $this->selectedQuestion);
    }

    public function newQuestion()
    {
        $this->reset('selectedQuestion');


    }
    public function deleteQuestion()
    {
        $idQuestion=$this->selectedQuestion['id'];
        QuestionsPrefab::destroy($idQuestion);
        $this->reset('selectedQuestion');
        $this->dispatch('$refresh');
        $this->dispatch('close-delete-modal');
        session()->flash('success','Prtgunta Eliminada');
    }

    public function changeType($type){

        $types=[
            '1'=>[],
            '2'=>[],
            '3'=>['optionD'=>'APROBADO','optionE'=>'APROBADO'],
            '4'=>['optionA'=>'Si','optionB'=>'NO'],
            '5'=>['optionA'=>'VOTO PUBLICO','optionB'=>'VOTO PRIVADO']
        ];
        foreach ($this->options as $op) {
            $this->selectedQuestion[$op]='';
        }
        foreach ($types[$type] as $key=> $option) {
            $this->selectedQuestion[$key]=$option;
        }
        $this->selectedQuestion['type']=$type;
    }

    public function storeQuestion(){
        if(!$this->selectedQuestion['title']){
            $this->addError('error','La pregunta debe tener un titulo');
        };
        $this->selectedQuestion['prefab']=true;
        $this->selectedQuestion['isValid']=false;
        $this->selectedQuestion['coefGraph']=1;
        try {
            if($this->selectedQuestion['id']!=0){
                $id=$this->selectedQuestion['id'];
                array_diff($this->selectedQuestion, array('id'));
                QuestionsPrefab::where('id',$id)->update($this->selectedQuestion);
                session()->flash('success','Pregunta actualizada con exito');
            }else{
                $question=QuestionsPrefab::create($this->selectedQuestion);
            if($question){
                session()->flash('success','Pregunta creada con exito');
            }
            }

        } catch (\Throwable $th) {
            $this->addError('error','Error al crear la pregunta: '.$th->getMessage());
        }
        $this->reset('selectedQuestion');
    }


}
