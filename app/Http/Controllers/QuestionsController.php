<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Question;

class QuestionsController extends Controller
{
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
        return back();
    }
}
