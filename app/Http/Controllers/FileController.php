<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;

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


    public function getQuestionFolderPath($questionId, $title)
    {
        $questionName = ($questionId - 12) . '_' . $title;
        $parentFolderName = $this->getAsambleaFolderPath();
        $newFolderPath = $parentFolderName . '/' . $questionName;
        if (!file_exists($newFolderPath)) {
            mkdir($newFolderPath, 0755, true);
        }
        return $newFolderPath;
    }

    public function getAsambleaFolderPath()
    {
        $externalFolderPath = config('filesystems.disks.externalAsambleas.root');
        // Verifica si la carpeta existe

        if (!file_exists($externalFolderPath)) {
            mkdir($externalFolderPath, 0755, true);
        }
        $asambleaName = Cache::get('name_asamblea');

        $asambleaFolderPath = $externalFolderPath . '/' . $asambleaName;
        // Verifica si la carpeta existe

        if (!file_exists($asambleaFolderPath)) {
            mkdir($asambleaFolderPath, 0755, true);
        }

        return $asambleaFolderPath;
    }

    public function getLocalPath()
    {
    }

    public function createChart($questionId, $title, $labels, $values, $name)
    {
        // Datos para el gráfico

        $asambleaName = Cache::get('name_asamblea', '');
        $parent_path = $this->getQuestionFolderPath($questionId, $title); // Ruta donde se guardará la imagen
        $output_path =  $parent_path . '/' . $name . '.png';
        $localPath = ($questionId - 12) . '/' . $name . '.png';

        $combined =  array_merge($labels, array_map('strval', $values));
        // Crear un array con los datos
        $data = [
            $title,
            json_encode($combined),
            $output_path
        ];
        $args = '';

        foreach ($data as $value) {
            $args .= ' ' . escapeshellarg($value);
        }

        // dd($args);
        // Convertir el array a JSON

        // $json_data =  escapeshellarg($datos);
        // Ejecutar el script de Python
        $py_path = env('PYTHON_PATH');

        $command = escapeshellcmd("$py_path C:/xampp/htdocs/nova/scripts/create_plot.py $args " . escapeshellarg($asambleaName));
        // Ejecutar el comando y capturar la salida y errores

        $output = shell_exec($command . ' 2>&1');
        if ($output != '200') {
            throw new Exception('Problemas al crear las Graficas: ' . $output);
        } else {
            return $this->loadImage($output_path, $localPath);
        }
    }

    public function loadImage($sourcePath, $destinationPath)
    {
        // Verifica si el archivo existe
        if (file_exists($sourcePath)) {
            // Mueve el archivo al directorio de almacenamiento
            Storage::disk('results')->put($destinationPath, file_get_contents($sourcePath));

            return $destinationPath;
        } else {
            dd('algo muy malo ha sucedido');
            throw new Exception('File does not exist');
        }
    }

    public function createResult($values,$questionId,$title){
        
    }


}
