<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;

use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpParser\Node\Expr\Throw_;

class UsersImport implements ToModel,WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $rol=strval($row['rol']);


        if(!$row['nombre']){
            dd();
            throw new \Exception('La columna "nombre" es obligatoria');
        }
        if(!$row['username']){
            throw new \Exception('La columna "username" es obligatoria');
        }
        if(!$row['password']){
            throw new \Exception('La columna "password" es obligatoria');
        }
        if(!$rol){
            throw new \Exception('La columna "rol" es obligatoria');
        }

        $accepted=['Admin','Operario','Lider'];
        if(!in_array($rol,$accepted)){
            throw new \Exception('Error: el usuario '.$row['nombre'].' '.$row['apellido'].' no tiene un rol valido');
        }

        $existingUser = User::where('username', $row['username'])->first();

        // Si el usuario ya existe, no crearlo de nuevo
        if ($existingUser) {
            return null;
        }

        $user=new User([
            'name'=>$row['nombre'],
            'lastName'=>$row['apellido'],
            'telefono'=>$row['telefono'],
            'cedula'=>$row['cedula'],
            'username'=>$row['username'],
            'password'=>bcrypt($row['password']),
            'passwordTxt'=>$row['password'],

        ]);
        $user->assignRole($rol);
        return $user;
    }
}
