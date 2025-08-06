<?php

namespace App\Http\Controllers;

use App\Events\CajasEvent;
use App\Models\aplicacion_pagos;
use App\Models\clientes;

use App\Models\cobros;
use App\Models\comandas;
use App\Models\comprobantes;
use App\Models\detalle_cobros;
use App\Models\evento_cuentas;
use App\Models\eventos;
use App\Models\forma_pagos;
use App\Models\ordenes;
use App\Models\pago_anticipado;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class PagoAnticipadoController extends Controller
{
    private $table = 'pago_anticipados';

    public function __construct()
    {
        $this->getTh($this->table, 'Pagos anticipados');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view($this->table . '.index', [
            'th' => $this->th['index'],
            'p' => pago_anticipado::orderBy('id', 'DESC')->where('anulado', false)->paginate(15),
        ]);
    }
    public function search(Request $r)
    {
        return view($this->table . '.index', [
            'th' => $this->th['index'],
            'p' => pago_anticipado::where('id', 'ilike', '%' . $r->txtBusqueda . '%')
                ->orWhereIn('clientes_id', function ($query) use ($r) {
                    $query->select('id')
                        ->from('clientes')
                        ->where('nombre', 'ilike', '%' . $r->txtBusqueda . '%');
                })
                ->paginate(10),
            'txtBusqueda' => $r->txtBusqueda,
            'table' => $this->table,
            'data' => [
                'forma_pagos' => forma_pagos::orderBy('id', 'ASC')->get(),
                'clientes' => clientes::orderBy('id', 'DESC')->get(),
            ],
        ]);
    }
    private function validarOrdenes($ordenes)
    {
        try {
            if(isset($ordenes) && $ordenes != null){
                foreach($ordenes as $o)
                {
                    $ordenI = Crypt::decryptString($o);
                    $cobro = aplicacion_pagos::where('origen_id', $ordenI)
                    ->where('origen', 1)
                    ->where('estado', true)
                    ->first();
                    if($cobro){
                        return true;
                    }
                }
            }
        } catch (\Throwable $th) {
            return redirect()
                ->back()
                ->with('message', 'Ocurrió un problema al agregar: ' . $th->getMessage());
        }


    }
    private function validarComandas($comandas)
    {
        try {
            if (isset($comandas) && $comandas != null) {
                foreach ($comandas as $c) {
                    $comanda = Crypt::decryptString($c);
                    $cobroComanda = aplicacion_pagos::where('origen_id', $comanda)
                        ->where('origen', 3)
                        ->where('estado',true)
                        ->first();
                    if($cobroComanda > 0 ){

                        return true;
                    }
                }
            }
        } catch (\Throwable $th) {
            return redirect()
                ->back()
                ->with('message', 'Ocurrió un problema al agregar: ' . $th->getMessage());
        }
    }
    public function turnoOrden(int $ordenId)
    {
        if (!filter_var($ordenId, FILTER_VALIDATE_INT)) {
            throw new Exception('El ID de la orden debe ser un entero.');
        }
        try {
            $turno = session('turno');
            if (!$turno) {
                throw new Exception('No se encontró el turno en la sesión.');
            }
            $orden = ordenes::where('id', $ordenId)
            ->firstOrFail();
            if(is_null($orden->turnos_id)) {
            $orden->turnos_id = $turno->id;
            $orden->save();
            }
        } catch (\Throwable $th) {
            throw new Exception('Error al actualizar el turno: ' . $th->getMessage());
        }
    }
    public function turnoComanda(int $c)
    {
        if (!filter_var($c, FILTER_VALIDATE_INT)) {
            throw new Exception('El ID de la comanda debe ser un entero.');
        }

        try {
            $turno = session('turno');
            if (!$turno) {
                throw new Exception('No se encontró el turno en la sesión.');
            }
            $comanda = comandas::where('id', $c)
                ->firstOrFail();
            if(is_null($comanda->turnos_id)){
                $comanda->turnos_id = $turno->id;
                $comanda->save();
            }

        } catch (\Throwable $th) {
            throw new Exception('Error al actualizar el turno: ' . $th->getMessage());
        }
    }
    //todo refactorizar validaciones
    public function pagoAnticipadoEvento(Request $r)
    {
        try {

            $cl = Crypt::decryptString($r->eventos_id);
            $cliente = Crypt::decryptString($r->clientes_id);

            $forma = Crypt::decryptString($r->forma_pago);
            $monto = floatval(Crypt::decryptString($r->monto));
            if($r->ordenes != null){
            $ordenCobrada = $this->validarOrdenes($r->ordenes,$cl);
            if (isset($ordenCobrada)) {
                return redirect()->route('eventos.pago_anticipado', ['id' => $r->eventos_id])
                    ->with('message', 'Ya existe un pago anticipado para las cuentas de ordenes  del evento #  ' .$cl)->with('type', 'danger');
                }
            }
            if($r->comandas != null) {
            $comandaCobrada = $this->validarComandas($r->comandas);
            if (isset($comandaCobrada)) {
                return redirect()->route('eventos.pago_anticipado', ['id' => $r->eventos_id])
                    ->with('message', 'Ya existe un pago anticipado para la cuentas de comandas del evento #  ' .$cl)->with('type', 'danger');
                }
            }

            $pago_anticipado = $this->getPagosCuentasCliente($cl, $cliente);
            $cobros = $this->generaCobro($cliente, $r->comandas, $r->ordenes);
            if(isset($r->anticipos) && $r->anticipos != null && $monto != null ){
            ( new CobrosController())->asignar($cobros->id, $cl, $r->anticipos, $monto);

            }

            $concepto = 'PAGO DE CUENTAS DE EVENTOS.';

            if (!is_null($r->comandas) && count($r->comandas) > 0) {
                $concepto .= ' Comandas: ' . count($r->comandas) . '.';
            }

            if (!is_null($r->ordenes) && count($r->ordenes) > 0) {
                $concepto .= ' Ordenes: ' . count($r->ordenes) . '.';
            }

            if (is_null($r->comandas) || count($r->comandas) == 0) {
                $concepto;
            }

            if (is_null($r->ordenes) || count($r->ordenes) == 0) {
                $concepto;
            }
            if ($pago_anticipado->count() == 0 && $monto <= 0) {
                throw new Exception('No hay cuentas para el pago anticipado para este cliente.');
            }
            $p = new pago_anticipado();
            $p->monto = $monto;
            $p->monto_historico = $monto;
            $p->concepto = $concepto;
            $p->fecha = date('Y-m-d');
            $p->users_id = Auth::user()->id;
            $p->clientes_id = $cobros->clientes_id;
            $p->cajas_id = session('caja')->id;
            $p->turnos_id = session('turno')->id;
            $p->forma_pagos_id = $forma;
            $p->cobros_id = $cobros->id;
            $p->estado = false;
            $p->save();



            if (isset($r->comandas) && count($r->comandas) > 0) {
                foreach ($r->comandas as $comanda) {
                    $id_comanda = Crypt::decryptString($comanda);
                    if (!intval($id_comanda) || $id_comanda <= 0) {
                        throw new \Exception('No se encontraron comandas válidas.');
                    }
                    $c = comandas::find($id_comanda);
                    if (!$c) {
                        throw new \Exception('No se encontró la comanda con ID: ' . $id_comanda);
                    }

                    $this->turnoComanda($id_comanda);


                    (new PagosAplicacionController())->pagoCuentasEvento($c->id, $p->id, 3);
                }
            }
            if (isset($r->ordenes) && count($r->ordenes) > 0) {
                foreach ($r->ordenes as $orden) {
                    $orden_id = Crypt::decryptString($orden);
                    if (!intval($orden_id) || $orden_id <= 0) {
                        throw new \Exception('No se encontraron comandas válidas.');
                    }
                    $o = ordenes::find($orden_id);
                    if (!$o) {
                        throw new \Exception('No se encontró la orden con ID: ' . $orden_id);
                    }
                    $this->turnoOrden($orden_id);
                    (new PagosAplicacionController())->pagoCuentasEvento($o->orden, $p->id, 1);
                }
            }
            broadcast(
                new CajasEvent(
                    session('caja')->id,
                    Auth::user()->name . ' solicita el comprobante del pago anticipado#' . $p->id,
                    1,
                    route(
                        'pago_anticipados.resultado',
                        [
                            'id' => Crypt::encryptString($p->id),
                        ]),
                ),
            );

            return redirect()->route(
                'pago_anticipados.resultado',
                [
                    'id' => Crypt::encryptString($p->id),
                ]
            );
        } catch (\Throwable $th) {
            return redirect()
                ->route('eventos.pago_anticipado',['id'=> Crypt::encryptString($cl)])
                ->with('message', 'Ocurrio un problema al agregar ' . $th->getMessage());
        }
    }
    public function getPagosCuentasCliente(int $e, int $cliente)
    {
        if ($e <= 0 || $cliente <= 0) {
                throw new Exception('El ID del evento o del cliente no es válido.');
        }
        try {
            // Buscar el evento por su ID y asegurarse de que pertenezca al cliente
            $evento = eventos::where('id', $e)
                ->where('clientes_id', $cliente)
                ->firstOrFail();
            $cuentas = evento_cuentas::where('eventos_id', $evento->id)
                ->whereIn('origen', [1, 2, 3])
                ->get();

            return $cuentas;
        }catch (Exception $e) {

            throw new Exception('Error al obtener cuentas del cliente: ' . $e->getMessage());
        }
    }
     /*/**funcion para crear cobro de forma automatizada */
     private function generaCobro($p,$comandas, $ordenes)
     {
        try {
            $cliente = clientes::findOrFail($p);
            $token = $this->token($cliente->id);
            $cobro = new cobros();
            $cobro->titular = $cliente->nombre;
            $cobro->fecha = date('Y-m-d');
            $cobro->tipo_comprobante = $token;
            $cobro->cajas_id = session('caja')->id;
            $cobro->users_id = Auth::id();
            $cobro->clientes_id = $cliente->id;
            $cobro->save();

            if ($comandas != null && count($comandas) > 0)
            foreach ($comandas as $c) {
                $this->crearDetalleCobro(3, $c, $cobro->id);
            }


            if ($ordenes != null && count($ordenes) > 0) {
                foreach ($ordenes as $o) {
                    $this->crearDetalleCobro(1, $o, $cobro->id);
                }
            }
            return $cobro;
        } catch (\Throwable $th) {
        throw $th;
        }
     }
    //*crear el detalle de cobro
    private function crearDetalleCobro($origen, $cuenta, $cobro)
    {
        try {
            $detalleCobro = new detalle_cobros();
            $detalleCobro->origen = $origen;
            $detalleCobro->origen_id = Crypt::decryptString($cuenta);
            $detalleCobro->cobros_id = $cobro;
            $detalleCobro->save();
        } catch (\Throwable $th) {
            throw $th;
        }

    }

    //funcion de verificacion del token segun el cliente
    private function token($c){
        try {
            $cliente = clientes::find($c);
            switch ($cliente->tipo_cliente) {
                case 0:
                    $token = 7002;
                    break;
                case 1:
                    $token = 7001;
                    break;
            }
            return $token;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    //**resultado del pago anticipado

    public function resultado(Request $r)
    {
        try {
            $pago_id = Crypt::decryptString($r->id);
            $pago = pago_anticipado::with('clientes')->find($pago_id);

            return view('pago_anticipados.resultado', [
                'pago'   => $pago,
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }


    public function confirm($id)
    {
        try {
            return view('confirm', [
                'th' => $this->th['confirm'],
                'p' => pago_anticipado::findOrFail(Crypt::decryptString($id)),
            ]);
        } catch (\Throwable $t) {
            return redirect()
                ->back()
                ->with('message', 'Ocurrio un error (' . $t->getMessage() . ')')
                ->with('type', 'danger');
        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\pago_anticipado  $pago_anticipado
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $r)
    {
        try {
            $id = $r->id ?? null;
            if (empty(trim($id))) {
                return redirect()
                    ->route($this->table . '.index')
                    ->with('message', 'Ocurrió un error, el identificador del registro no cumple los requerimientos necesarios.')
                    ->with('type', 'danger');
            }
            $pago_id = Crypt::decryptString($id);
            $pa = pago_anticipado::find($pago_id);

            if (!$pa) {
                throw new Exception('Pago anticipado no encontrado.');
            }
            if ($pa->comprobantes[0]->anulacion->count() > 0 && !$pa->estado) {
                $pa->anulado = true;
                $pa->save();
                return redirect()
                    ->route($this->table . '.index')
                    ->with('message', 'Registro anulado con éxito')
                    ->with('type', 'success');
            } else {
                throw new Exception('No se puede anular, no se ha anulado su comprobante aún.');
            }
        } catch (\Throwable $th) {
            return redirect()
                ->back()
                ->with('message', 'Ocurrio un error (' . $th->getMessage() . ')')
                ->with('type', 'danger');
        }
    }

}
