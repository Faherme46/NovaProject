<?php

namespace App\Http\Controllers;

use App\Exports\UserExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use  Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Validators\ValidationException;
use App\Imports\UsersImport;

use Maatwebsite\Excel\Facades\Excel;

use App\Models\User;
use Exception;

class UsersController extends Controller
{

    public function createUser(Request $request)
    {
        $validator = $this->validateFields($request,false);
        if ($validator == 400) {
            return back()->withErrors('El nombre de usuario ya esta en uso')->withInput();
        } elseif ($validator != 200) {
            return back()->withErrors($validator)->withInput();
        }
        // Crear el nuevo usuario
        try {
            User::create([
                'name' => ucwords(strtolower($request->name)),
                'lastname' => ucwords(strtolower($request->lastname)),
                'cedula' => $request->cedula,
                'telefono' => $request->telefono,
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'passwordTxt' => $request->password,
                'roleTxt' => $request->role
            ])->assignRole($request->role);
            $this->exportUsers();
            return back()->with('success', 'Usuario creado correctamente');
        } catch (Exception $e) {
            return back()->withErrors($e->getMessage())->withInput();
        }
    }
    public function updateUser(Request $request)
    {
        $validator = $this->validateFields($request,true);
        if ($validator == 400) {
            return back()->withErrors('El nombre de usuario ya esta en uso')->withInput();
        } elseif ($validator != 200) {
            return back()->withErrors($validator)->withInput();
        }
        // Crear el nuevo usuario
        try {
            $user=User::find($request->idUser);
            $user->update([
                'name' => ucwords(strtolower($request->name)),
                'lastname' => ucwords(strtolower($request->lastname)),
                'cedula' => $request->cedula,
                'telefono' => $request->telefono,
                'password' => Hash::make($request->password),
                'passwordTxt' => $request->password,
                'roleTxt'=>$request->role
            ]);
            $user->assignRole($request->role);

            $this->exportUsers();
            return back()->with('success', 'Usuario actualizado correctamente');
        } catch (Exception $e) {
            return back()->withErrors($e->getMessage())->withInput();
        }
    }

    public function validateFields(Request $request, $updating)
    {
        // Validar los datos del formulario
        // Mensajes de error personalizados
        $messages = [
            'nombre.required' => 'El nombre es obligatorio.',
            'apellido.required' => 'El apellido es obligatorio.',
            'cedula.required' => 'La cédula es obligatoria.',
            'cedula.unique' => 'La cédula ya está en uso.',
            'telefono.required' => 'El teléfono es obligatorio.',
            'username.required' => 'El nombre de usuario es obligatorio.',
            'username.unique' => 'El nombre de usuario ya está en uso.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.confirmed' => 'La confirmación de la contraseña no coincide.',
            'role.in' => 'El rol seleccionado no es válido. Debe ser Admin, Lider u Operario.',
        ];

        // Validar los datos del formulario
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'lastname' => 'string|max:255',
            'cedula' => 'string|max:20',
            'telefono' => 'string|max:15',
            'role' => 'required|in:Admin,Lider,Operario',
            'username' => 'required|string|max:255',
            'password' => 'required|string',
        ], $messages);

        if (!$updating) {
            $users = User::pluck('username')->toArray();
            if (in_array($request->username, $users)) {
                return 400;
            }
        }

        if ($validator->fails()) {

            return $validator;
        } else {
            return 200;
        }
    }

    public function deleteUser(Request $request)
    {
        $id = $request->id;
        $user = User::findOrFail($id);
        $user->delete();
        $this->exportUsers();
        return back()->with('success', 'Usuario eliminado con exito');
    }
    public function importUsers()
    {
        try {
            Excel::import(new UsersImport, 'C:/Asambleas/usuarios.xlsx');
            return redirect()->back()->with('success', 'Archivo importado correctamente');
        } catch (ValidationException $e) {
            // Manejar excepciones específicas de validación de Excel
            $failures = $e->failures();
            return back()->withErrors('Error 2: ' . $failures[1]);
            //Excepcion por archivo inexistente
        } catch (\Illuminate\Contracts\Filesystem\FileNotFoundException $e) {
            return back()->withErrors('Error: No se encontró la hoja de cálculo');
        } catch (Exception $e) {

            // Manejar cualquier otra excepción
            return back()->withErrors($e->getMessage());
        }
    }

    public function exportUsers()
    {

        try {
            $export = new UserExport();
            $excel = Excel::store($export, 'usuarios.xlsx', 'externalUsers');
            return redirect()->back();
        } catch (ValidationException $e) {
            // Manejar excepciones específicas de validación de Excel
            $failures = $e->failures();
            return back()->withErrors('Error: ' . $failures[1]);
            //Excepcion por archivo inexistente
        } catch (\Illuminate\Contracts\Filesystem\FileNotFoundException $e) {
            return back()->withErrors('Error: No se encontró la hoja de cálculo');
        } catch (Exception $e) {

            // Manejar cualquier otra excepción
            return back()->withErrors($e->getMessage());
        }
    }
}
