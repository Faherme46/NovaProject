<?php

namespace App\Http\Controllers;

use App\Models\Asamblea;
use Illuminate\Http\Request;
use App\Http\Controllers\FileController;

class AsambleaController extends Controller
{
    public function index()
    {


        $sessionController=new SessionController;
        $sessionId=$sessionController->getSessionId();

        if ($sessionId) {
            $asambleas = Asamblea::get();
            return view('admin.session', compact('asambleas'));

        } else {
            $fileController= new FileController;
            $folders=$fileController->getFolders();
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

        $request->merge($input);

        $request->validate([
            'folder' => 'required',
            'lugar' => 'required',
            'fecha' => 'required|date',
            'hora' => 'required|date_format:H:i',
            'estado' => 'required|in:pendiente,en_progreso,finalizada',
            'registro'=> 'required|boolean',
        ],[
            'folder.required' => 'Debe seleccionar un cliente.',
        ]);


        $asamblea=Asamblea::create($request->all());



        $sessionController = new SessionController();
        $sessionController->setSession($asamblea->id_asamblea);
        return redirect()->route('asambleas.index')->with('success', 'Reunión creada con éxito.');
    }

    public function edit($id)
    {
        $asamblea = Asamblea::findOrFail($id);
        return view('admin.creaAsamblea', compact('asamblea'));
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
        return redirect()->route('asambleas.index')->with('success', 'Reunión actualizada con éxito.');
    }

    public function destroy($id)
    {

        $asamblea = Asamblea::findOrFail($id);
        $asamblea->delete();

        return redirect()->route('asambleas.index')->with('success', 'Asamblea eliminada con éxito.');
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

    //Metodos de manejo de archivos
}
