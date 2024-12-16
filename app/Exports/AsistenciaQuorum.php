<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class AsistenciaQuorum implements FromArray, WithEvents
{


     public $data;
     public $quorum_start;
    public function __construct($data,$quorum_start)
    {
        //Array con el id de las questions
        $this->data = $data;
        $this->quorum_start=$quorum_start;
    }
    public function array(): array
    {
        $array = $this->buildArray();

        return $array;
    }

    public function buildArray()
    {
        $array[]=[
            ['Predio','','Coeficiente Propiedad','Asistente a la reunion','Nombre'],
            ['Torre','Apto.']
        ];
        $array += $this->data->map(function ($predio) {
            $predioArray[]=$predio->numeral1;
            $predioArray[]=$predio->numeral2;
            $predioArray[]=$predio->coeficiente;
            try {
                if($this->quorum_start&&!$predio->quorum_start){
                    return $predioArray;
                }else{
                    if($predio->control){
                        $predioArray[]=$predio->getRelationPersona($predio->asistente->id);
                        $predioArray[]=$predio->asistente->fullName();
                    }
                }

            } catch (\Throwable $th) {
                return redirect()->route('gestion.report')->with('error','Error exportando archivos de excel, '.$th->getMessage());
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

                $cellsToMerge=[
                    'A1:B1',
                    'C1:C2',
                    'D1:D2',
                    'E1:E2'
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
                $sheet->getStyle('A3:E1000')->applyFromArray([
                    'borders' => [
                        'allBorders' => [ // Aplica bordes a todas las celdas del rango
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN, // Tipo de borde
                            'color' => ['rgb' => '000000'], // Color del borde (negro)
                        ],
                    ],
                ]);
                $sheet->getStyle('A1:E2')->getAlignment()->setWrapText(true);
                $sheet->getStyle('E3:E1000')->getAlignment()->setWrapText(true);

                $sheet->getColumnDimension('C')->setAutoSize(true);
                $sheet->getColumnDimension('D')->setAutoSize(true);
                $sheet->getColumnDimension('E')->setAutoSize(true);


                // Opcional: Aplicar estilos a la celda combinada
                $sheet->getStyle('A1:E2')->applyFromArray([
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                    'font' => [
                        'color' => ['rgb' => 'FFFFFF'], // Color del texto (blanco)
                        'bold' => true,
                        'size' => 10,
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '022060'], // Color de fondo (verde)
                    ],
                ]);
                $sheet->getStyle('E3:E1000')->applyFromArray([
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ]
                ]);
            },
        ];
    }
}

