<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UserExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return User::select('id','name','lastName','cedula','telefono','username','passwordTxt','roleTxt')->whereNot('username','ehernandez')->get();
    }
    public function headings(): array
    {
        return [
            'Id',
            'nombre',
            'apellido',
            'cedula',
            'telefono',
            'username',
            'password',
           ' rol'
        ];
    }
}
