<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Models\Propiedad;
use Illuminate\Http\Request;


class FileController extends Controller
{

    public function getFolders(){
        // Obtén la ruta de la carpeta externa desde la configuración
        $externalFolderPath = config('filesystems.disks.external.root');

        // Verifica si la carpeta existe
        if (is_dir($externalFolderPath)) {
            // Obtén una lista de subcarpetas
            $subfolders = array_filter(glob($externalFolderPath . '/*'), 'is_dir');

            // Extrae solo los nombres de las carpetas
            $subfolderNames = array_map('basename', $subfolders);

            return $subfolderNames;
        } else {

            return redirect()->back()->withErrors(['error' => 'La carpeta externa no existe.']);
        }
    }


    public function importPropiedades(Request $request){

    }
    public function export(){
        dd('Todo');
    }



}
