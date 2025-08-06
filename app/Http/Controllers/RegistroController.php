<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreregistroRequest;
use App\Http\Requests\UpdateregistroRequest;
use App\Models\comandas;
use App\Models\ordenes;
use App\Models\recepcion_salidas;
use App\Models\recepciones;
use App\Models\registro;
use App\Models\tipo_registros;

class RegistroController extends Controller
{

    /**
     * Crea el registro de conexión entre cuentas y comprobantes
     *
     * @param Integer $registro -> Id de la cuenta.
     * @param comprobantes $comprobante
     * @param Integer $tipo_registro -> Cuenta a la que pertenece.
     * @return void
     */
    public function createRegistro($registro, $comprobante, $tipo_registro)
    {
        $p = new registro;
        $p->registro = $registro;
        $p->comprobantes_id = $comprobante;
        $p->tipo_registros = $tipo_registro;
        if (!$p->save())
            throw new \ErrorException(`No se pudo registrar el comprobante ($comprobante), en el tipo de registro ($tipo_registro).`);

        //Desativar cuentas facturadas
        $this->desactivarCuenta($registro, $tipo_registro);
    }

    /**
     * Desactiva las cuentas al momento de ser creado el comprobante
     *
     * @param Integer $registro-> Id de la cuenta.
     * @param Integer $tipo -> Tipo de cuenta [Orden, Recepción, Comandas, Eventos]
     * @return void
     */
    public function desactivarCuenta($registro, $tipo)
    {
        switch ($tipo) {
                //Ordenes
            case 1:
                $h = ordenes::find($registro);
                $h->estado = false;
                $h->facturada = true;
                if (!$h->save())
                    throw new \ErrorException(`No se pudo desactivar la orden ($registro)`);
                break;
            case 2:
                $h = recepciones::find($registro);
                recepcion_salidas::where('recepciones_id', $h->id)->update(['facturada' => true, 'estado' => false]);
                if ($h->fecha_salida <= date('Y-m-d')) {
                    $h->estado = false;
                    (new RecepcionesController)->salidaHabitacion($h->habitaciones_id);
                }
                $h->facturada = true;
                if (!$h->save())
                    throw new \ErrorException(`No se pudo desactivar la orden ($registro)`);

                (new RecepcionCargosController)->setFacturado($h->id, $h->facturada);
                break;
            case 3:
                $h = comandas::find($registro);
                $h->estado = false;
                $h->facturada = true;
                if (!$h->save())
                    throw new \ErrorException(`No se pudo desactivar la comanda ($registro) aunque ya se realizo el comprobante`);
                break;
        }
    }

    /**
     * Activa las cuentas al momento de anular un comprobante
     *
     * @param Integer $registro -> Id de la cuenta.
     * @param Integer $tipo -> Tipo de cuenta [Orden, Recepción, Comandas, Eventos]
     * @return void
     */
    public function activarCuenta($registro, $tipo)
    {
        switch ($tipo) {
                //Ordenes
            case 1:
                $h = ordenes::find($registro);
                $h->estado = true;
                $h->facturada = false;
                if (!$h->save())
                    throw new \ErrorException(`No se pudo desactivar la orden ($registro)`);
                break;
            case 2:
                $h = recepciones::find($registro);
                if (!$h->estado) (new RecepcionesController)->salida_pospago($h, 'Creado automáticamente por anulación de de comprobante');
                $h->facturada = false;
                $h->comprobante = true;
                if (!$h->save())
                    throw new \ErrorException(`No se pudo activar la estadía ($registro)`);

                (new RecepcionCargosController)->setFacturado($h->id, $h->facturada);
                break;
            case 3:
                $h = comandas::find($registro);
                $h->estado = true;
                $h->facturada = false;
                if (!$h->save())
                    throw new \ErrorException(`No se pudo activar la comanda $registro`);
                break;
        }
    }
}
