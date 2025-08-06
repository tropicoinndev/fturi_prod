<?php

namespace App\Http\Controllers;
use App\Models\evento_cuentas;
use Illuminate\Support\Facades\Auth;

class EventoCuentasController extends Controller
{
    /** se refactoriza usando solid la mayoria de funciones que sean reusables y limpias y mas legibles */
    public function eventoCuentas($cuenta, $eventoId, $origen)
    {

        try {
            $e = new evento_cuentas();
            $e->origen = $origen;
            $e->origen_id = $cuenta;
            $e->eventos_id = $eventoId;
            $e->users_id = Auth::id();
            $e->save();
            return $e;
        } catch (\Throwable $th) {
            throw $th;

        }
    }

    public function montoCuentas($numero_cuenta, $monto_cuenta, $origen)
    {
        try {
            $monto = evento_cuentas::where('origen_id', $numero_cuenta)
                ->where('origen', $origen)
                ->update(['monto' => $monto_cuenta]);

                return $monto;

        } catch (\Throwable $th) {
            return redirect()->back()
                ->with('message', 'Error al modificar el registro: ' . $th->getMessage())
                ->with('type', 'danger');

        }
    }


}
