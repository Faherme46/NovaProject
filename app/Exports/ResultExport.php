<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ResultExport implements FromArray, ShouldAutoSize, WithStyles
{
    protected $question;
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
            ['Pregunta: ', $this->question->title],
            ['', '', ''],
            ['', 'Coeficiente', 'Nominal'],
            ['Quorum: ', $this->question->quorum, $this->question->predios],
            ['', '', ''],
        ];
        $totalCoef = 0;
        $totalNom = 0;
        foreach ($options as $option) {
            if ($this->question->$option !== null) {
                $array[] = [$this->question->$option, strval($resultCoef->$option), strval($resultNom->$option)];
                $totalCoef += $resultCoef->$option;
                $totalNom += $resultNom->$option;
            }
        }
        $array[] = ['Ausente:', strval($resultCoef->absent), strval($resultNom->absent)];
        $array[] = ['Abstencion:', strval($resultCoef->abstainted), strval($resultNom->abstainted)];
        $array[] = ['Nulo:', strval($resultCoef->nule), strval($resultNom->nule)];

        $totalCoef += $resultCoef->absent + $resultCoef->abstainted + $resultCoef->nule;
        $totalNom += $resultNom->absent + $resultNom->abstainted + $resultNom->nule;
        $array[] = ['Total', $totalCoef, $totalNom];

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
}
