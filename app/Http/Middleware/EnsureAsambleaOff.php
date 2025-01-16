<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAsambleaOff
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        if ($request->is('livewire/update')) {
            // No aplicar este middleware a la ruta livewire/update
            return $next($request);
        }
        /**   if (cache('asamblea')) {
         *      return redirect()->route('home')->withErrors(['msg' => 'No es posible si hay una asamblea en sesión']);
         *}*/


        return $next($request);
    }
}
