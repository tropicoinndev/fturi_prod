<?php

namespace App\Http\Middleware;

use App\Models\cajas;
use Closure;
use Illuminate\Http\Request;

class CajasMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (!session('caja') || !session('turno'))
            return redirect()->route('cajas.login');

        return $next($request);
    }
}
