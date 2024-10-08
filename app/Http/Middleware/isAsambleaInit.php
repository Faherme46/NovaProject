<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class isAsambleaInit
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (cache('predios_init',-1)==-1) {
            return redirect()->route('home')->withErrors(['msg' => 'La Asamblea no ha iniciado']);
        }
        return $next($request);
    }
}
