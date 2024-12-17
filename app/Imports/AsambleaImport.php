<?php

namespace App\Imports;

use App\Models\Asamblea;
use Maatwebsite\Excel\Concerns\ToModel;

use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AsambleaImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $fields = [
            'name',
            'folder',
            'fecha',
            'controles',
            'registro',
            'signature'
        ];
        foreach ($fields as $f) {
            if (!array_key_exists($f, $row)) {
                throw new \Exception('La columna  ' . $f . ' es obligatoria');
            }
        }

        $existingAsamblea = Asamblea::where('name', $row['name'])->get();
        // Si el usuario ya existe, no crearlo de nuevo
        if ($existingAsamblea->isEmpty()) {
            $attributes = [];
            $fields = [
                'id_asamblea',
                'name',
                'folder',
                'lugar',
                'ciudad',
                'fecha',
                'hora',
                'controles',
                'referencia',
                'Tipo',
                'h_inicio',
                'h_cierre'
            ];
            foreach ($fields as $f) {
                $attributes[$f] =(array_key_exists($f, $row)) ? $row[$f] : null;
            }
            $asamblea = Asamblea::create($attributes);
            if(array_key_exists('ordenDia', $row)){
                $asamblea->ordenDia=json_decode($row['ordenDia']);
            }
            if(array_key_exists('registro', $row)){
                $asamblea->ordenDia=(bool) $row['registro'];
            }
            if(array_key_exists('signature', $row)){
                $asamblea->ordenDia=(bool) $row['signature'];
            }


            return $asamblea;
        }else{
            return null;
        }

    }
}
