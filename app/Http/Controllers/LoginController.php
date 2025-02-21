<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Terminal;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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




        if (Auth::attempt($credentials)) {

            $user = Auth::user();
            if ($user->getRoleNames()[0] == 'Terminal') {
                $ip = \Illuminate\Support\Facades\Request::ip();
                $hostname = gethostbyaddr($ip);
                Terminal::updateOrCreate(
                    ['ip_address' => $ip],
                    ['host' => $hostname, 'available' => true, 'user_id' => $user->id, 'user_name' => $user->name.' '.$user->lastName]
                );

                Log::channel('custom')->info('El Terminal {username} ha ingresado al sistema.', ['username' => Auth::getUser()->username]);
                $request->session()->regenerate();

                return redirect()->route('terminal')->with('success', 'Inicio de sesion exitoso');
            } else {

                Log::channel('custom')->info('El Usuario {username} ha ingresado al sistema.', ['username' => Auth::getUser()->username]);
                $request->session()->regenerate();

                return redirect()->intended(route('home'))->with('success', 'Inicio de sesion exitoso');
            }
        } else {
            return back()->withErrors('El usuario o contraseña no son validos');
        }
    }



    public function logout(Request $request)
    {
        $user = Auth::getUser();


        $user = Auth::user();
        if ($user->getRoleNames()[0] == 'Terminal') {
            $terminal=$user->terminal->delete();
        }
        Auth::logout();
        $request->session()->invalidate();

        $request->session()->regenerateToken();
        Log::channel('custom')->info('El Usuario {username} ha salido del sistema.', ['username' => $user->name]);

        return redirect()->route('home')->with('warning', 'Sesion cerrada');
    }
}
