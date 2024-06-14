<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Validators\ValidationException;

use App\Models\Predio;
use App\Models\Persona;

use App\Imports\PersonasImport;
use App\Imports\PrediosImport;

use Maatwebsite\Excel\Facades\Excel;
class PrediosController extends Controller
{

    protected $sessionController;

    public function __construct() {
        $this->sessionController = new SessionController;;
    }


    public function import(String $file){

        try {
            $externalFilePathPredios = 'C:/Asambleas/Clientes/'.$file.'/predios.xlsx';
            $externalFilePathPersonas= 'C:/Asambleas/Clientes/'.$file.'/personas.xlsx';
            Excel::import(new PersonasImport,$externalFilePathPersonas);
            Excel::import(new PrediosImport,$externalFilePathPredios);

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
