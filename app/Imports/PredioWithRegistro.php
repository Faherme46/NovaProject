<?php

namespace App\Imports;

use App\Models\Persona;
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
        $keys = ['descriptor1', 'numeral1', 'descriptor2', 'numeral2', 'coeficiente', 'novota',
        'cc_propietario', 'cc_apoderado', 'nombre_ap', 'apellido_ap'];
        foreach ($keys as $key) {

            if (!array_key_exists($key, $row)) {
                throw new \Exception('La columna "' . $key . '" es obligatoria');
            }
        }
        $personas = $row['cc_propietario'];
        $cleanedString = preg_replace('/[^0-9;]+/', '', $personas);
        $personasArray = explode(';', $cleanedString);


        $predio = Predio::create([
            'descriptor1' => $row['descriptor1'],
            'numeral1' => $row['numeral1'],
            'descriptor2' => $row['descriptor2'],
            'numeral2' => $row['numeral2'],
            'cc_apoderado' => $row['cc_apoderado'],
            'coeficiente' => round($row['coeficiente'],5),
            'vota' => !$row['novota']
        ]);
        if (array_key_exists('votos', $row)) {
            $predio->votos=$row['votos'];
        }else{
            $predio->votos=1;
        }
        $predio->personas()->attach($personasArray);
        $predio->save();

        if( $row['cc_apoderado']){
            $personaX=Persona::find($row['cc_apoderado']);
            if(!$personaX){
                if(!$row['nombre_ap']){
                    throw new \Exception("Se requiere 'nombre_ap' para '" . $row['cc_apoderado'] . "' en {$predio->getFullName()}");
                }
                $apoderado = Persona::create([
                    'id' => $row['cc_apoderado'],
                    'tipo_id' => 'CC',
                    'nombre' => strtoupper($row['nombre_ap']),
                    'apellido' => strtoupper($row['apellido_ap'])
                ]);
                $apoderado->save();
            }

        }

        return $predio;
    }
}
