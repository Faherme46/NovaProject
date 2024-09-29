<?php

namespace App\Exports;

use App\Models\PrediosPersona;
use Maatwebsite\Excel\Concerns\FromCollection;

class PrediosPersonasExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return PrediosPersona::select('id','persona_id','predio_id')->get();
    }
    public function headings(): array
    {
        return [
            'ID',
            'PersonaId',
            'PredioId'
        ];
    }
}
