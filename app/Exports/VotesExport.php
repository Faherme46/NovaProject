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
    protected $data;
    public function __construct($votes) {
        $this->data = $votes;
    }

    public function array(): array
    {
        $array = $this->buildArray();
        return $array;

    }

    public function buildArray(){
        $array = Control::with('vote')->get()->map(function ($control) {
            $td='';
            if($control->vote){
                $td = ($control->t_publico=='1') ? 'Publico' : 'Privado';
                $vote=$control->vote->vote;
            }else{
                $td = '';
                $vote='';
            }


            return [$control->id, $vote, $td];
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
