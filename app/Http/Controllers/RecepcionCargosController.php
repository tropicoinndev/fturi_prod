<?php

namespace App\Http\Controllers;

use App\Models\recepcion_cargos;

class RecepcionCargosController extends Controller
{

    /**
     * Realiza el cambio cuando se factura la recepción, o cuando se anula la recepción a la que pertenece
     *
     * @param Integer $recepciones_id
     * @param boolean $estado
     * @return void
     * cSpell:ignore eliminacion, recepcion
     */
    public function setFacturado($recepciones_id, $facturado = true)
    {
        return recepcion_cargos::where('recepciones_id', $recepciones_id)
            ->where('eliminacion_users_id', null)
            ->update(['facturado' => $facturado, 'estado' => !$facturado]);
    }
}
