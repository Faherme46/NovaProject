<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

use App\Models\Result;
use App\Models\Question;
use App\Http\Controllers\FileController;
use App\Models\Control;

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
            'title.required'=>'Se requiere un titulo a la pregunta',
            'radioType.required'=>'Se requiere un tipo de la pregunta',
            'radioCoef.required'=>'Se requiere el Coeficiente/Nominal de la pregunta'
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

        if ( $request->controls<=0 ) {
            return redirect()->back()->withErrors(['No se han registrado controles']);
        }

        $seconds=$request->mins*60+$request->secs;

        try {
            $question=Question::create([
                'title'=>strtoupper($request->title),
                'optionA'=>strtoupper($request->optionA),
                'optionB'=>strtoupper($request->optionB),
                'optionC'=>strtoupper($request->optionC),
                'optionD'=>strtoupper($request->optionD),
                'optionE'=>strtoupper($request->optionE),
                'optionF'=>strtoupper($request->optionF),
                'prefab'=>false,
                'coefGraph'=>($request->radioCoef),
                'quorum'=>Control::where('state',1)->sum('sum_coef_can'),
                'predios'=>Control::where('state',1)->sum('predios_vote'),
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
