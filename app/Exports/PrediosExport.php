<?php

namespace App\Exports;

use App\Models\Predio;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PrediosExport implements FromArray, WithHeadings, WithStyles
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function array(): array
    {
        $array = $this->buildArray();

        return $array;
    }

    public function buildArray()
    {
        $array = Predio::with('personas')->with('apoderado')->get()->map(function ($predio) {
            // Convierte el predio a array
            $personasIds = $predio->personas->pluck('id')->implode(';');

            // Agrega el string de IDs al array del predio
            $predioArray = [];
            $predioArray += $predio->toArray();
            $clavesAEliminar = [
                "control_id",
                "quorum_start",
                "quorum_end",
                "created_at",
                "updated_at",
                "personas",
                "personas_ids",
                "apoderado",

                "cc_apoderado"
            ];

            // Elimina los campos por su clave
            foreach ($clavesAEliminar as $clave) {
                unset($predioArray[$clave]);
            }
            // Obtiene los IDs de las personas y los une en un string separado por ';'

            $predioArray['noVota'] = ($predioArray['vota']) ? '' : 1;
            unset($predioArray['vota']);
            $predioArray['cc_propietario']= $personasIds;
            $predioArray['cc_apoderado'] = ($predio->apoderado) ? $predio->apoderado->id : null;
            $predioArray['nombre_ap'] = ($predio->apoderado) ? $predio->apoderado->nombre : null;
            $predioArray['apellido_ap'] = ($predio->apoderado) ? $predio->apoderado->apellido : null;


            return $predioArray;
        })->toArray();


        return $array;
    }
    public function headings(): array
    {
        return [
            'id',
            'descriptor1',
            'numeral1',
            'descriptor2',
            'numeral2',
            'coeficiente',
            'noVota',
            'cc_propietario',
            'cc_apoderado',
            'nombre_ap',
            'apellido_ap'
        ];
    }
    public function styles(Worksheet $sheet)
    {
        // // Aplicar color a la celda B2
        // $sheet->getStyle('B11')->getFill()->setFillType(Fill::FILL_SOLID);
        // $sheet->getStyle('B11')->getFill()->getStartColor()->setARGB(Color::COLOR_BLUE);
        //  // Aplicar color a la celda B2
        //  $sheet->getStyle('C11')->getFill()->setFillType(Fill::FILL_SOLID);
        //  $sheet->getStyle('C11')->getFill()->getStartColor()->setARGB(Color::COLOR_BLUE);

        return [
            // Style the first row as bold text.
            1    => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'FFFF00'], // Color amarillo
                ],
            ],

        ];
    }
}
