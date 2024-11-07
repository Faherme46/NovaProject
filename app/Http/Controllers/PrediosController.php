<?php

namespace App\Http\Controllers;

use App\Exports\PrediosExport;
use App\Http\Controllers\Controller;

use App\Models\Predio;
use Illuminate\Http\Request;

use App\Models\Control;
use Maatwebsite\Excel\Facades\Excel;

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
                if($predio->control){
                    $predio->control->setCoef();
                }
                return redirect()->route('consulta')->with('success','Se ha actualizado el predio');
            } catch (\Throwable $th) {
                return redirect()->route('consulta')->withErrors($th->getMessage());
            }
        }else{
            session('warning','El predio no fue encontrado');
            return redirect()->route('consulta');
        }
    }

    //todo cambiar asambleaName por route
    public function export($route=null){
        $asambleaName = cache('asamblea')['name'];
        $export = new PrediosExport();
        return Excel::store($export, $asambleaName . '/predios.xlsx', 'externalAsambleas');
    }



}
