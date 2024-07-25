<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

use App\Models\Result;
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

        $seconds=$request->mins*60+$request->secs;

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
                'seconds'=>$seconds,
                'type'=>$request->radioType
            ]);

            $this->fileController->getQuestionFolderPath($question->id,$question->title);

            //todo log create question
            session(['question_id'=>$question->id]);
            return redirect()->route('questions.show');
        } catch (\Throwable $th) {
            return redirect()->back()->withErrors([
                'error' => $th->getMessage(),
            ]);
        }
    }


}
