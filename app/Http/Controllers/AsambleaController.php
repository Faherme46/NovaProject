<?php

namespace App\Http\Controllers;

use App\Models\Asamblea;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DateTimeZone;

use App\Http\Controllers\FileController;

use App\Models\Predio;
use App\Models\Persona;
use Illuminate\Database\QueryException;

class AsambleaController extends Controller
{

    protected $prediosController;
    protected $sessionController;
    protected $fileController;
    protected $asambleaId=0;

    public function __construct() {
        $this->prediosController= new PrediosController();
        $this->sessionController=new SessionController;
        $this->fileController= new FileController;

    }
    public function index()
    {

        $sessionId=$this->sessionController->getSessionId();


        if ($sessionId) {
            $asambleas = Asamblea::get();
            $predios=Predio::get();
            $personas=Persona::get();
            return view('lider.session', compact('asambleas','personas','predios'));

        } else {

            $folders=$this->fileController->getFolders();
            return view('admin.asamblea', compact('folders'));
            # code...
        }

    }

    public function store(Request $request)
    {
        // Obtener los datos originales del request
        $input = $request->all();

        // Modificar los datos del request
        if ($input['registro']=== 'true') {
            $input['registro']=true;
        }else{
            $input['registro']=false;
        }
        $input['name']=$input['folder'].'_'.$input['fecha'].'_'.$input['hora'];

        $request->merge($input);

        $request->validate([
            'folder' => 'required',
            'lugar' => 'required',
            'fecha' => 'required|date',
            'hora' => 'required|date_format:H:i',
            'controles'=>'required',
            'registro'=> 'required|boolean',
        ],[
            'folder.required' => 'Debe seleccionar un cliente.',
        ]);


        try {
            $asamblea=Asamblea::create($request->all());
            $this->asambleaId=$asamblea->id_asamblea;
            $this->sessionController->setSession($asamblea->id_asamblea,$asamblea->folder);
            $this->prediosController->import($asamblea->folder);
            return redirect()->route('asambleas.index')->with('success', 'Reunión creada con éxito.');
        }catch(QueryException $qe){
            if ($qe->errorInfo[1] == 1062) { // 1062 es el código de error para duplicados
                return redirect()->route('asambleas.index')->withErrors('Ya existe una asamblea en la misma fecha.');
            } else {
                return redirect()->route('asambleas.index')->withErrors($qe->getMessage());
            }
        }catch (\Exception $e) {
            dd('');
            $this->sessionController->destroyOnError();
            $this->destroy($asamblea->id_asamblea);
            return redirect()->route('asambleas.index')->withErrors($e->getMessage());
        }
    }



    public function update(Request $request, $id)
    {

        $request->validate([
            'nombre' => 'required',
            'lugar' => 'required',
            'fecha' => 'required|date',
            'hora' => 'required',
            'estado' => 'required|in:pendiente,en_progreso,finalizada,cancelada',
        ]);

        $asamblea = Asamblea::findOrFail($id);
        $asamblea->update($request->all());
        return redirect()->route('admin.asambleas')->with('success', 'Reunión actualizada con éxito.');
    }

    public function destroy($id)
    {
        $asamblea = Asamblea::findOrFail($id);
        $asamblea->delete();
        return redirect()->route('admin.asambleas')->with('success', 'Asamblea eliminada con éxito.');
    }


    public function getName($id){

        if ($id){
            $asamblea = Asamblea::findOrFail($id);
            return $asamblea->folder;
        }else{
            return('-');
        }

    }

    public function getOne($id){
        if ($id){
            $asamblea = Asamblea::findOrFail($id);
            return $asamblea;
        }else{
            return(null);
        }
    }


    public function iniciarAsamblea(Request $request){
            try {
                $asamblea = Asamblea::findOrFail($request->id_asamblea);
                $time=Carbon::now(new DateTimeZone('America/Bogota'));
                if ($asamblea->h_inicio==null) {
                    $asamblea->h_inicio= $time;
                    $asamblea->save();
                    return back()->with('info','Se ha iniciado la asamblea en: '.$time);
                }else{
                    return back()->with('warning','Ya se establecio el inicio en: '.$asamblea->h_inicio);
                }
            } catch (\Exception $e) {
                //throw $th;
                return back()->withErrors($e->getMessage());
            }



    }

    public function terminarAsamblea(Request $request){
        try {
            $asamblea = Asamblea::findOrFail($request->id_asamblea);
            $time=Carbon::now(new DateTimeZone('America/Bogota'));
            if ($asamblea->h_cierre==null) {
                $asamblea->h_cierre= $time;
                $asamblea->save();
                return back()->with('info','Se ha terminado la asamblea en: '.$time);
            }else{
                return back()->with('warning','Ya se establecio el cierre en: '.$asamblea->h_cierre);
            }


        } catch (\Exception $e) {
            //throw $th;
            return back()->withErrors($e->getMessage());
        }


}



    //Metodos de manejo de archivos
}
