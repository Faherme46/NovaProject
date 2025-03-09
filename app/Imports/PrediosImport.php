<?php

namespace App\Imports;

use App\Models\Predio;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Calculation\MathTrig\Round;

class PrediosImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $keys = ['descriptor2', 'numeral2', 'coeficiente', 'novota'];
        foreach ($keys as $key) {

            if (!array_key_exists($key, $row)) {
                throw new \Exception('La columna "' . $key . '" es obligatoria');
            }
        }
        $attributes=[
            'descriptor1' => ($row['descriptor1']&&$row['numeral1']!='-')?$row['descriptor1']:'',
            'numeral1' => ($row['numeral1']&&$row['numeral1']!='-')?$row['numeral1']:'',
            'descriptor2' => $row['descriptor2'],
            'numeral2' => $row['numeral2'],
            'coeficiente' => round($row['coeficiente'],5),
            'vota' => !$row['novota'],
        ];
        if (array_key_exists('votos', $row)) {
            $attributes['votos']=$row['votos'];
        }else{
            $attributes['votos']=1;
        }
        return new Predio($attributes);
    }
}
