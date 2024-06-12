<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Imports\PersonasImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Persona;
class PersonasController extends Controller
{
    public function import(Request $request){

        $request->validate([
            'file'=>'required|mimes:csv,xlsx,xls,txt'
        ]);
        try {
            $file=$request->file('file');
            Excel::import(new PersonasImport,$file);
            return redirect()->route('predios.index')->with('success','Carga de datos exitosa');
            } catch (\Exception $e) {
                //TODO majo de errores
            return back()->withErrors($e->getMessage());
        }
    }

    public function destroyAll(){
        Persona::truncate();
        return back();

    }

    public function find(){
        
    }
}
