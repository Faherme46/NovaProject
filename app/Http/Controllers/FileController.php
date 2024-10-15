<?php

namespace App\Http\Controllers;

use App\Exports\ResultExport;
use App\Exports\VotesExport;
use App\Http\Controllers\Controller;
use App\Models\Question;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Facades\Excel;
use Throwable;

class FileController extends Controller
{
    public $numberPrefab;


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
        $questionName = ($questionId - cache('questionsPrefabCount',13)) . '_' . $title;
        $parentFolderName = $this->getAsambleaFolderPath();
        $newFolderPath = $parentFolderName . '/Preguntas/' . $questionName;

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
        $asambleaName = cache('asamblea')['name'];

        $asambleaFolderPath = $externalFolderPath . '/' . $asambleaName;
        // Verifica si la carpeta existe

        if (!file_exists($asambleaFolderPath)) {
            mkdir($asambleaFolderPath, 0755, true);
        }

        return $asambleaFolderPath;
    }

    public function getOnlyQuestionPath($questionId, $title): string
    {
        $questionCount=Question::where('prefab',true)->count();
        $questionName = ($questionId - $questionCount) . '_' . $title;
        $parentFolderName = cache('asamblea')['name'];
        $newFolderPath = $parentFolderName . '/Preguntas/' . $questionName;

        return $newFolderPath;
    }

    public function createChart($questionId, $title, $labels, $values, $name)
    {
        // Datos para el gráfico

        $asambleaName = cache('asamblea')['name'];

        $parent_path = $this->getQuestionFolderPath($questionId, $title); // Ruta donde se guardará la imagen
        $output_path =  $parent_path . '/' . $name . '.png';

        $localPath = ($questionId - cache('questionsPrefabCount',13)) . '/' . $name . '.png';

        // Crear un array con los datos
        $data = [
            'title'=>$title,
            'output'=>$output_path,
            'labels'=>$labels,
            'values'=>$values,
            'nameAsamblea'=>$asambleaName
        ];



        $jsonData=json_encode($data);
        try {
            $response=Http::post('http://localhost:5000/create-plot', $data);
            return $this->loadImage($output_path, $localPath);
        } catch (Throwable $th) {
            throw new Exception('Error al conectar con el servidor python');
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

            throw new Exception('File does not exist');
        }
    }

    public function exportVotes($votos, $questionId, $title)
    {
        $path = $this->getOnlyQuestionPath($questionId, $title);
        // Sort the data by key (Control) alphabetically
        ksort($votos);

        $export = new VotesExport($votos);
        return Excel::store($export, $path . '/votos.xlsx', 'externalAsambleas');
    }

    public function exportResult($question)
    {
        $path = $this->getOnlyQuestionPath($question->id, $question->title);

        $export = new ResultExport($question);
        return Excel::store($export, $path . '/resultados.xlsx', 'externalAsambleas');
    }


    public function importConf(): int
    {
        $path = storage_path('conf.json');
        if (file_exists($path)) {
            $config = json_decode(file_get_contents($path), true);
            return 200;
        } else {
            return 0;
        }
    }

    public function exportPdf($path,$output){
        Storage::disk('externalAsambleas')->put($path, $output);
    }

    public function exportTables(){
        $prediosController= new \App\Http\Controllers\PrediosController;
        $personasController = new \App\Http\Controllers\PersonasController;
        $asambleaName = cache('asamblea')['name'];

        $ret1 =$prediosController->export($asambleaName);
        $ret2 =$personasController->export($asambleaName);
        $ret3 =$personasController->exportRelation($asambleaName);
        return $ret1&&$ret2&&$ret3;
    }

}
