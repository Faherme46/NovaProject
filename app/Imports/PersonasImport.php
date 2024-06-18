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
            'id'=>$row['cc_propietario'],
            'tipo_id'=>$row['tipo_id'],
            'nombre'=>$row['nombre_p'],
            'apellido'=>$row['apellido_p'],
            'apoderado'=>false
        ]);
    }
}
