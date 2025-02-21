<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class NoEleccionesMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!(cache('asamblea'))?cache('asamblea')['eleccion']:true) {
            Auth::logout();
            return redirect()->route('login')->with('warning','Esta asamblea no es Electoral');
        }
        return $next($request);
    }
}
