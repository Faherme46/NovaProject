<?php

namespace App\Http\Controllers;

use App\Exports\ControlesExport;
use App\Exports\ControlesWithRegistro;
use App\Exports\PrediosControlExport;
use App\Exports\PrediosExport;
use App\Http\Controllers\Controller;

use App\Models\Predio;
use Illuminate\Http\Request;

use App\Models\Control;
use App\Models\Persona;
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
            'descriptor2' => ['required'],
            'numeral2' => ['required'],
        ], $messages);



        if (cache('asamblea')['registro']) {
            $request->validate(['propietario' => ['required']], ['propietario.required' => 'El id del propietario es requerido']);
            $propietario=Persona::find($request->input('propietario'));
            if(!$propietario){
                return back()->with('error','El propietario no esta registrado en la base de datos');
            }
        }

        $coef = floatval(str_replace(',', '.', $request->coef));

        try {
            $predio = Predio::create([
                'coeficiente' => $coef,
                'votos' => $request->votos,
                'descriptor1' => ($request->descriptor1)?$request->descriptor1:'',
                'descriptor2' => ($request->descriptor2)?$request->descriptor2:'',
                'numeral1' => ($request->numeral1)?$request->numeral1:'',
                'numeral2' => $request->numeral2,
                'vota' => (bool) $request->vota
            ]);
            if ($predio->control) {
                $predio->control->setCoef();
            }
            \Illuminate\Support\Facades\Log::channel('custom')->info('Se ha creado el predio', ['predio' => $predio]);
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
                \Illuminate\Support\Facades\Log::channel('custom')->info('Se ha actualizado el predio', ['predio' => $predio]);
                return redirect()->route('consulta')->with('success', 'Se ha actualizado el predio');
            } catch (\Throwable $th) {
                return redirect()->route('consulta')->withErrors($th->getMessage());
            }
        } else {

            return redirect()->route('consulta')->with('warning', 'El predio no fue encontrado');
        }
    }

    
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

    public function repairPredios()
    {
        
        $asamblea =  cache('asamblea');

        if (!$asamblea) {
            return redirect()->route('home')->with('error', 'No existe una asamblea');
        }
        $controles = Control::all();
        foreach ($controles as $control) {

            if (strtotime($asamblea->h_inicio) < strtotime($control->h_entrega)) {
                $control->predios()->update(['quorum_start' => false]);
            } else {
                $control->predios()->update(['quorum_start' => true]);
            };
            if (strtotime($asamblea->h_fin) < strtotime($control->h_recibe)) {
                $control->predios()->update(['quorum_end' => true]);
            } else {
                $control->predios()->update(['quorum_end' => false]);
            };
        }

        
        $predios = Predio::whereNotNull('control_id')->get();
        try {
            foreach ($predios as $predio) {
                $ids = $predio->personas->pluck('id')->toArray();

                if (!in_array($predio->control->cc_asistente, $ids)) {
                    $predio->update(['cc_apoderado' => $predio->control->cc_asistente]);
                } else {
                    $predio->update(['cc_apoderado' => null]);
                }
            }
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
        cache(['prepared' => true]);
        return back()->with('success', 'Se han preparado los predios');
    }
    public function exportPrediosControles(){
        try {
            $asambleaName = cache('asamblea')['name'];
            $prediosControlExport= new PrediosControlExport();
            Excel::store($prediosControlExport, $asambleaName . '/Tablas/predios_controles.xlsx', 'externalAsambleas');
            return back()->with('success', 'Archivo exportado correctamente');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
        
    }
}
