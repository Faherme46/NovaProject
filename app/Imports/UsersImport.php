<?php

namespace App\Imports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;

use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UsersImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {

        if (!array_key_exists('rol', $row)) {
            throw new \Exception('La columna "rol" es obligatoria');
        }
        $rol = strval($row['rol']);


        if (!array_key_exists('nombre', $row)) {
            throw new \Exception('La columna "nombre" es obligatoria');
        }
        if (!array_key_exists('username', $row)) {
            throw new \Exception('La columna "username" es obligatoria');
        }
        if (!array_key_exists('password', $row)) {
            throw new \Exception('La columna "password" es obligatoria');
        }

        $accepted = ['Admin', 'Operario', 'Lider','Terminal'];
        if (!in_array($rol, $accepted)) {

            throw new \Exception('Error2 : el usuario ' . $row['nombre'] . ' ' . $row['apellido'] . ' no tiene un rol valido');
        }

        $existingUser = User::where('username', $row['username'])->first();

        // Si el usuario ya existe, no crearlo de nuevo
        if ($existingUser) {
            $existingUser->update([
                'name' => $row['nombre'],
                'lastName' => (array_key_exists('apellido', $row)) ? $row['apellido'] : '',
                'telefono' => (array_key_exists('telefono', $row)) ? $row['telefono'] : '',
                'cedula' => (array_key_exists('cedula', $row)) ? $row['cedula'] : '',
                'password' => bcrypt($row['password']),
                'passwordTxt' => $row['password'],
                'roleTxt' => $rol
            ]);
            return null;
        }

        $user = new User([
            'name' => $row['nombre'],
            'lastName' => (array_key_exists('apellido', $row)) ? $row['apellido'] : '',
            'telefono' => (array_key_exists('telefono', $row)) ? $row['telefono'] : '',
            'cedula' => (array_key_exists('cedula', $row)) ? $row['cedula'] : '',
            'username' => $row['username'],
            'password' => bcrypt($row['password']),
            'passwordTxt' => $row['password'],
            'roleTxt' => $rol
        ]);
        $user->assignRole($rol);
        return $user;
    }
}
