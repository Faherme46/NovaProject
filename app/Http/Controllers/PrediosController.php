<?php

namespace App\Http\Controllers;

use App\Exports\ControlesExport;
use App\Exports\ControlesWithRegistro;
use App\Exports\PrediosExport;
use App\Http\Controllers\Controller;

use App\Models\Predio;
use Illuminate\Http\Request;

use App\Models\Control;
use Maatwebsite\Excel\Facades\Excel;

class PrediosController extends Controller
{

    protected $sessionController;


    public function createPredio(Request $request)
    {
        $messages = [
            'coef.required' => 'El campo coeficiente es obligatorio.',
            'votos.required' => 'El campo votos es obligatorio.',
            'descriptor2.required' => 'El campo descriptor 2 es obligatorio',
            'numeral2.required' => 'El campo numeral 2 es obligatorio',
        ];

        $request->validate([
            'coef' => ['required'],
            'votos' => ['required'],
            'descriptor1' => ['required'],
            'descriptor2' => ['required'],
            'numeral1' => ['required'],
            'numeral2' => ['required'],
        ], $messages);



        if (cache('asamblea')['registro']) {
            $request->validate(['propietario' => ['required']], ['propietario.required' => 'El id del propietario es requerido']);
        }

        $coef=floatval(str_replace(',', '.', $request->coef));

        try {
            $predio = Predio::create([
                'coeficiente' => $coef,
                'votos' => $request->votos,
                'descriptor1' => $request->descriptor1,
                'descriptor2' => $request->descriptor2,
                'numeral1' => $request->numeral1,
                'numeral2' => $request->numeral2,
                'vota'=>(bool) $request->vota
            ]);
            if ($predio->control) {
                $predio->control->setCoef();
            }
            return redirect()->route('consulta')->with('success', 'Se ha Creado el predio');
        } catch (\Throwable $th) {
            return redirect()->route('consulta')->withErrors($th->getMessage());
        }
    }


    public function updatePredio(Request $request)
    {

        $messages = [
            'coef.required' => 'El campo coeficiente es obligatorio.',
            'votos.required' => 'El campo votos es obligatorio.',
            'id.required' => 'El campo id es obligatorio.',
        ];

        $request->validate([
            'coef' => ['required'],
            'votos' => ['required'],
            'id' => ['required'],
        ], $messages);

        $predio = Predio::find($request->id);

        if ($predio) {

            try {
                $predio->update([
                    'coeficiente' => $request->coef,
                    'votos' => $request->votos,
                    'vota' => $request->boolean('voto')
                ]);
                if ($predio->control) {
                    $predio->control->setCoef();
                }
                return redirect()->route('consulta')->with('success', 'Se ha actualizado el predio');
            } catch (\Throwable $th) {
                return redirect()->route('consulta')->withErrors($th->getMessage());
            }
        } else {

            return redirect()->route('consulta')->with('warning', 'El predio no fue encontrado');
        }
    }

    //todo cambiar asambleaName por route
    public function export($route = null)
    {
        $asambleaName = cache('asamblea')['name'];
        $export = new PrediosExport();
        return Excel::store($export, $asambleaName . '/Tablas/predios.xlsx', 'externalAsambleas');
    }

    public function controlExport($route = null)
    {
        $asambleaName = cache('asamblea')['name'];
        $export = new ControlesExport();
        return Excel::store($export, $asambleaName . '/Tablas/controles.xlsx', 'externalAsambleas');
    }
    public function controlWithRegistroExport($route = null)
    {
        $asambleaName = cache('asamblea')['name'];
        $export = new ControlesWithRegistro();
        return Excel::store($export, $asambleaName . '/Tablas/controles.xlsx', 'externalAsambleas');
    }
}
