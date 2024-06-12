<?php

namespace App\Imports;

use App\Models\Predio;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PrediosImport implements ToModel,WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Predio([
            'cc_propietario'=>$row['cc_propietario'],
            'descriptor1'=>$row['descriptor1'],
            'numeral1'=>$row['numeral1'],
            'descriptor2'=>$row['descriptor2'],
            'numeral2'=>$row['numeral2'],
            'coeficiente'=>$row['coeficiente']
        ]);
    }
}
