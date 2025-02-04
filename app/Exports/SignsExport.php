<?php

namespace App\Exports;


use App\Models\Predio;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SignsExport implements FromArray, WithHeadings,WithStyles,WithDrawings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function array(): array
    {
        $array = $this->buildArray();

        return $array;
    }

    public function buildArray()
    {
        $array = Predio::with('personas','control')->get()->map(function ($predio) {
            // Convierte el predio a array
            $predioArray[]=$predio->getFullName();
            if ($predio->control){

                $persona=$predio->control->persona;

                $predioArray[]=$predio->getRelationPersona($persona->id);
                $predioArray[]=$persona->fullName();
                $predioArray[]=$persona->tipo_id;
                $predioArray[]=$persona->id;
                $predioArray[]='imagen';
            }

            return $predioArray;
        })->toArray();



        return $array;
    }
    public function headings(): array
    {
        return [
            'Predio',
            'Apoderado/Propietario',
            'Nombre',
            'Tipo de ID',
            'N° de Identificacion',
            'Firma'
        ];
    }


    public function styles(Worksheet $sheet)
    {
        // // Aplicar color a la celda B2
        // $sheet->getStyle('B11')->getFill()->setFillType(Fill::FILL_SOLID);
        // $sheet->getStyle('B11')->getFill()->getStartColor()->setARGB(Color::COLOR_BLUE);
        //  // Aplicar color a la celda B2
        //  $sheet->getStyle('C11')->getFill()->setFillType(Fill::FILL_SOLID);
        //  $sheet->getStyle('C11')->getFill()->getStartColor()->setARGB(Color::COLOR_BLUE);


        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('G')->setAutoSize(true);
        return [
            // Style the first row as bold text.
            1    => [
                'font' => ['bold' => true]

            ],

        ];
    }

    public function drawings() {
        return $this->array()->map(function($predio, $index) {
            $drawing = new Drawing();
            $drawing->setPath(public_path('assets/img/logo.png'));
            $drawing->setHeight(90);
            $drawing->setCoordinates('F'.($index+));
            return $drawing;
        })->toArray();
     }

}
