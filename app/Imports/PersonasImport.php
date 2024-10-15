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
        $keys = ['cc_propietario','tipo_id','nombre','apellido'];
        foreach ($keys as $key) {

            if (!array_key_exists($key, $row)) {
                throw new \Exception('La columna "' . $key . '" es obligatoria');
            }
        }
        return new Persona([
            'id'=>$row['cc_propietario'],
            'tipo_id'=>$row['tipo_id'],
            'nombre'=>strtoupper($row['nombre']),
            'apellido'=>strtoupper($row['apellido'])
        ]);
    }
}
