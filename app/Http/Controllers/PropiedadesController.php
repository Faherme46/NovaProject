<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Propiedad;
use App\Imports\PropiedadesImport;
use Maatwebsite\Excel\Facades\Excel;
class PropiedadesController extends Controller
{
    public function index(){
        $propiedades=Propiedad::all();
        return view('admin.creaAsamblea',compact('propiedades'));
    }
    public function destroyAll(){

        Propiedad::truncate();
        return redirect()->route('admin.crearAsamblea')->with('success', 'Todas las propiedades y archivos eliminados con éxito.');
    }

    public function import(Request $request){
        $request->validate([
            'file'=>'required|mimes:csv,xlsx,xls,txt'
        ]);
        try {
            $file=$request->file('file');
            Excel::import(new PropiedadesImport,$file);
            return redirect()->route('propiedades.index')->with('success','Carga de datos exitosa');
        } catch (\Exception $e) {

            return back()->withErrors($e->getMessage());
        }
    }

}
