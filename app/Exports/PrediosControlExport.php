<?php

namespace App\Exports;

use App\Models\Predio;
use Maatwebsite\Excel\Concerns\FromArray;

class PrediosControlExport implements FromArray
{
    public function array(): array
    {
        $array = Predio::select(['id','descriptor1','numeral1','descriptor2','numeral2','coeficiente','control_id'])->orderBy('id')->get()->toArray();

        return $array;
    }

    
    public function headings(): array
    {
        return [
            'id',
            'control_id'
        ];
    }
    
}
