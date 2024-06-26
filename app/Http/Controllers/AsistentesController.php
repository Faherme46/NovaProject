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
        $prediosToAdd=session('prediosToAdd',collect());
        $allPredios=Predio::all();

        if(!$poderdantes->isEmpty()){
            $prediosPoderdantes= $poderdantes->flatMap(function ($persona) {
                return $persona->predios()->whereDoesntHave('asignacion')->get();
            });
        }else {
            $prediosPoderdantes=collect();
        }

        $asignacion=0;
        $prediosAvailable=$prediosToAdd;

        $controlTurn=session('lastControl',0)+1;//control que deberia ser el siguiente

        if($asistente){
            $asignaciones=$asistente->asignaciones;
            if(!$asignaciones->isEmpty()){
                    if(session('asignacion')){
                        $asignacion=Asignacion::find(session('asignacion'));
                    }else{
                        $asignacion=$asignaciones->first();
                    }
            }

            $prediosAvailable=$prediosAvailable->concat($asistente->predios()->whereDoesntHave('asignacion')->get());
            $prediosAvailable=$prediosAvailable->concat($prediosPoderdantes);
            $variables= compact('asistente','asignaciones', 'controlIds','poderdantes','controlTurn');
        }else{
            $variables=[];
        }


        return view('registro', compact('allPredios','asistente','prediosAvailable','asignacion','controlIds','controlTurn')+$variables);

    }






    public function buscar(Request $request)
    {

        $this->limpiar();
        $asistente = Persona::find($request->cedula);
        $this->getAvailableControls();
        if(!$asistente){
            $cedula=$request->cedula;
            return back(302)->with('showModal', true)->with('cedula',$cedula);
        }
        session(['asistente'=>$asistente]);

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

        if($personaPredios==$predios||empty($personaPredios)){
            return redirect()->route('asistencia.index')->with('success', 'Predios asignados con éxito');
        }else{
            session(['asistente'=>$asistente]);
            return redirect()->route('asistencia.index')->with('success', 'Predios asignados con éxito');
        };


    }

    public function limpiar(){
        session()->forget(['asistente','poderdantesIds','poderdantes','prediosAvailable','asignacion','prediosToAdd']);
        return redirect()->route('asistencia.index');
    }

    public function getPredios(Request $request){
        // session()->forget('propietarios');
        $cedula=$request->cedulaPropietario;
        $actual=session('asistente',null);

        if(!$actual){
            return back()->with('errorPropietarios','Debe ingresar un asistente');
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

            return redirect()->route('asistencia.index');
        }else{
            return back()->with('errorPropietarios','No se encontro');
        }

    }

    public function dropPoderdante(Request $request){
        $arrayPropietarios=session('poderdantesIds',[]);
        $cedula=$request->cedula;
        $arrayPropietarios = array_values(array_filter($arrayPropietarios, function($valor) use ($cedula) {
            return $valor !== $cedula;
        }));

        $poderdantes=Persona::find($arrayPropietarios);

        session(['poderdantes'=>$poderdantes]);
        return redirect()->route('asistencia.index');
    }

    public function dropAllPoderdantes(){
        session()->forget(['poderdantesIds','poderdantes']);
        //comentario
        return redirect()->route('asistencia.index');
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
            return redirect()->route('asistencia.index')->withErrors($e->getMessage());
        }


        $this->limpiar();

        return back();

    }


    public function changeAsignacion(Request $request){

        $control=Control::findOrFail($request->control);

        $asignacion=$control->asignacion;
        session(['asignacion'=>$asignacion->id]);
        return redirect()->route('asistencia.index');
    }

    public function dropAsignacion(){
        session(['asignacion'=>0]);
        return redirect()->route('asistencia.index');
    }

    public function creaPersona(Request $request){

        $validatedData = $request->validate([
            'cedula' => 'required|max:12',
            'nombre' => 'required',
        ]);
        try {
            $asistente=Persona::create([
                'tipo_id'=>$request->tipo_id,
                'id'=>$request->cedula,
                'nombre'=>$request->nombre,
                'apellido'=>$request->apellido,
                'apoderado'=>true
            ]);
            session(['asistente'=>$asistente]);
            return redirect()->route('asistencia.index');
        } catch (\Exception $e) {
            return back()->withErrors($e->getMessage());
        }
    }

    public function addPredio(Request $request){
        if (session('asistente',false)){
            $listPredios=session('prediosToAdd',collect());
            try {
                $predio=Predio::findOrFail($request->predio_id);
                if($predio){
                    $listPredios->push($predio);
                    session(['prediosToAdd'=>$listPredios]);
                    return redirect()->route('asistencia.index');
                }else{
                    throw new \Exception('Problema para obtener el predio');
                }
            } catch (\Exception $e) {
                return back()->withErrors($e->getMessage());
            }
        }else{
            return back()->withErrors('No hay asistente seleccionado');
        }
    }
}


