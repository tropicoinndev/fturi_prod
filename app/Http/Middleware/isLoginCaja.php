<?php

namespace App\Http\Middleware;

use App\Models\cajas;
use App\Models\turnos;
use Closure;
use Illuminate\Http\Request;

class isLoginCaja
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
        if (!session()->has('caja') || !session()->has('turno'))
            return redirect()->route('cajas.login');
        else {
            $c = cajas::find(session('caja')->id);
            if (!$c || !$c->estado)
                return redirect()->route('cajas.logout')->with('message', 'La caja que estaba utilizando fue bloqueada, en este momento no puede utilizarse. Intente mas tarde. (Si el problema continua consulte a soporte@tropicoinn.com.sv)');

            $t = turnos::find(session('turno')->id);
            if (!$t || !$t->estado )
                return redirect()->route('cajas.logout')->with('message', 'El turno que estaba utilizando se finalizo: ' . $t->cierrre);
        }

        return $next($request);
    }
}
