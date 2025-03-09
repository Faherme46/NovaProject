<?php

namespace App\Exports;

use App\Models\Control;
use App\Models\Predio;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class EleccionesExport implements FromArray,WithEvents, WithHeadings,WithStyles
{

    /**
     * @return array
     */
    public function array(): array
    {
        $array = $this->buildArray();
        return $array;
    }

    public function buildArray()
    {
        $array = Control::with('predios')->get()->map(function ($control) {
            // Convierte el predio a array
        
            // Agrega el string de IDs al array del predio
            $predioArray=[
                'id' => $control->id,
                'cc_asistente'=> $control->cc_asistente,
                'nombre' => $control->persona->fullName(),
            ];
            $names='';
            if ($control->predios->count()==1) {
                $names=$control->predios[0]->getFullName();
            }else{
                foreach ($control->predios as $predio) {
                    $names.=$predio->getFullName()."\n";
                }
                
            }
            $predioArray['names']= rtrim( $names, "\n"); ;
            $predioArray+=[
                'coeficiente'=>$control->sum_coef,
                'votos'=>$control->predios_total,
                'td'=> ($control->t_publico)?'Publico':'Privado',
                'h_entrega'=>$control->h_entrega,
                'torre'=>$control->vote,
                'cc_voto'=> ($control->h_recibe!='-1')?$control->h_recibe:'EN BLANCO'];
            
            return $predioArray;
        })->toArray();


        return $array;
    }
    
    public function headings(): array
    {
        return [
            'ID',
            'Cedula',
            'Nombre',
            'Predios',
            'Coeficiente',
            'Votos',
            'TD',
            'Hora de voto',
            'Torre',
            'Voto'
            
        ];
    }
    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1    => [
                'font' => ['bold' => true],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                
                $sheet->getStyle('D')->getAlignment()->setWrapText(true);
                $cols=['A','B','C','D','E','F','G','H','I','J'];
                foreach ($cols as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }
            }
        ];
    }
}
