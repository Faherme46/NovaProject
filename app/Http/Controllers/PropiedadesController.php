<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Validators\ValidationException;

use App\Models\Propiedad;
use App\Models\Persona;

use App\Imports\PersonasImport;
use App\Imports\PropiedadesImport;

use Maatwebsite\Excel\Facades\Excel;
class PropiedadesController extends Controller
{

    protected $sessionController;

    public function __construct() {
        $this->sessionController = new SessionController;;
    }
    public function index(){
        $propiedades=Propiedad::all();
        $personas=Persona::all();
        return view('admin.creaAsamblea',compact('propiedades'));
    }
    public function destroyAll(){
        Persona::truncate();
        Propiedad::truncate();
        return redirect()->route('admin.asamblea')->with('success', 'Todas las propiedades y archivos eliminados con éxito.');
    }

    public function import(String $file){

        try {
            $externalFilePath = 'C:/Asambleas/Clientes/'.$file.'/datos.xlsx';
            Excel::import(new PersonasImport,$externalFilePath);
            Excel::import(new PropiedadesImport,$externalFilePath);

            return redirect()->route('asambleas.index')->with('success','Carga de datos exitosa');
        } catch (ValidationException $e) {

            // Manejar excepciones específicas de validación de Excel
            $failures = $e->failures();
            throw new \Exception('Error: '.$failures[1]);
            //Excepcion por archivo inexistente
        } catch (\Illuminate\Contracts\Filesystem\FileNotFoundException $e) {
            throw new \Exception('Error: No se encontró la hoja de cálculo: datos.xlsx');
        } catch (\Exception $e) {

            // Manejar cualquier otra excepción
             throw new \Exception($e->getMessage());
        }
    }

}
