<?php

namespace App\Exports;


use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class QuestionsExport implements WithMultipleSheets
{
    protected $data;

    public function __construct($data)
    {
        //Array con el id de las questions
        $this->data = $data;
    }

    public function sheets(): array
    {
        $sheets = [];

        foreach ($this->data as $sheetData) {

            $sheets[] = new ResultExport($sheetData);
        }

        return $sheets;
    }

}
