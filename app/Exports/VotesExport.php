<?php

namespace App\Exports;

use App\Models\Control;
use App\Models\Vote;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class VotesExport implements FromArray, WithHeadings, WithStyles
{

    public function __construct()
    {
    }

    public function array(): array
    {
        $array = $this->buildArray();
        return $array;
    }

    public function buildArray()
    {
        $array = Control::all()->map(function ($control) {
            $td = '';
            if ($control->vote) {
                $vote = $control->vote;
            } else {
                $vote = '';
            }

            $td = ($control->t_publico == '1') ? 'Publico' : 'Privado';
            return [
                $control->id,
                $vote,
                $control->state,
                $td,
                $control->sum_coef,
                $control->sum_coef_can,
                $control->sum_coef_abs,
                $control->predios_total,
                $control->predios_vote,
                $control->predios_abs
            ];
        })->toArray();

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
            'Estado',
            'TD',
            'Coeficiente Total',
            'Coeficiente Habilitado',
            'Coeficiente Abstencion',
            'Predios Totales',
            'Predios Habilitados',
            'Predios Abstencion',
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
