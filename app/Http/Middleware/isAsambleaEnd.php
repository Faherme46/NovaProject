<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class isAsambleaEnd
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (cache('predios_end',-1)!=-1) {
            return redirect()->route('home')->withErrors(['msg' => 'La Asamblea ya terminó']);
        }
        return $next($request);
    }
}
