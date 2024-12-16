<?php

namespace App\Exports;

use App\Models\control;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ControlesWithRegistro implements FromArray,WithHeadings,WithStyles
{
    public function array(): array
    {
        $array = $this->buildArray();
        return $array;
    }

    public function buildArray()
    {
        $array = Control::with('predios')->get()->map(function ($control) {
            // Convierte el predio a array
            $predios = $control->predios;

            $prediosName = '';
            $controlArray = ['ID' => $control->id];
            if (!empty($predios)) {
                # code...
                $flag=$predios->count();
                foreach ($predios as $key => $predio) {
                    $prediosName .= $predio->getFullName() ;
                    $flag-=1;
                    if($flag>0){
                        $prediosName.="\n";

                    }
                }


                $controlArray['predios'] = $prediosName;
                $controlArray['coeficiente']=$control->sum_coef;
                $controlArray['votos']=$control->predios->count();
                $controlArray['TD']=($control->t_publico==='1')?'Publico':'Privado';
                $controlArray['cc_asistente']=($control->persona)?$control->persona->id:'';
                $controlArray['name_asistente']=($control->persona)?$control->persona->fullName():'';

            }

            return $controlArray;
        })->toArray();



        return $array;
    }
    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Control',
            'Predios',
            'Coeficiente',
            'Votos',
            'TD',
            'CC_Asistente',
            'Nombre_Asistente'
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
        $sheet->getStyle(['1','1','8','1000'])->getAlignment()->setVertical('top')->setWrapText(true);
        $sheet->getStyle(['3','1','8','1000'])->getAlignment()->setVertical('center');
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('G')->setAutoSize(true);
        return [
            // Style the first row as bold text.
            1    => [
                'font' => ['bold' => true]

            ],

        ];
    }
}
