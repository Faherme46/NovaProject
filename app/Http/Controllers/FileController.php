<?php

namespace App\Http\Controllers;

use App\Exports\ResultExport;
use App\Exports\VotesExport;
use App\Http\Controllers\Controller;
use App\Models\Question;
use Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Facades\Excel;
use Throwable;

class FileController extends Controller
{
    public $numberPrefab;


    public function getFolders()
    {
        // Obtén la ruta de la carpeta externa desde la configuración
        $externalFolderPath=Storage::disk('externalClientes');

        // Verifica si la carpeta existe
        if ($externalFolderPath->exists('')) {
            // Obtén una lista de subcarpetas
            $subfolders = $externalFolderPath->directories();

            return $subfolders;
        } else {

            return redirect()->back()->withErrors(['error' => 'La carpeta externa no existe.']);
        }
    }


    public function getQuestionFolderPath($questionId, $title)
    {
        $questionName = ($questionId);
        $parentFolderName = $this->getAsambleaFolderPath();
        $newFolderPath = $parentFolderName . '/Preguntas/' . $questionName;

        if (!file_exists($newFolderPath)) {
            mkdir($newFolderPath, 0755, true);
        }
        return $newFolderPath;
    }

    public function getAsambleaFolderPath()
    {

        $asambleaName = cache('asamblea')['name'];

        $asambleaFolderPath = Storage::disk('externalAsambleas')->path($asambleaName);
        // Verifica si la carpeta existe

        if (!$asambleaFolderPath) {
            Storage::disk('externalAsambleas')->makeDirectory($asambleaName);
        }

        return $asambleaFolderPath;
    }

    public function getOnlyQuestionPath($questionId, $title): string
    {
        $questionName = ($questionId);
        $parentFolderName = cache('asamblea')['name'];
        $newFolderPath = $parentFolderName . '/Preguntas/' . $questionName;
        return $newFolderPath;
    }
    public function quitarTildes($texto) {
        $conTildes = ['á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú', 'ñ', 'Ñ'];
        $sinTildes = ['a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U', 'n', 'N'];
        return str_replace($conTildes, $sinTildes, $texto);
    }
    public function createChart($questionId, $title, $labels, $values, $name)
    {
        // Datos para el gráfico

        $asambleaName = cache('asamblea')['name'];

        $parent_path = $this->getQuestionFolderPath($questionId, $title); // Ruta donde se guardará la imagen
        $output_path =  $parent_path . '/' . $name . '.png';
        //todo numero de preguntas en defecto
        $localPath = $asambleaName . '/' .  ($questionId) . '/' . $name . '.png';
        // Crear un array con los datos
        $data = [
            'title'=>$this->quitarTildes($title),
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

    public function exportVotes( $questionId, $title)
    {
        $path = $this->getOnlyQuestionPath($questionId, $title);
        $export = new VotesExport();
        Excel::store($export, $path . '/votos.xlsx', 'externalAsambleas');
        return;
    }

    public function exportResult($question)
    {
        $path = $this->getOnlyQuestionPath($question->id, $question->title);

        $export = new ResultExport($question);
        return Excel::store($export, $path . '/resultados.xlsx', 'externalAsambleas');
    }



    public function exportPdf($path,$output){
        Storage::disk('externalAsambleas')->put($path, $output);
    }

    public function exportTables(){
        $prediosController= new \App\Http\Controllers\PrediosController;
        $personasController = new \App\Http\Controllers\PersonasController;
        $asambleaName = cache('asamblea')['name'];
        $asambleaRegistro= cache('inRegistro');
        $ret1 =$prediosController->export($asambleaName);
        if($asambleaRegistro){
            $ret2 =$personasController->export($asambleaName);
            $ret3 =$prediosController->controlWithRegistroExport($asambleaName);
        }else{
            $ret2=true;
            $ret3=$prediosController->controlExport($asambleaName);
        }


        return $ret1&&$ret2&&$ret3;
    }

}
