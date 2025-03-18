<?php

namespace App\Imports;

use App\Models\Control;
use App\Models\Vote;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class VotesImport implements ToModel, WithHeadingRow
{
    /**
     * @param Collection $collection
     */
    public function model(array $row)
    {
        $attributes = [
            'sum_coef' => ($row['coeficiente_total'])?$row['coeficiente_total']:0,
            'sum_coef_can' => ($row['coeficiente_habilitado'])?$row['coeficiente_habilitado']:0,
            'sum_coef_abs' => ($row['coeficiente_abstencion'])?$row['coeficiente_abstencion']:0,
            'predios_total' => ($row['predios_totales'])?$row['predios_totales']:0,
            'predios_vote' => ($row['predios_habilitados'])?$row['predios_habilitados']:0,
            'predios_abs' => ($row['predios_abstencion'])?$row['predios_abstencion']:0,
            'state'=> ($row['estado'])?$row['estado']:0,
        ];
        
        $attributes['vote'] = ($row['voto'])?$row['voto']:null;
        
        
        Control::where('id', $row['control'])->update(
            $attributes
        );
    }
}
