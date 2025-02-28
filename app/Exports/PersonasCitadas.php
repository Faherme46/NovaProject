<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class PersonasCitadas implements FromArray, WithEvents
{
    /**
     * @return \Illuminate\Support\Collection
     */

    public $data;
    public function __construct($data)
    {
        //Array con el id de las questions
        $this->data = $data;
    }
    public function array(): array
    {
        $array = $this->buildArray();

        return $array;
    }

    public function buildArray()
    {
        $array[] = [
            ['Predio', '', 'Coeficiente', 'Asistente', '', 'Nombre'],
            ['Torre', 'Apto.', '', 'Propietario', 'Apoderado', 'Propietario', 'Apoderado']
        ];
        $array += $this->data->map(function ($predio) {
            $predioArray[] = $predio->numeral1;
            $predioArray[] = $predio->numeral2;
            $predioArray[] = $predio->coeficiente;
            $apoderado=false;
            if ($predio->control) {
                $apoderado = !in_array($predio->control->persona->id, $predio->personas->pluck('id')->toArray());
                if ($apoderado) {
                    $predioArray[] = '';
                    $predioArray[] = 'X';
                } else {
                    $predioArray[] = 'X';
                    $predioArray[] = '';
                }
            }else{
                $predioArray[] = 'X';
                $predioArray[] = '';
            }


            $names = '';
            if ($predio->personas->count() == 1) {
                $names .= $predio->personas[0]->fullName();
            } else {
                foreach ($predio->personas as $persona) {
                    $names .= $persona->fullName() . "\n";
                }
            }
            $predioArray[] = $names;
            if($predio->control){
                if ($apoderado) {
                    $predioArray[] = $predio->control->persona->fullName();
                }
            }


            return $predioArray;
        })->toArray();


        return $array;
    }


    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                $cellsToMerge = [
                    'A1:B1',
                    'C1:C2',
                    'D1:E1',
                    'F1:G1'
                ];
                // Combinar celdas (por ejemplo, A1 hasta C1)
                foreach ($cellsToMerge as $cells) {
                    $sheet->mergeCells($cells);
                }




                $sheet->getStyle('A3:E1000')->applyFromArray([
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ]
                ]);
                $sheet->getStyle('A3:G1000')->applyFromArray([
                    'borders' => [
                        'allBorders' => [ // Aplica bordes a todas las celdas del rango
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN, // Tipo de borde
                            'color' => ['rgb' => '000000'], // Color del borde (negro)
                        ],
                    ],
                ]);
                $sheet->getStyle('E3:F1000')->getAlignment()->setWrapText(true);
                $cols = ['A', 'B', 'C', 'D', 'E', 'F', 'G'];
                foreach ($cols as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }

                // Opcional: Aplicar estilos a la celda combinada
                $sheet->getStyle('A1:G2')->applyFromArray([
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
