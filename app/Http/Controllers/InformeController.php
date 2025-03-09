<?php

namespace App\Http\Controllers;

use App\Exports\EleccionesExport;
use App\Exports\PersonasCitadas;
use App\Http\Controllers\Controller;
use App\Models\Asamblea;
use App\Models\Control;
use App\Models\Predio;
use Carbon\Carbon;

use setasign\Fpdi\Fpdi;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use setasign\Fpdi\PdfParser\StreamReader;

class InformeController extends Controller
{
    public $asamblea;
    public $variables;
    public $docs;

    public $predios;
    public function __construct()
    {
        $this->variables['date'] = Carbon::now()->locale('es')->isoFormat('MMMM YYYY');
        
        $this->asamblea = Asamblea::find(cache('asamblea')['id_asamblea']);
        $date = explode('-', $this->asamblea->fecha);
        $meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
        $dateString = $date[2] . ' de ' . $meses[(int)$date[1] - 1] . ' de ' . $date[0];
        // $this->predios = Predio::where('id', '<', 5)->get();
        $this->predios = Predio::with(['personas', 'apoderado'])->get()->map(function ($predio) {
            return [
                'id' => $predio->id,
                'nombre' => $predio->getFullName(),
                'numeral1' => $predio->numeral1,
                'numeral2' => $predio->numeral2,
                'control_id' => $predio->control_id,
                'coeficiente' => $predio->coeficiente,
                'personas' => $predio->personas->map(function ($persona) {
                    return ['id' => $persona->id, 'nombre' => $persona->fullName()];
                })->toArray(),
                'apoderado' => $predio->apoderado ? $predio->apoderado->toArray() : null,
                'quorum_start' => $predio->quorum_start,
                'quorum_end' => $predio->quorum_end,
                'h_entrega' => ($predio->control) ? $predio->control->h_entrega : false,
                'h_recibe' => ($predio->control) ? $predio->control->h_recibe : false,
            ];
        })->toArray();

        
        $this->variables += [
            'registro' => cache('inRegistro'),
            'asambleaR' => $this->asamblea,
            'dateString' => $dateString,
            'predios' => $this->predios,
            'prediosCount' => Predio::whereNotNull('control_id')->count(),
            'quorum'=>Control::sum('sum_coef')
        ];
    }

    /**
     * Execute the job.
     */
    public function createReport()
    {

        try {

            $this->createDocument('front-page');



            $response = $this->exportPersonas();
            if ($response->getStatusCode() == 500) {
                return redirect()->route('gestion.report')->with('error', $response->getContent());
            } else if ($response->getStatusCode() == 423) {
                return redirect()->route('gestion.report')->with('error', 'Error exportando el archivo: "' . $response->getContent() . '". Verifique que no se este usando');
            } else if ($response->getStatusCode() != 200) {
                return redirect()->route('gestion.report')->with('error', 'Error desconocido');
            }
            $anexos = [
                'Listado de Personas Citadas a la Asamblea ',
                'Listado de predios que acudieron a las votaciones',
                'Informe de Resultados de Elecciones'
            ];
            $this->variables += [
                'anexos' => $anexos,
            ];
            $this->createDocument('index-registro');

            $this->variables['index'] = 0;
            $this->createDocument('personas-citadas');

            $this->variables['index'] = 1;
            $this->createDocument('predios-votan');
            $this->variables['index'] = 2;
            
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
                ->header('Content-Disposition', 'inline; filename="Informe.pdf"');
        } catch (\Throwable $th) {

            return redirect()->route('elecciones.informe')->with('error', $th->getMessage());
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


    public function exportPersonas()
    {
        $asambleaName = cache('asamblea')['name'];
        $predios = Predio::with('personas')->with('apoderado')->get();
        try {
            $export1 = new PersonasCitadas($predios);
            $responseExcel1 = Excel::store($export1, $asambleaName . '/Informe/Personas_citadas.xlsx', 'externalAsambleas');
            if (!$responseExcel1) {
                return response()->json('Personas_citadas.xlsx', 423);
            }

            $export3 = new EleccionesExport();
            $responseExcel3 = Excel::store($export3, $asambleaName . '/Informe/Votos.xlsx', 'externalAsambleas');
            if (!$responseExcel3) {
                return response()->json('Votos.xlsx', 423);
            }


            return response()->json(['success' => 'Archivos Exportados correctamente'], 200);
        } catch (\Throwable $th) {
            return response()->json('Falla en la exportacion de excel: ' . $th->getMessage(), 500);
        }
    }
}
