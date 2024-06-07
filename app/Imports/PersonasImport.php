<?php

namespace App\Imports;

use App\Models\Persona;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PersonasImport implements ToModel,WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Persona([
            'cedula'=>$row['cc_propietario'],
            'nombre'=>$row['nombre_p'],
            'apellido'=>$row['apellido_p'],
            'apoderado'=>false
        ]);
    }
}
