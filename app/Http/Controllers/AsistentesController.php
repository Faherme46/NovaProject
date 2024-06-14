<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;


use App\Models\Persona;
use App\Models\Predio;
use App\Models\Asignacion;

class AsistentesController extends Controller
{
    protected $selectedAll;

    public function index()
    {
        $persona = session('persona',null);
        $prediosId = session('predios',[]);//array con ids de los predios seleccionados
        $predios=($prediosId)?Predio::find($prediosId):collect(); //predios Seleccionados
        $selectedAll=($persona)?$predios==$persona->predios:false; //variable que indica si estan o no seleccionados todos
        $asignaciones=Asignacion::all();//aisgnaciones existentes en la BD //todo quitar esto de las variables
        $assignedControls = Asignacion::pluck('id_control')->toArray();//array con los controles que ya fueron asignados
        $availableControls=array_diff(Cache::get('controles',[]),$assignedControls); //controles disponibles
        $controlTurn=session('lastControl',0)+1;//control que deberia ser el siguiente
        if(!in_array($controlTurn,$availableControls)){
            $control=current($availableControls);//si el control ya fue asignado devuelve el siguiente en la lista
        }
        return view('registro', compact('persona', 'predios','selectedAll','asignaciones','availableControls','controlTurn'));
    }


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
        $predios = session('predios',[]);
        $controlId = $request->input('control');
        if($predios==[]){
           return back()->withErrors('No se han seleccionado predios');
        }
        try{
            $asignacion=Asignacion::create([
            'cc_asistente' => $cc_asistente,
            'id_control' => $controlId,
            'sum_coef' =>$request->sum_coef,
            'estado' => 'activo'
            ]);
        }catch(QueryException $e){
            if ($e->errorInfo[1] == 1062) {
                // Manejar la excepción específica de "Duplicate entry"
                return redirect()->route('asistentes.index')->withErrors('El número de control ya está asignado. Por favor, elige otro.');
            }
        }catch(\Exception $e){
            return redirect()->route('asistentes.index')->withErrors($e->getMessage());
        }

        $asignacion->predios()->attach($predios);

        session()->forget(['persona', 'predios']);
        session(['lastControl'=>$controlId]);
        return redirect()->route('asistentes.index');
        // return redirect()->route('asistentes.index')->with('success', 'Predios asignados con éxito');
    }

    public function limpiar(){
        session()->forget(['persona', 'predios']);
        return redirect()->route('asistentes.index');
    }
}
