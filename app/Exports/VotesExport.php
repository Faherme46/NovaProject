<?php

namespace App\Exports;

use App\Models\Control;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class VotesExport implements FromArray, WithHeadings, WithStyles
{
    protected $data;
    public function __construct(array $votos)
    {
        $this->data = $votos;
    }

    public function array(): array
    {
        $array = array_map(function ($value, $key) {

            $td = (Control::find($key)->value('t_publico')) ? 'Publico' : 'Privado';
            
            return [$key, $value, $td];
        }, $this->data, array_keys($this->data));

        return $array;
    }
    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Control',
            'Voto',
            'TD'
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

        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true]],

        ];
    }
}
