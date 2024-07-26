<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Models\Predio;
use Illuminate\Http\Request;

use App\Models\Control;
class PrediosController extends Controller
{

    protected $sessionController;


    public function updatePredio(Request $request){
        $messages = [
            'coef.required' => 'El campo coeficiente es obligatorio.',
            'id.required' => 'El campo id es obligatorio.',
        ];

        $request->validate([
            'coef' => ['required'],
            'id' => ['required'],
        ], $messages);


        $predio=Predio::find($request->id);
        if ($predio) {
            try {
                $predio->update([
                    'coeficiente'=>$request->coef,
                    'vota'=>$request->boolean('voto')
                ]);
                $predio->control[0]->setCoef();
                return redirect()->route('consulta')->with('success1','Se ha actualizado el predio');
            } catch (\Throwable $th) {
                return redirect()->route('consulta')->withErrors($th->getMessage());
            }
        }else{
            session('warning1','El predio no fue encontrado');
            return redirect()->route('consulta');
        }
    }



}
