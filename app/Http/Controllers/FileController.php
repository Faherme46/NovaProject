<?php

namespace App\Http\Controllers;

use App\Exports\ResultExport;
use App\Exports\VotesExport;
use App\Http\Controllers\Controller;
use App\Models\Predio;
use Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Carbon\Carbon;


use Barryvdh\DomPDF\Facade\Pdf;

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
        $asambleaName = Cache::get('name_asamblea');

        $asambleaFolderPath = $externalFolderPath . '/' . $asambleaName;
        // Verifica si la carpeta existe

        if (!file_exists($asambleaFolderPath)) {
            mkdir($asambleaFolderPath, 0755, true);
        }

        return $asambleaFolderPath;
    }

    public function getOnlyQuestionPath($questionId, $title)
    {

        $questionName = ($questionId - 12) . '_' . $title;
        $parentFolderName = Cache::get('name_asamblea');
        $newFolderPath = $parentFolderName . '/Preguntas/' . $questionName;

        return $newFolderPath;
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
        $scriptPath = config('filesystems.disks.scripts.root') . '/create_plot.py';
        $scriptPath = str_replace('\\', '/', $scriptPath);
        $command = escapeshellcmd("$py_path $scriptPath $args " . escapeshellarg($asambleaName));
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

    public function createDocs($request)
    {
        $variables = $this->getVariables($request);
        // $views = ['front-page',
        //           'index-registro',
        //         'personas-citadas',
        // 'asistencia-quorum'];
        $views=['personas-citadas'];
        foreach ($views as $key=> $view) {
            $pdf = Pdf::loadView('docs/'.$view, $variables);
            // Aplicar el CSS personalizado
            $pdf->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'defaultFont' => 'Helvetica',
                'isPhpEnabled' => true,
            ]);

            $pdf->setPaper('A4', 'portrait');
            $pdf->save(cache('name_asamblea') . '/Informe/Informe'.$key.'.pdf','externalAsambleas');
            $canvas=$pdf->getCanvas();
            $canvas->page_text(280,810," {PAGE_NUM} de {PAGE_COUNT}",'Helvetica', 7, array(0, 0, 0));
            $docs[]= $pdf->output();
        }

        return $docs;
    }

    public function getVariables($request)
    {
        $predios=Predio::where('id','<','10')->get();
        if (cache('inRegistro')) {
            $variables = [
                'title' => 'Informe de Asamblea Ordinaria',
                'subtitle' => '',
                'h_start' => $request->h_start,
                'h_end' => $request->h_end,
                'date' => Carbon::now()->locale('es')->isoFormat('MMMM YYYY'),
                'anexos' => $request->anexos,
                'firstFooter'=>'Los datos utilizados por TECNOVIS para la elaboración de los
                                Anexos relacionados en este informe
                                (incluye los cálculos para las votaciones),
                                y que comprende la lista de delegados,
                                tiene como base la información suministrada
                                por la Administración de Bosque del Hato a TECNOVIS,
                                para el desarrollo de esta Asamblea.'
            ];
        } else {
            $variables = [
                'title' => 'Asamblea de copropietarios',
                'subtitle' => 'Informe de Votación',

                'date' => Carbon::now()->locale('es')->isoFormat('MMMM YYYY'),
            ];
        }

        $variables += [
            'registro' => cache('inRegistro'),
            'client_name' => $request->client_name,
            'dateAsamblea' => $request->date,
            'type' => $request->type,
            'reference' => $request->reference,
            'predios' => $predios
        ];
        return $variables;
    }
    public function createReport(Request $request)
    {
        $messages = [
            'anexos.required' => 'Se requieren anexos',
            'date.required' => 'Se requiere la fecha',
            'type' => 'Se requiere el tipo de asamblea',
            'client_name' => 'Se requiere el nombre del cliente',
            'reference' => 'Se requiere la referencia',
        ];

        $request->validate([
            'anexos' => ['required'],
            'date' => ['required'],
            'type' => ['required'],
            'client_name' => ['required'],
            'reference' => ['required'],
        ], $messages);


        // Guardar el PDF en la ruta especificada

        $filePath = $this->getAsambleaFolderPath(); // Por ejemplo, en la carpeta 'storage/app/public/'
        $filePath = $filePath . '/Informe';

        if (!file_exists($filePath)) {
            mkdir($filePath, 0755, true);
        }

        $docs=$this->createDocs($request);
        // foreach ($docs as $pdf) {
        //     // $pdf->save();
        // }

        $pdfContent=end($docs);
        // $variables=$this->getVariables($request);
        // return view('docs.asistencia-quorum', $variables);
         return response()->stream(
             function () use ($pdfContent) {
                 echo $pdfContent;
             },
             200,
             [
                 'Content-Type' => 'application/pdf',
                 'Content-Disposition' => 'inline; filename="Informe.pdf"',
             ]
         );
    }
}
