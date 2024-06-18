<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
class ValidateLogin{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        if (!Auth::check()) {
            // Redirigir al usuario a la página de inicio de sesión si no está autenticado
            return redirect()->route('login')->withErrors(['msg' => 'Debes estar autenticado para acceder a esta página.']);
        }
        return $next($request);
    }
}
