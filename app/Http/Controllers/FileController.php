<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Imports\PropiedadesImport;
use App\Models\Propiedad;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class FileController extends Controller
{
    public function index(){
        $propiedades=Propiedad::all();
        return view('admin.creaAsamblea',compact('propiedades'));
    }

    public function import(Request $request){
        $request->validate([
            'file'=>'required|mimes:csv'
        ]);
        try {
            $file=$request->file('file');
            Excel::import(new PropiedadesImport,$file);
            return redirect()->route('files.index');
        } catch (\Exception $e) {
            return back()->withErrors($e->getMessage());
        }


    }
    public function export(){
        dd('Todo');
    }
}
