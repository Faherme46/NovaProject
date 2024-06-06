<?php

namespace App\Http\Controllers;

use App\Models\Reunion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Session;

class ReunionController extends Controller
{
    public function index()
    {
        $reuniones = Reunion::on('principal')->get();
        return view('admin.creaReunion', compact('reuniones'));
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
            'nombre' => 'required',
            'lugar' => 'required',
            'fecha' => 'required|date',
            'hora' => 'required|date_format:H:i',
            'estado' => 'required|in:pendiente,en_progreso,finalizada',
            'registro'=> 'required|boolean',
            'nombreBd' => 'required',
        ]);


        $reunion=Reunion::create($request->all());



        $sessionController = new sessionController();
        $sessionController->setSession($reunion->id_reunion);
        app()->instance('name_reunion', $this->getName($reunion->id_reunion));
        return redirect()->route('reuniones.index')->with('success', 'Reunión creada con éxito.');
    }

    public function edit($id)
    {
        $reunion = Reunion::findOrFail($id);
        return view('admin.creaReunion', compact('reunion'));
    }

    public function update(Request $request, $id)
    {

        $request->validate([
            'nombre' => 'required',
            'lugar' => 'required',
            'fecha' => 'required|date',
            'hora' => 'required',
            'estado' => 'required|in:pendiente,en_progreso,finalizada,cancelada',
            'nombreBd' => 'required',
        ]);

        $reunion = Reunion::findOrFail($id);
        $reunion->update($request->all());
        return redirect()->route('reuniones.index')->with('success', 'Reunión actualizada con éxito.');
    }

    public function destroy($id)
    {

        $reunion = Reunion::findOrFail($id);
        $reunion->delete();

        return redirect()->route('reuniones.index')->with('success', 'Reunión eliminada con éxito.');
    }


    public function getName($id){

        if ($id){
            $reunion = Reunion::findOrFail($id);
            return $reunion->nombre;
        }else{
            return('-');
        }

    }
}
