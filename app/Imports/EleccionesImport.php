<?php

namespace App\Imports;

use App\Models\Persona;
use App\Models\Torre;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class EleccionesImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        if ($row['candidato']) {

            if ($row['cc_propietario']) {
                $persona = Persona::find($row['cc_propietario']);
            }
            if ($persona) {
                $torre = Torre::where('name', $row['candidato'])->first();
                // dd($row['candidato']);
                if ($torre) {
                    $torre->candidatos()->syncWithoutDetaching($persona->id);
                    $torre->save();
                }
            }
        }
        return;
    }
}
