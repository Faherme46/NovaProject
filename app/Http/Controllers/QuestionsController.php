<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

use App\Models\Question;
use App\Http\Controllers\FileController;

class QuestionsController extends Controller
{

    public $fileController;
    public function __construct() {
        $this->fileController = new FileController;
    }
    public function createQuestion(Request $request){

        $request->validate([
            'radioType'=>'required',
            'radioCoef'=>'required',
            'title'=>'required'
        ],[
            'title.required'=>'Se requiere un titulo a la pregunta'
        ]);
        if ( $request->radioType != 1 &&
            !$request->filled('optionA') &&
            !$request->filled('optionB') &&
            !$request->filled('optionC') &&
            !$request->filled('optionD') &&
            !$request->filled('optionE') &&
            !$request->filled('optionF')
        ) {
            return redirect()->back()->withErrors([
                'options' => 'Al menos uno de los campos debe tener un valor.',
            ]);
        }
        try {
            $question=Question::create([
                'title'=>$request->title,
                'optionA'=>$request->optionA,
                'optionB'=>$request->optionB,
                'optionC'=>$request->optionC,
                'optionD'=>$request->optionD,
                'optionE'=>$request->optionE,
                'optionF'=>$request->optionF,
                'nominalPriotiry'=>$request->boolean('radioCoef'),
                'prefab'=>false,
                'type'=>$request->radioType
            ]);
            $questionName='Pregunta_'.$question->id-12;
            $this->fileController->createSubFolder($questionName,Cache::get('name_asamblea'));  
            return view('layout.presentation',compact('question'));
        } catch (\Throwable $th) {
            return redirect()->back()->withErrors([
                'error' => $th->getMessage(),
            ]);
        }


    }
}
