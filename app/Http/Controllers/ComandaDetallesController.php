<?php

namespace App\Http\Controllers;

use App\Http\Requests\eventoRequest;
use App\Http\Requests\mesasRequest;
use App\Http\Requests\Storecomanda_detallesRequest;
use App\Http\Requests\Updatecomanda_detallesRequest;
use App\Models\cajas;
use App\Models\cajas_users;
use App\Models\comanda_detalles;
use App\Models\comanda_existencias;
use App\Models\comandas;
use App\Models\descuentos;
use App\Models\evento_cuentas;
use App\Models\eventos;
use App\Models\existencias;
use App\Models\precios;
use Barryvdh\Snappy\Facades\SnappyPdf;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use stdClass;

class ComandaDetallesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $r)
    {
        $comandas = comandas::with(['comanda_turnos', 'comanda_cajas', 'comanda_clientes', 'comanda_usuarios'])->findOrFail($r->comandasId);
        return view('comandas.detalle', [
            'p' => $comandas,
            'detalle' => $this->getComandaDetalle($comandas->id),
        ]);
    }
    public function getComandaDetalle($comandaId)
    {
        return comanda_detalles::with(['comandas', 'precios', 'descuentos', 'user_comanda', 'user_acepta'])
            ->where('comandas_id', '=', $comandaId)
            ->orderBy('id', 'DESC')
            ->get();
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Storecomanda_detallesRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $r)
    {
        try {
            $validator = Validator::make($r->all(), [
                'observacion' => 'nullable|string|max:200',
            ], [
                'observacion.max' => 'Solo se permiten 200 caracteres como máximo.',
            ]);

            //Verificar si la validación falla
            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Solo se permiten 200 caracteres como máximo.',
                    'type'   => 'danger',
                    'status' => false,
                    'errors' => $validator->errors(),
                ]);
            }

            $c = comandas::find(Crypt::decryptString($r->comanda));
            if (!$c->estado || $c->comprobante || $c->eliminada || $c->facturada || $c->anulada) {
                return response()->json(['message' => 'Error al agregar la comanda no esta disponible, actualice e intente de nuevo', 'type' => 'danger']);
            }

            $p = precios::find(Crypt::decryptString($r->precio));
            $cantidad = $r->cantidad;

            if ($cantidad <= 0) {
                return response()->json(['message' => 'Debe agregar una cantidad valida', 'type' => 'info']);
            }

            switch ($p->categorias_precios->token) {
                case 1101:
                    if ($this->validarExistencias($p, $cantidad, $r->lote)) {
                        $this->addDetalleInventario($c, $p, $cantidad, $r->lote, $r->observacion);
                    } else {
                        return response()->json(['message' => 'Error al agregar no se cumplen las existencias, intente agregarlo de nuevo desde otro lote', 'type' => 'danger']);
                    }
                    break;
                case 1105:
                    $this->addComandaDetalle($c, $p, $cantidad, $r->observacion);
                    break;
                default:
                    # code...
                    break;
            }

            return response()->json(['comanda_detalle' => $c->detalles_comanda, 'message' => 'Se agrego un producto.', 'type' => 'info']);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Error al agregar: ' . $th->getMessage(), 'type' => 'info']);
        }
    }
    #-----STORE APP-----
    public function storeApp(Request $r)
    {
        try {
            #Asignacion de datos POST a variables
            $cantidad  = intval($r->cantidad);
            $comandaId = Crypt::decryptString($r->comanda);
            $precioId  = $r->precio; #Crypt::decryptString($r->precio);
            $lote      = intval($r->lote);

            #Validacion de variables
            if ($cantidad  <= 0 || $cantidad  == null) throw new Exception('No se encontró el parámetro: cantidad.');
            if ($comandaId <= 0 || $comandaId == null) throw new Exception('No se encontró el parámetro: comanda.');
            if ($precioId  <= 0 || $precioId  == null) throw new Exception('No se encontró el parámetro: precio.');

            #Logica
            #---Comanda---
            $c = comandas::find($comandaId);
            if (!$c)
                throw new Exception('No se encontró la comanda solicitada.');

            if (!$c->estado || $c->comprobante || $c->eliminada || $c->facturada || $c->anulada)
                throw new Exception('Error al agregar la comanda, no está disponible, actualice e intente de nuevo.');
            #-------------

            #---Precio---
            $p = precios::with('categorias_precios')->find($precioId);
            if (!$p)
                throw new Exception('No se encontró el precio solicitado.');

            switch ($p->categorias_precios->token) {
                case 1101: #Productos con existencias
                    if ($this->validarExistencias($p, $cantidad, $lote))
                        $this->addDetalleInventario($c, $p, $cantidad, $lote, $r->observacion);
                    else
                        throw new Exception('Error al agregar el detalle a la comanda, no se cumplen las existencias, intente agregarlo de nuevo desde otro lote.');
                    break;
                case 1105: #Productos sin existencias
                    $this->addComandaDetalle($c, $p, $cantidad, $r->observacion);
                    break;
                default:
                    #Code...
                    break;
            }
            #------------

            return response()->json([
                'status' => true,
                'message' => 'Detalle de comanda agregado correctamente.',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }
    #-------------------
    public function crearDetalle(Request $r)
    {
        try {
            $c = comandas::find(Crypt::decryptString($r->comanda));
            if (!$c->estado || $c->comprobante || $c->eliminada || $c->facturada || $c->anulada) {
                return response()->json(['message' => 'Error al agregar la comanda no esta disponible, actualice e intente de nuevo', 'type' => 'danger']);
            }

            $precio = precios::find(Crypt::decryptString($r->precio));
            $cantidad = intval($r->cantidad);
            $precioModificado = $r->precioModificado;
            $observacion = $r->observacion;

            if ($cantidad <= 0) {
                return redirect()->back()->with('message', 'Debe agregar una cantidad valida')->with('type', 'info');
            }
            if ($precioModificado <= 0) {
                return redirect()->back()->with('message', 'Debe agregar una precio valido')->with('type', 'danger');
            }
            if (session('turno') && session('turno')->id == null)
                return redirect()->back()->with('message', 'Ocurrio un problema al identificar el turno, vuelva a iniciar sesion')->with('type', 'danger');

            $p = new comanda_detalles();
            $p->comandas_id = $c->id;
            $p->cantidad = $cantidad;
            $p->observaciones = $observacion ?? null;
            $p->precios_id = $precio->id;
            $p->precio = $precioModificado !== null ? $precioModificado : $precio->precio;
            $p->iva = $precio->iva;
            $p->advalorem = $precio->advalorem;
            $p->propina = $precio->propina;
            $p->users_comanda_id = Auth::user()->id;
            $p->turnos_id = session('turno')->id;
            $p->save();

            return response()->json(['comanda_detalle' => $c->detalles_comanda, 'message' => 'Se agrego un producto.', 'type' => 'info']);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Error al agregar: ' . $th->getMessage(), 'type' => 'info']);
        }
    }
    private function validarExistencias($precio, $cantidad, $lote = null)
    {
        foreach ($precio->detalle_producto as $v) {
            $c = 0;
            $c += $v->descargo * $cantidad;
            if ($lote != null) {
                $l = existencias::where('id', $lote)
                    ->where('productos_id', $v->productos_id)
                    ->where('estado', true)
                    ->where('existencia', '>=', $c)
                    ->get();
            } else {
                $l = existencias::where('productos_id', $v->productos_id)
                    ->where('estado', true)
                    ->where('existencia', '>=', $c)
                    ->orderBy('vencimiento', 'asc')
                    ->get();
            }

            return $l->count() > 0;
        }
    }
    private function addDetalleInventario($comanda, $precio, $cantidad, $lote, $observacion = null)
    {
        try {
            $p = $this->addComandaDetalle($comanda, $precio, $cantidad, $observacion);
            $valid = $this->descontarExistencia($precio, $p, $lote);
            if (!$valid) {
                $p->delete();
                return response()->json(['message' => 'Error al agregar no se cumplen las existencias, intente agregarlo de nuevo desde otro lote', 'type' => 'danger']);
            }
        } catch (\Throwable $th) {
            return throw $th;
        }
    }
    private function addComandaDetalle($comanda, $precio, $cantidad, $observacion = null)
    {
        try {
            if (session('turno') && session('turno')->id == null)
                throw new Exception('Ocurrio un problema al identificar el turno, vuelva a iniciar sesion');

            $p = new comanda_detalles();
            $p->comandas_id = $comanda->id;
            $p->cantidad = $cantidad;
            $p->observaciones = $observacion ?? null;
            $p->precios_id = $precio->id;
            $p->precio = $precio->precio;
            $p->iva = $precio->iva;
            $p->advalorem = $precio->advalorem;
            $p->propina = $precio->propina;
            $p->users_comanda_id = Auth::user()->id;
            $p->turnos_id = session('turno')->id;
            $p->save();
            return $p;
        } catch (\Throwable $th) {
            return throw $th;
        }
    }
    public function descontarExistencia($precio, $detalle_comanda, $lote = null)
    {
        try {
            foreach ($precio->detalle_producto as $v) {
                $c = 0;
                $c += $v->descargo * $detalle_comanda->cantidad;
                if ($lote != null) {
                    $l = existencias::where('id', $lote)
                        ->where('productos_id', $v->productos_id)
                        ->where('estado', true)
                        ->where('existencia', '>=', $c)
                        ->first();
                } else {
                    $l = existencias::where('productos_id', $v->productos_id)
                        ->where('estado', true)
                        ->where('existencia', '>=', $c)
                        ->orderBy('vencimiento', 'asc')
                        ->first();
                }

                if (!isset($l->id)) {
                    return false;
                }

                $l->existencia -= $c;
                $l->save();

                (new ComandaExistenciasController())->store($detalle_comanda->id, $l->id, $v->productos_id, $c);
            }
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }
    public function descargoComanda($id)
    {
        try {
            $c = comandas::find(Crypt::decryptString($id));

            if ($this->validarMontoComanda($id)) {
                $d = comanda_detalles::with('precios')
                    ->where('comandas_id', $c->id)
                    ->orderBy('id', 'DESC')
                    ->get();
                $this->descargo($d);
                $this->updateCuentaByCortesia($c->id);
            } else {
                return response()->json(['message' => 'Error al hacer el descargo no se cumplen las existencias, se cancela el descargo', 'type' => 'danger']);
            }

            return redirect()->route('cortesias.aplicar', ['id' => isset($c->id) ? Crypt::encryptString($c->id) : Crypt::encryptString(0), 'origen' => Crypt::encryptString(3)]);
        } catch (\Throwable $th) {
            return throw $th;
        }
    }
    //funcion para validar monto de comanda
    private function validarMontoComanda($id)
    {
        $c = comandas::find(Crypt::decryptString($id));
        return $c->sumComanda > 0;
    }
    //**funcion de descargo en detalle_comandas accediendo al descargo de precios productos mediante la relacion detalle_producto_precio */
    private function descargo($d)
    {
        foreach ($d as $detalle) {
            foreach ($detalle->detalle_producto_precio as $precio) {
                $cantidadDescargada = $precio->descargo * $detalle->cantidad;
                $existencia = existencias::where('productos_id', $precio->productos_id)
                    ->where('estado', true)
                    ->where('existencia', '>=', $cantidadDescargada)
                    ->orderBy('vencimiento', 'ASC')
                    ->first();

                if (!isset($existencia->id)) {
                    return response()->json(['message' => 'Error al agregar no se cumplen las existencias', 'type' => 'danger']);
                }

                $existencia->existencia -= $cantidadDescargada;
                $existencia->save();
                (new ComandaExistenciasController())->store($detalle->id, $existencia->id, $precio->productos_id, $cantidadDescargada);
            }
        }
    }
    //* fuction para actualizar el comprobante de comanda y actualizar el monto de la comanda en evento_cuentas
    private function updateCuentaByCortesia($comanda_id)
    {
        try {
            //se actualizara el comprobante de la comanda
            $comanda = comandas::find($comanda_id);
            $comanda->comprobante = true;
            $comanda->save();
            //* actualizar el monto de cuenta a $0.00 como se fue a cortesia
            $cuenta_comanda = evento_cuentas::where('origen_id', $comanda->id)->where('origen', 3);
            $cuenta_comanda->monto = 0;
            $cuenta_comanda->save();
            return $cuenta_comanda;
        } catch (\Throwable $th) {
            $th;
        }
    }

    public function anulacionProducto(Request $r)
    {
        try {

            if (!isset($r->detalles))
                return throw new Exception('No hay nada para anular. Seleccione un producto valido para anular');

            $detalles = $r->detalles;

            if (count($detalles) > 0) {
                foreach ($detalles as $d) {
                    $id = Crypt::decryptString($d);
                    if (isset($r['cantidad-' . $d])) {
                        $cantidad = $r['cantidad-' . $d];

                        if ($cantidad > 0) {
                            $p = comanda_detalles::find($id);

                            if ($cantidad > $p->cantidad) {
                                return redirect()->back()->with('message', 'Se detecto un intento de manipulacion del sistema, ingreso una cantidad mayor a lo comandado.')->with('type', 'danger');
                            }

                            if ($p->cantidad == $cantidad) {
                                $p->anulado = true;
                            } else {
                                $p->cantidad -= $cantidad;
                            }

                            $p->save();

                            (new AnulacionesDetalleComandaController())->store($cantidad, $p->id, $r->observacion ?? null);
                            $this->setExistencias($p, $cantidad);
                        }
                    }
                }
            }
            return redirect()->back()->with('message', 'Se completo el proceso de anulación.');
        } catch (\Throwable $th) {
            return redirect()
                ->back()
                ->with('message', 'Ocurrió un problema al completar la anulación. ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }
    public function separarProducto(Request $r)
    {
        try {
            $mesa = (int) $r->mesa;
            if (!($mesa > 0)) {
                return redirect()->back()->with('message', 'Debe agregar un numero de mesa valida.')->with('type', 'danger');
            }

            $c = comandas::where('mesa', $mesa)
                ->where('cajas_id', session('caja')->id)
                ->where('turnos_id', session('turno')->id)
                ->where('estado', true)
                ->where('facturada', false)
                ->where('comprobante', false)
                ->where('anulada', false)
                ->where('eliminada', false)
                ->first();
            if ($c == null) {
                $c = (new ComandasController())->nuevaComanda($mesa);
            }

            $detalles = $r->detalles;

            if (count($detalles) > 0) {
                foreach ($detalles as $d) {
                    $id = Crypt::decryptString($d);
                    $cantidad = (int) $r['cantidad-' . $d];

                    if ($cantidad > 0) {
                        $p = comanda_detalles::find($id);

                        if ($cantidad > $p->cantidad) {
                            return redirect()->back()->with('message', 'Se detecto un intento de manipulación del sistema, ingreso una cantidad mayor a lo comandado.')->with('type', 'danger');
                        }

                        if ($p->cantidad == $cantidad) {
                            $p->comandas_id = $c->id;
                        } else {
                            $p->cantidad -= $cantidad;

                            $cd = new comanda_detalles();
                            $cd->comandas_id = $c->id;
                            $cd->cantidad = $cantidad;
                            $cd->precio = $p->precio;
                            $cd->iva = $p->iva;
                            $cd->propina = $p->propina;
                            $cd->advalorem = $p->advalorem;
                            $cd->solicitud = $p->solicitud;
                            $cd->aceptacion = $p->aceptacion;
                            $cd->espera = $p->espera;
                            $cd->observaciones = $p->observaciones;
                            $cd->cancelado = $p->cancelado;
                            $cd->anulado = $p->anulado;
                            $cd->entregado = $p->entregado;
                            $cd->incremento_tiempo = $p->incremento_tiempo;
                            $cd->precios_id = $p->precios_id;
                            $cd->users_comanda_id = $p->users_comanda_id;
                            $cd->user_solicita_id = $p->user_solicita_id;
                            $cd->users_acepta_id = $p->users_acepta_id;
                            $cd->users_asigna_id = $p->users_asigna_id;
                            $cd->descuentos_id = $p->descuentos_id;
                            $cd->turnos_id = $p->turnos_id;
                            $cd->save();
                            foreach ($p->lotes as $l) {
                                $le = comanda_existencias::find($l->id);
                                if ($le != null) {
                                    if ($le->cantidd == $cd->cantidad) {
                                        $le->comanda_detalles_id = $cd->id;
                                    } else {
                                        $le->cantidad -= $cd->cantidad;
                                        $ln = new comanda_existencias();
                                        $ln->cantidad = $cd->cantidad;
                                        $ln->productos_id = $le->productos_id;
                                        $ln->existencias_id = $le->existencias_id;
                                        $ln->comanda_detalles_id = $cd->id;
                                        $ln->save();
                                    }
                                }
                                $le->save();
                            }
                        }
                        $p->save();
                    }
                }
            }
            return redirect()
                ->back()
                ->with('message', 'Se separaron los productos seleccionados a la mesa #' . $mesa);
        } catch (\Throwable $th) {
            return redirect()
                ->back()
                ->with('message', 'Ocurrió un problema al completar la separar. ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }
    //funcion para separar comanda desde evento ya seas para una existente o una nueva
    //TODO Crear Request para validar mesa y detalle creado
    //TODO validar que no se separe a si misma.

    public function separarProductoEvento(eventoRequest $r)
    {


        try {
            $mesa = (int) $r->mesa;
            $detalles = $r->detalles;

            if (!isset($detalles) || count($detalles) == 0)
                throw new Exception('No se selecciono ningún producto para mover.');

            if (!$mesa || $mesa <= 0) {
                throw new Exception('Debe agregar un numero de mesa valida.');
            }

            // Array <objeto> id:int, cantidad:int
            // cSpell:ignore oSeparacion, separacion, aceptacion
            $separacion = array();
            foreach ($detalles as $d) {
                $oSeparacion = new stdClass;
                $id = Crypt::decryptString($d);
                $cantidad = (int) $r['cantidad-' . $d];

                if (!$id || $id <= 0 || !$cantidad || $cantidad <= 0)
                    throw new Exception('Error al validar los parámetros de separacion, intente recargando la pagina y volviendo a generar la separacion.');

                $oSeparacion->id = $id;
                $oSeparacion->cantidad = $cantidad;
                array_push($separacion, $oSeparacion);
            }

            $evento_id = Crypt::decryptString($r->eventos_id);
            $comanda_id = Crypt::decryptString($r->comanda);

            if (!$comanda_id || $comanda_id <= 0 || !$evento_id || $evento_id <= 0)
                throw new Exception('Error al desencriptar');



            $nuevaComanda = (new ComandasController)->getComandaInterface($mesa)->first();

            $evento = eventos::find($evento_id);
            $c_actual = comandas::find($comanda_id);
            if (!$evento || $evento->id == null || $evento->id <= 0 || !$c_actual || $c_actual->id == null || $c_actual->id <= 0)
                throw new Exception('No se pudo obtener el evento o comanda');

            if (!$nuevaComanda || $nuevaComanda->id == null)
                $nuevaComanda = (new ComandasController())->nuevaComanda($mesa, 5, $c_actual->clientes_id, $c_actual->titular);

            if (!$nuevaComanda || $nuevaComanda->id == null || $nuevaComanda->id <= 0)
                throw new Exception('No se pudo crear la nueva comanda');


            (new EventoCuentasController())->eventoCuentas($nuevaComanda->id, $evento->id, 3);

            $error = "";
            foreach ($separacion as $d) {
                $p = comanda_detalles::find($d->id);

                if (!$p || $p->id == null) {
                    $error = $error . 'No se puede separar';
                    continue;
                }

                if ($d->cantidad > $p->cantidad) {
                    $error = $error . 'No se puede separar el producto: ' . $p->precios->detalle . ' porque la cantidad ingresada es mayor. ';
                    continue;
                }

                if ($p->cantidad == $d->cantidad)
                    $p->comandas_id = $nuevaComanda->id;

                else {
                    $p->cantidad -= $d->cantidad;

                    $cd = $p->replicate();
                    $cd->comandas_id = $nuevaComanda->id;
                    $cd->cantidad = $d->cantidad;
                    $cd->save();
                }
                $p->save();
            }

            return redirect()
                ->back()
                ->with('message', 'Se separaron los productos seleccionados a la mesa #' . $mesa);
        } catch (\Throwable $th) {
            return redirect()
                ->back()
                ->with('message', 'Ocurrió un problema al completar la separar. ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }

    private function setExistencias($detalle_comanda, $cantidad)
    {
        $ed = $detalle_comanda->precios->detalle_producto;
        foreach ($ed as $v) {
            $ce = comanda_existencias::where('comanda_detalles_id', $detalle_comanda->id)
                ->where('productos_id', $v->productos_id)
                ->first();
            if (isset($ce->id)) {
                $can = $v->descargo * $cantidad;
                if ($ce->cantidad < $can) {
                    return redirect()->back()->with('message', 'Se detecto un intento de manipulacion del sistema, ingreso una cantidad mayor a lo comandado.')->with('type', 'danger');
                }

                $e = existencias::find($ce->existencias_id);
                $e->existencia += $can;
                $e->save();
            }
        }
    }

    public function setDescuento($id, $descuento_id)
    {
        $detalle = comanda_detalles::where('comandas_id', $id)->get();
        foreach ($detalle as $d) {
            $this->setDescuentoComanda($d, $descuento_id);
        }
    }

    public function setDescuentoComanda($comanda_detalles, $descuento)
    {
        $p = precios::find($comanda_detalles->precios_id);
        //var_dump($p);
        if ($p->descuento) {
            $cd = comanda_detalles::find($comanda_detalles->id);
            $cd->descuentos_id = $descuento;
            $cd->save();
            //var_dump($cd);
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\comanda_detalles  $comanda_detalles
     * @return \Illuminate\Http\Response
     */
    public function destroy(comanda_detalles $comanda_detalles)
    {
        //
    }
    public function edicionCantidad(Request $r)
    {
        try {

            $detalles = $r->detalles;
            if (count($detalles) > 0) {
                foreach ($detalles as $d) {
                    $id = Crypt::decryptString($d);
                    if (isset($r['cantidad-' . $d])) {
                        $cantidad = intval($r['cantidad-' . $d]);

                        if ($cantidad > 0) {
                            $p = comanda_detalles::find($id);
                            if ($p && $p->comandas->facturada) {
                                return redirect()->back()->with('message', 'La comanda  ya ha sido facturada, no es posible editar la cantidad.')->with('type', 'danger');
                            }
                            if ($cantidad <= $p->cantidad)
                                return
                                    redirect()->back()->with('message', 'Se detecto un intento de manipulacion del sistema, ingreso una cantidad menor a lo comandado.')->with('type', 'danger');

                            $p->cantidad = $cantidad;
                            $p->save();
                        }
                    }
                }
            }

            return redirect()->back()->with('message', 'Se completó el proceso de edición de cantidad de producto.');
        } catch (\Throwable $th) {
            return redirect()->back()->with('message', 'Ocurrió un problema al completar la edicion de la cantidad. ' . $th->getMessage())->with('type', 'danger');
        }
    }



    public function comandasPrint(Request $r)
    {
        $comanda = comandas::with(['clientes', 'usuarios'])->findOrFail(Crypt::decryptString($r->id));
        $detalle = comanda_detalles::where('comandas_id', $comanda->id)->with([
            'user_comanda',
            'user_solicita',
            'user_asignado',
            'user_acepta',
            "precios",
            "lotes"
        ])->where('anulado', false)->get();
        return view('comandas.print', ['p' => $comanda, 'detalle' => $detalle]);
    }

    public function reporteVentas(Request $r)
    {
        return view('comandas.reportes.ventasForm', ['cajas' => $this->getUserCajas()]);
    }
    public function getUserCajas($caja = null)
    {
        if ($caja == null) {
            $userCajas = cajas_users::where('users_id', Auth::user()->id)->get();
            return cajas::whereIn('id', $userCajas->pluck('cajas_id'))->get();
        } else
            return cajas::whereIn('id', $caja)->get();
    }

    public function reporteVentasAcciones(Request $r)
    {
        $arrayCajas = $this->getArrayCajas($r->cajas_id);
        $cajas = $this->getUserCajas($arrayCajas);
        $venta = DB::table('ventas_usuarios')
            ->whereBetween('fecha', [$r->inicio, $r->fin])
            ->whereIn('cajas_id', $cajas->pluck('id'))
            ->get();
        $usuarios = cajas_users::whereIn('cajas_id', $cajas->pluck('id'))
            ->distinct('users_id')
            ->get();

        switch ($r->opcion) {
            case 1:
                $vistaName = 'comandas.reportes.ventasPreviewResumen';
                if (isset($r->detallado) && $r->detallado == 1)
                    $vistaName = 'comandas.reportes.ventasPreviewDetallado';
                return view($vistaName, [
                    'cajas' => $cajas,
                    'usuarios' => $usuarios,
                    'venta' => $venta,
                    'inicio' => Carbon::parse($r->inicio),
                    'fin' => Carbon::parse($r->fin),
                ]);
                break;
            case 2:
                $vistaName = 'comandas.reportes.ventasPrintResumen';
                if (isset($r->detallado) && $r->detallado == 1)
                    $vistaName = 'comandas.reportes.ventasPrintDetallado';


                $snap = SnappyPdf::loadView($vistaName, [
                    'cajas' => $cajas,
                    'usuarios' => $usuarios,
                    'venta' => $venta,
                    'inicio' => Carbon::parse($r->inicio),
                    'fin' => Carbon::parse($r->fin),
                ])
                    ->setPaper('letter')
                    ->setOption('margin-top', '10mm')
                    ->setOption('margin-bottom', '10mm')
                    ->setOption('margin-left', '10mm')
                    ->setOption('margin-right', '10mm');

                return $snap->inline('reporte_ventas_por_empleados.pdf');
                break;
            default:
                # code...
                break;
        }
    }
    /**
     * @param array $cajas_ids | Encriptados
     */
    public function getArrayCajas($cajas_ids)
    {
        $cajas = $cajas_ids;
        $selectedCajas = [];
        if (is_array($cajas) && count($cajas) > 0) {
            foreach ($cajas as $v) {
                $c = Crypt::decryptString($v);
                if ($c == 0) {
                    $selectedCajas = null;
                    break;
                }
                array_push($selectedCajas, $c);
            }
        }
        return $selectedCajas;
    }
}
