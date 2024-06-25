<?php

namespace App\Http\Controllers;

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
    public function index()
    {
        return view('createUser');
    }

    public function createUser(Request $request)
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
            'cedula' => 'string|max:20|unique:users,cedula',
            'telefono' => 'string|max:15',
            'role' => 'required|in:Admin,Lider,Operario',
            'username' => 'required|string|max:255|unique:users,username',
            'password' => 'required|string',


        ], $messages);

        if ($validator->fails()) {
            return redirect()->route('users.index')
                ->withErrors($validator)
                ->withInput();
        }
        // Crear el nuevo usuario
        try {
            User::create([
                'name' => $request->name,
                'lastname' => $request->lastname,
                'cedula' => $request->cedula,
                'telefono' => $request->telefono,
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'passwordTxt' => $request->password
            ])->assignRole($request->role);

            return back()->with('success', 'usuario creado correctamente');
        } catch (Exception $e) {
            return back()->withErrors($e->getMessage())->withInput();
        }
    }

    public function importUsers()
    {
        try {
            Excel::import(new UsersImport, 'C:/Asambleas/usuarios.xlsx');
            return redirect()->route('users.index')->with('success','Archivo importado correctamente');
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
