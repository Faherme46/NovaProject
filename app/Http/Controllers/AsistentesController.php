<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;


use App\Models\Persona;
use App\Models\Predio;
use App\Models\Asignacion;
use App\Models\Control;

use function PHPSTORM_META\type;

class AsistentesController extends Controller
{
    protected $selectedAll;

    public function index()
    {
        if(Cache::get('asambleaOn',false)){
            $persona = session('persona',null);
            $asignacionesAll=Asignacion::all();
            $availableControls=Control::whereDoesntHave('asignacion')->get();
            $controlTurn=session('lastControl',0)+1;//control que deberia ser el siguiente
            $controlIds=$availableControls->pluck('id')->toArray();
            if(!in_array($controlTurn,$controlIds)){
                $control=current($availableControls);//si el control ya fue asignado devuelve el siguiente en la lista
            }
            if($persona){
                $asignaciones=$persona->asignaciones;
                $prediosAvailable=$persona->predios()->whereDoesntHave('asignacion')->get();
                return view('registro', compact('persona', 'prediosAvailable','asignaciones','asignacionesAll','controlIds','controlTurn'));
            }


            return view('registro', compact('persona','asignacionesAll','controlIds','controlTurn'));
        }else{
            return view('registro');
        }

    }

    //metodo que se ejecuta si el usuario ya tiene una asignacion


    public function anadirPredio(Request $request)
    {
        // Obtener la lista actual de predios
        $predios = session('predios',[]);
        $predio=$request->predioId;
        // Verificar si el ID del predio ya está en la lista
        if (in_array($predio,$predios)) {
            // Si está en la lista, eliminarlo
            $predios = array_diff($predios, [$predio]);
        } else {
            // Si no está en la lista, añadirlo
            $predios[]=$predio;
        }
        // Guardar la lista actualizada en la sesión
        session(['predios' => $predios]);
        return redirect()->route('asistentes.index');
    }

    public function buscar(Request $request)
    {
        session()->forget(['persona', 'predios']);
        $persona = Persona::find($request->cedula);

        if(!$persona){
            return redirect()->route('asistentes.index')->withErrors('No se encontro');
        }
        session(['persona' => $persona]);
        $this->allPrediosCheck();

        return redirect()->route('asistentes.index');

    }
    public function allPrediosCheck()
    {
        $persona = session('persona',null);
        if(!$persona){
            return redirect()->route('asistentes.index')->withErrors('Debe ingresar una cédula');
        }
        session(['predios'=>$persona->predios->pluck('id')->toArray()]);
        return redirect()->route('asistentes.index');
    }



    public function allPrediosUncheck()
    {
        session(['predios' => []]);
        return redirect()->route('asistentes.index');
    }

    public function asignar(Request $request)
    {
        $cc_asistente = $request->input('cc_asistente');
        $idPredios=$request->input('prediosSelect');
        $controlId = $request->input('control');

        if(is_null($idPredios)){
           return back()->withErrors('No se han seleccionado predios');
        }
        $predios=explode(",", $idPredios);

        $control=Control::find($controlId);
        try{
            $asignacion=$control->asignacion()->create([
            'cc_asistente' => $cc_asistente,
            'sum_coef' =>$request->sum_coef,
            'estado' => 'activo'
            ]);
        }catch(QueryException $e){
            if ($e->errorInfo[1] == 1062) {
                // Manejar la excepción específica de "Duplicate entry"
                return redirect()->route('asistentes.index')->withErrors('El número de control ya está asignado. Por favor, elige otro.');
            }else{
                return redirect()->route('asistentes.index')->withErrors($e->getMessage());
            }
        }catch(\Exception $e){
            dd($e->getMessage());
            return redirect()->route('asistentes.index')->withErrors($e->getMessage());
        }

        $asignacion->predios()->attach($predios);
        session(['lastControl'=>$controlId]);
        session()->forget(['persona']);
        $persona=Persona::find($cc_asistente);
        $personaPredios=$persona->predios->pluck('id')->toArray();

        if($personaPredios==$predios){
            return redirect()->route('asistentes.index')->with('success', 'Predios asignados con éxito');
        }else{
            return redirect()->route('asistentes.index')->with('success', 'Predios asignados con éxito')->with('persona',$persona);
        };


    }

    public function limpiar(){
        session()->forget(['persona']);
        return redirect()->route('asistentes.index');
    }
}
