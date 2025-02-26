<?php

namespace App\Exports;

use App\Models\Persona;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PersonasExport implements FromCollection,WithHeadings{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Persona::select('tipo_id','id','nombre','apellido')->get();
    }
    public function headings(): array
    {
        return [
            'tipo_id',
            'cc_propietario',
            'nombre',
            'apellidox'
        ];
    }
}
