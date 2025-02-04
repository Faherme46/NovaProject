<?php

namespace App\Exports;

use App\Models\Predio;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class ProofExport implements FromCollection,WithDrawings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $producto = Predio::where('id','<',50)->get();

        return $producto;

    }

    public function drawings() {
        return $this->collection()->map(function($predio, $index) {
            $drawing = new Drawing();
            $drawing->setPath(public_path('assets/img/logo.png'));
            $drawing->setHeight(90);
            $drawing->setCoordinates('T'.($index+3));
            return $drawing;
        })->toArray();
    }
}
