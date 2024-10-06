<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Cache;

class EnsureAsambleaOn
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
        if (!Cache::get('asamblea')) {
            return redirect()->route('home')->withErrors(['msg' => 'No hay asamblea en sesion']);
        }

        return $next($request);
    }
}
