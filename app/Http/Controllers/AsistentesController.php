<?php
//TODO validar la asignacion mas de una vez
//registrar el estado del quorum
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;


use App\Models\Persona;
use App\Models\Predio;
use App\Models\Asignacion;
use App\Models\Control;

use Illuminate\Support\Facades\Log;

class AsistentesController extends Controller
{
    protected $selectedAll;

    public function index()
    {
        $asistente = session('asistente',null);
        $poderdantes=session('poderdantes',collect());
        $controlIds=session('availableControls',[]);

        if(!$poderdantes->isEmpty()){
            $prediosPoderdantes= $poderdantes->flatMap(function ($persona) {
                return $persona->predios()->whereDoesntHave('asignacion')->get();
            });
        }else {
            $prediosPoderdantes=collect();
        }

        $asignacion=0;
        $prediosAvailable=collect();
        $controlTurn=session('lastControl',0)+1;//control que deberia ser el siguiente

        if($asistente){
            $asignaciones=$asistente->asignaciones;
            if(!$asignaciones->isEmpty()){
                $asignacion=$asignaciones->first();
            }

            $prediosAvailable=$asistente->predios()->whereDoesntHave('asignacion')->get();
            $prediosAvailable=$prediosAvailable->concat($prediosPoderdantes);
            $variables= compact('asistente','asignaciones', 'controlIds','poderdantes','controlTurn');
        }else{
            $variables=[];
        }

        return view('registro', compact('asistente','prediosAvailable','asignacion','controlIds','controlTurn')+$variables);

    }

    public function routing(){
        if(Cache::get('asambleaOn',false)){
            return redirect()->route('asistencia.index');
        }else{
            return redirect()->back()->withErrors('No hay asamblea en sesion');
        }
     }

    //metodo que se ejecuta si el usuario ya tiene una asignacion




    public function buscar(Request $request)
    {
        $this->limpiar();
        $asistente = Persona::find($request->cedula);

        if(!$asistente){
            $newPersona=Persona::create([
                'id'=>$request->cedula,
                'tipo_id'=>'cc',
                'nombre'=>'Pepito andres',
                'apellido'=>'Perez de la cruz',
                'apoderado'=>true
            ]);
            return redirect()->route('asistencia.index')->with('asistente',$newPersona);
        }
        session(['asistente'=>$asistente]);
        $this->getAvailableControls();
        return redirect()->route('asistencia.index');

    }

    public function getAvailableControls(){
        $availableControls=Control::whereDoesntHave('asignacion')->get();
        $controlIds=$availableControls->pluck('id')->toArray();
        session(['availableControls'=>$controlIds]);
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
                return redirect()->route('asistencia.index')->withErrors('El número de control ya está asignado. Por favor, elige otro.');
            }else{
                return redirect()->route('asistencia.index')->withErrors($e->getMessage());
            }
        }catch(\Exception $e){
            dd($e->getMessage());
            return redirect()->route('asistencia.index')->withErrors($e->getMessage());
        }

        $asignacion->predios()->attach($predios);
        session(['lastControl'=>$controlId]);
        $this->limpiar();
        $asistente=Persona::find($cc_asistente);
        $personaPredios=$asistente->predios->pluck('id')->toArray();

        if($personaPredios==$predios){
            return redirect()->route('asistencia.index')->with('success', 'Predios asignados con éxito');
        }else{
            return redirect()->route('asistencia.index')->with('success', 'Predios asignados con éxito')->with('asistente',$asistente);
        };


    }

    public function limpiar(){
        session()->forget(['asistente','poderdantesIds','poderdantes','prediosAvailable']);
        return redirect()->route('asistencia.index');
    }

    public function getPredios(Request $request){
        // session()->forget('propietarios');
        $cedula=$request->cedulaPropietario;
        $actual=session('asistente',null);

        if(!$actual){
            return back()->with('errorPropietarios','No hay asistente registrado');
        }else if($actual->id==$cedula){
            return back()->with('errorPropietarios','El poderante no puede ser igual al asistente');
        }

        $arrayPropietarios=session('poderdantesIds',[]);
        // dd($arrayPropietarios);
        $cedula=$request->cedulaPropietario;
        $persona=Persona::find($cedula);

        if($persona){


            if (in_array($cedula,$arrayPropietarios)) {
                return  back()->with('errorPropietarios','Ya fue añadido');
            }
            $arrayPropietarios[]=$cedula;

            $poderdantes=Persona::find($arrayPropietarios);
            session(['poderdantesIds'=>$arrayPropietarios]);
            session(['poderdantes'=>$poderdantes]);

            return back(302,compact('poderdantes'));
        }else{
            return back()->with('errorPropietarios','No se encontro');
        }

    }

    public function dropPoderdante(Request $request){
         // session()->forget('propietarios');
        $arrayPropietarios=session('poderdantesIds',[]);
        $cedula=$request->cedula;
        $arrayPropietarios = array_values(array_filter($arrayPropietarios, function($valor) use ($cedula) {
            return $valor !== $cedula;
        }));

        $poderdantes=Persona::find($arrayPropietarios);
        session(['poderdantesIds'=>$arrayPropietarios]);
        session(['poderdantes'=>$poderdantes]);
        return back(302,compact('poderdantes'));
    }

    public function dropAllPoderdantes(){
        session()->forget(['poderdantesIds','poderdantes']);
        $poderdantes=collect();
        return back(302,compact('poderdantes'));
    }


    public function asistencia(){
        $asignacionesAll=Asignacion::all();
        return view('asistencia',compact('asignacionesAll'));
    }


    public function editAsignacion(Request $request){
        Log::info('Método update ejecutado'); // Registro para debugging

        $idPredios=$request->input('prediosSelect');
        $asignacion = Asignacion::findOrFail($request->asignacion_id);
        if(is_null($idPredios)){
           return back()->withErrors('No se han seleccionado predios');
        }
        $newPredios=explode(",", $idPredios);

        try{
            $asignacion->predios()->syncWithoutDetaching($newPredios);

        }catch(QueryException $e){
            if ($e->errorInfo[1] == 1062) {
                return redirect()->route('asistencia.index')->withErrors($e->getMessage());
            }
        }catch(\Exception $e){
            dd($e->getMessage());
            return redirect()->route('asistencia.index')->withErrors($e->getMessage());
        }


        $this->limpiar();

        return back();

    }


    public function changeAsignacion(Request $request){
        $asignacion=Asignacion::findOrFail($request->id_asignacion);



        return back(302,compact('asignacion'));
    }

    public function creaPersona(){
    // Lógica para determinar si se debe mostrar el modal
    // Aquí puedes agregar cualquier lógica necesaria

    // Redirigir a la vista con una variable de sesión para mostrar el modal
    return redirect()->route('asistencia.index')->with('showModal', true);
}
}


