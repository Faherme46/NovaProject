<?php

namespace App\Imports;

use App\Models\Predio;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;


class PredioWithRegistro implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $personas = $row['cc_propietario'];
        $cleanedString = preg_replace('/[^0-9;]+/', '', $personas);
        $personasArray = explode(';', $cleanedString);


        $predio =Predio::create([
            'descriptor1' => $row['descriptor1'],
            'numeral1' => $row['numeral1'],
            'descriptor2' => $row['descriptor2'],
            'numeral2' => $row['numeral2'],
            'cc_apoderado' => $row['apoderado'],
            'coeficiente' => $row['coeficiente'],
            'vota' => !$row['novota']
        ]);
        $predio->personas()->attach($personasArray);
        $predio->save();
        return $predio;
    }
}
