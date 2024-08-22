<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Jobs\GeneratePdf;
use App\Models\Asamblea;
use Carbon\Carbon;
use App\Models\Question;
use App\Models\Control;
use App\Models\Predio;
use setasign\Fpdi\Fpdi;

use Barryvdh\DomPDF\Facade\Pdf;
use setasign\Fpdi\PdfParser\StreamReader;

class ReportController extends Controller
{

    public $asamblea;
    public $variables;
    public $docs;
    public $questions;
    public $predios;
    public function __construct()
    {
        $this->variables['date'] = Carbon::now()->locale('es')->isoFormat('MMMM YYYY');
        $this->variables['quorum'] = Control::whereNotIn('state', [4, 3])->sum('sum_coef');
        $this->asamblea = Asamblea::find(cache('id_asamblea'));
        // $this->predios = Predio::where('id', '<', 12)->get();
        $this->predios = Predio::all();
        $this->questions = Question::whereNot('prefab');


        $this->variables += [
            'registro' => cache('inRegistro'),
            'asamblea' => $this->asamblea,
            'predios' => $this->predios,
            'prediosCount' => $this->predios->whereNotNull('control_id')->count(),
            'questions' => $this->questions
        ];
    }

    /**
     * Execute the job.
     */
    public function createReport()
    {

        try {
 
            $this->createDocument('front-page');
            if (!cache('inRegistro')) {
                $this->variables += [
                    'anexos' => $this->questions->pluck('title')->toArray(),
                ];
                $this->createDocument('index-votacion');
            } else {
                $anexos = (cache('ordenDia', '')) ? [
                    'Listado de Personas Citadas a la Asamblea ',
                    'Asistencia Para Quorum',
                    'Listado Total de Participantes en la Asamblea',
                    'Orden del Día',
                    'Informe Resultado de Votaciones'
                ] : [
                    'Listado de Personas Citadas a la Asamblea ',
                    'Asistencia Para Quorum',
                    'Listado Total de Participantes en la Asamblea',
                    'Informe Resultado de Votaciones'
                ];
                $this->variables += [
                    'anexos' => $anexos,
                    'firstFooter' => 'Los datos utilizados por TECNOVIS para la elaboración de los
                                    Anexos relacionados en este informe
                                    (incluye los cálculos para las votaciones),
                                    y que comprende la lista de delegados,
                                    tiene como base la información suministrada
                                    por la Administración de Bosque del Hato a TECNOVIS,
                                    para el desarrollo de esta Asamblea.'
                ];
                $this->createDocument('index-registro');

                $this->variables['index'] = 0;
                $this->createDocument('personas-citadas');

                $this->variables['index'] = 1;
                $this->createDocument('asistencia-quorum');
                $this->variables['index'] = 2;
                $this->createDocument('participantes-asamblea');
                $this->variables['index'] = 3;
                if (cache('ordenDia', '')) {
                    $this->variables['ordenDia'] = cache('ordenDia');
                    $this->createDocument('orden-dia');
                }

                // $this->variables['index']=3;
                // $this->createDocument('quorum-final');
            }
            $this->createDocument('votaciones');
            $fileController = new FileController();
            $filePath = $fileController->getAsambleaFolderPath();
            $filePath = $filePath . '/Informe';
            if (!file_exists($filePath)) {
                mkdir($filePath, 0755, true);
            }
            $output = $this->mergePdf();

            $fileController->exportPdf($this->asamblea->name . '/Informe/Informe.pdf', $output);


            return response($output, 200)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'inline; filename="Informe.pdf"');;
        } catch (\Throwable $th) {
        }
    }
    public function createDocument($name)
    {
        $pdf = Pdf::loadView('docs/' . $name, $this->variables);
        // Aplicar el CSS personalizado
        $pdf->setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'defaultFont' => 'Helvetica',
            'isPhpEnabled' => true,
        ]);
        $pdf->setPaper('A4', 'portrait');
        $this->docs[$name] = $pdf->output();
    }
    function mergePdf()
    {
        // Crear una nueva instancia de FPDI
        $pdf = new Fpdi();

        // Recorrer el array de PDFs
        foreach ($this->docs as $pdfStr) {
            // Añadir una nueva página para cada PDF
            $pageCount = $pdf->setSourceFile(StreamReader::createByString($pdfStr));

            for ($i = 1; $i <= $pageCount; $i++) {
                // Importar cada página del PDF
                $templateId = $pdf->importPage($i);
                $pdf->AddPage();

                // Usar la plantilla (imported page)
                $pdf->useTemplate($templateId);
            }
        }


        return $pdf->output('S');
    }
}
