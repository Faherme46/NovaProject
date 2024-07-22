<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;


class FileController extends Controller
{

    public function getFolders()
    {
        // Obtén la ruta de la carpeta externa desde la configuración
        $externalFolderPath = config('filesystems.disks.externalClientes.root');

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

    public function createFolder($newFolderName)
    {
        $externalFolderPath = config('filesystems.disks.externalAsambleas.root');

        // Verifica si la carpeta existe
        if (!file_exists($externalFolderPath)) {
            mkdir($externalFolderPath, 0755, true);
        }

        $newFolderPath = $externalFolderPath . DIRECTORY_SEPARATOR . $newFolderName;

        if (!file_exists($newFolderPath)) {
            mkdir($newFolderPath, 0755, true);
        }
    }

    public function createSubFolder($newFolderName,$parentFolder)
    {
        $externalFolderPath = config('filesystems.disks.externalAsambleas.root');

        // Verifica si la carpeta existe
        if (!file_exists($externalFolderPath)) {
            mkdir($externalFolderPath, 0755, true);
        }

        $parentFolderPath = $externalFolderPath . DIRECTORY_SEPARATOR . $parentFolder;

        if (!file_exists($parentFolderPath)) {
            mkdir($parentFolderPath, 0755, true);
        }

        $newFolderPath = $externalFolderPath . DIRECTORY_SEPARATOR . $parentFolder . DIRECTORY_SEPARATOR . $newFolderName;

        if (!file_exists($newFolderPath)) {
            mkdir($newFolderPath, 0755, true);
        }
    }




    public function importPredios(Request $request)
    {
    }
    public function export()
    {
        dd('Todo');
    }
}
