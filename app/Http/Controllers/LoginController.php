<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function logView()
    {
        return view('login');
    }

    public function authenticate(Request $request)
    {

        $messages = [
            'username.required' => 'El campo nombre es obligatorio.',
            'password.required' => 'El campo contraseña es obligatorio.',
        ];

        $credentials = $request->validate([
            'username' => ['required'],
            'password' => ['required'],
        ], $messages);

        $fileController=new FileController;
        $response=$fileController->importConf();
        if ($response!=200) {
            $message='No se ha encontrado el archivo de configuracion';
            return redirect()->back()->withErrors($message)->withInput();
        }


        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended(route('home'))->with('success','Inicio de sesion exitoso');
        } else {
            return back()->withErrors('El usuario o contraseña no son validos');
        }
    }

    

    public function logout(Request $request){
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('home')->with('warning','Sesion cerrada');
    }
}
