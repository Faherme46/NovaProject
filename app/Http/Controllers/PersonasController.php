<?php

namespace App\Http\Controllers;

use App\Exports\PersonasExport;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Exception;

use App\Imports\PersonasImport;
use App\Models\Predio;
use App\Models\Persona;
use App\Models\Control;
use App\Models\PrediosPersona;

class PersonasController extends Controller
{
    public function import(Request $request)
    {

        $request->validate([
            'file' => 'required|mimes:csv,xlsx,xls,txt'
        ]);
        try {
            $file = $request->file('file');
            Excel::import(new PersonasImport, $file);
            return redirect()->route('predios.index')->with('success', 'Carga de datos exitosa');
        } catch (\Exception $e) {
            //TODO majo de errores
            return back()->withErrors($e->getMessage());
        }
    }

    public function destroyAll()
    {
        Persona::truncate();
        return back();
    }

    public function updatePersona(Request $request)
    {

        $messages = [
            'name.required' => 'El campo coeficiente es obligatorio.',
            'newId.required' => 'El campo id es obligatorio.',
            'tipoid.required' => 'El campo Tipo_Id es obligatorio.',
        ];

        $validator = $request->validate([
            'name' => ['required'],
            'tipoid' => ['required'],
            'newId' => ['required']
        ], $messages);

        $oldId = $request->id;
        $newId = $request->newId;

        if ($oldId!=$newId) {
            try {

                $persona=$this->updateIdPersona($oldId,$newId);

            } catch (\Throwable $th) {
                return redirect()->route('consulta')->withErrors($th->getMessage());
            }
        }else{
            $persona=Persona::find($oldId);
        }
        if(!$persona){
            return redirect()->route('consulta')->with('warning1','No fue encontrado');
       }
       $persona->nombre=strtoupper($request->name);
       $persona->apellido=strtoupper($request->lastName);
       $persona->tipo_id=$request->tipoid;
       $persona->save();

       return redirect()->route('consulta')->with('success', 'Se ha actualizado la persona');
    }

    public function updateIdPersona($oldId,$newId){
        $persona=DB::transaction(function () use ($oldId, $newId) {

            // Actualizar el ID del usuario
            $persona = Persona::find($oldId);

            if ($persona) {
                DB::statement('SET FOREIGN_KEY_CHECKS=0;');
                $persona->id = $newId;
                $persona->save();
                // Actualizar el ID en la tabla controls
                Control::where('cc_asistente', $oldId)->update(['cc_asistente' => $newId]);

                // Actualizar el ID en la tabla predios (si aplica)
                PrediosPersona::where('persona_id', $oldId)->update(['persona_id' => $newId]);
                Predio::where('cc_apoderado', $oldId)->update(['cc_apoderado' => $newId]);
                DB::statement('SET FOREIGN_KEY_CHECKS=1;');
                return $persona;
            } else {
                return null;

            }

        });
        return $persona;
    }

    public function export($route){
        $export = new PersonasExport();
        return Excel::store($export, $route . '/Tablas/personas.xlsx', 'externalAsambleas');
    }
    
}
