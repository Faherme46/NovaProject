<?php

namespace App\Exports;

use App\Models\Predio;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PrediosExport implements FromCollection,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Predio::select('id','descriptor1','numeral1','descriptor2','numeral2','coeficiente')->get();
    }
    public function headings(): array
    {
        return [
            'ID',
            'Descriptor1',
            'Numeral1',
            'Descriptor2',
            'Numeral2'
        ];
    }

}
