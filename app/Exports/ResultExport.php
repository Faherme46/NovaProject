<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class ResultExport implements FromArray, ShouldAutoSize, WithStyles, WithTitle, WithEvents
{
    protected $question;
    protected $rows;
    public function __construct($question)
    {
        $this->question = $question;
    }

    public function array(): array
    {
        $array = $this->buildArray();

        return $array;
    }



    public function buildArray()
    {

        $options = ['optionA', 'optionB', 'optionC', 'optionD', 'optionE', 'optionF'];
        $results = $this->question->results;
        if ($results[0]->isCoef) {
            $resultCoef = $results[0];
            $resultNom  = $results[1];
        } else {
            $resultCoef = $results[1];
            $resultNom  = $results[0];
        }


        $array = [
            ['Fecha: ', $this->question->created_at],
            ['ANEXO ' . $this->question->id],
            ['', '', '', '', '', ''],
            [$this->question->title],
            ['Opcion', 'Votos', 'Coeficiente', 'Descripcion'],
            // ['Quorum: ', $this->question->quorum, $this->question->predios],

        ];
        $this->rows = 0;
        foreach ($options as $op) {
            if ($this->question[$op]) {
                $this->rows++;
                $array[] = [str_replace(['option'], '', $op), strval($resultNom[$op]), strval($resultCoef[$op]), $this->question[$op]];
            }
        }


        $array[] = ['Ausente:', strval($resultNom->absent), strval($resultCoef->absent)];
        $array[] = ['Abstencion:', strval($resultNom->abstainted), strval($resultCoef->abstainted)];
        if ($this->question->type == 1) {
            $array[] = ['Presente:', strval($resultNom->nule), strval($resultCoef->nule)];
        } else {
            $array[] = ['Nulo:', strval($resultNom->nule), strval($resultCoef->nule)];
        }



        $array[] = ['Total', $resultNom->total, $resultCoef->total];
        $this->rows += 5;
        return $array;
    }


    public function styles(Worksheet $sheet)
    {
        // // Aplicar color a la celda B2
        // $sheet->getStyle('B11')->getFill()->setFillType(Fill::FILL_SOLID);
        // $sheet->getStyle('B11')->getFill()->getStartColor()->setARGB(Color::COLOR_BLUE);
        //  // Aplicar color a la celda B2
        //  $sheet->getStyle('C11')->getFill()->setFillType(Fill::FILL_SOLID);
        //  $sheet->getStyle('C11')->getFill()->getStartColor()->setARGB(Color::COLOR_BLUE);
        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true]],
            3    => ['font' => ['bold' => true]],
            11    => ['font' => ['bold' => true]],
        ];
    }


    public function title(): string
    {
        $id = $this->question->id;
        return 'Item ' . $id;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Combinar celdas (por ejemplo, A1 hasta C1)
                $sheet->mergeCells('B1:C1');
                $sheet->mergeCells('A2:F2');
                $sheet->mergeCells('A4:F4');

                for ($i = 0; $i < $this->rows; $i++) {
                    $cell = 'D' . ($i + 5) . ':F' . ($i + 5);
                    $sheet->mergeCells($cell);
                }

                $sheet->getStyle('B5:C' . ($this->rows + 4))->applyFromArray([
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ]
                ]);
                $sheet->getStyle('A5:F' . ($this->rows + 4))->applyFromArray([
                    'borders' => [
                        'allBorders' => [ // Aplica bordes a todas las celdas del rango
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN, // Tipo de borde
                            'color' => ['rgb' => '000000'], // Color del borde (negro)
                        ],
                    ],
                ]);

                $sheet->getStyle('D5:F5')->applyFromArray([
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ]
                ]);

                $sheet->getStyle('A5:F5')->applyFromArray([
                    'font' => [
                        'bold' => true,
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'bfbfbf'], // Color de fondo (verde)
                    ],
                ]);
                $sheet->getStyle('A11:F11')->applyFromArray([
                    'font' => [
                        'bold' => true,
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'bfbfbf'], // Color de fondo (verde)
                    ],
                ]);
                $sheet->getStyle('A4:F4')->getAlignment()->setWrapText(true);
                $sheet->getRowDimension(4)->setRowHeight(-1);
                // Opcional: Aplicar estilos a la celda combinada
                $sheet->getStyle('A2:F2')->applyFromArray([
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                    'font' => [
                        'color' => ['rgb' => 'FFFFFF'], // Color del texto (blanco)
                        'bold' => true,
                        'size' => 12,
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '022060'], // Color de fondo (verde)
                    ],
                ]);
                // Opcional: Aplicar estilos a la celda combinada
                $sheet->getStyle('A4:F4')->applyFromArray([
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                    'font' => [
                        'color' => ['rgb' => 'FFFFFF'], // Color del texto (blanco)
                        'bold' => true,
                        'size' => 12,
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '022060'], // Color de fondo (verde)
                    ],
                ]);
            },
        ];
    }
}
