<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use App\Models\QuestionsPrefab;

abstract class Controller
{
    public function createPrefabQuestion(Request $request)
    {
        $messages = [
            '*.required' => 'El campo :attribute no puede estar vacio'
        ];
        $request->validate([
            'title' => 'required',
            'optionA' => 'required',
            'optionB' => 'required',
            'optionC' => 'required',
            'optionD' => 'required',
            'optionE' => 'required',
            'optionF' => 'required',
            'type' => 'required'
        ], $messages);
        $question=QuestionsPrefab::create($request->all);
    }

    public function updateQuestion(Request $request){
        $messages = [
            '*.required' => 'El campo :attribute no puede estar vacio'
        ];
        $request->validate([
            'title' => 'required',
            'optionA' => 'required',
            'optionB' => 'required',
            'optionC' => 'required',
            'optionD' => 'required',
            'optionE' => 'required',
            'optionF' => 'required',
            'type' => 'required',
            'id'
        ], $messages);
    }
}
