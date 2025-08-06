<?php

namespace App\Http\Controllers;

use App\Events\BodegasEvent;
use App\Http\Requests\Storerequisicion_detallesRequest;
use App\Http\Requests\Updaterequisicion_detallesRequest;
use App\Models\bodegas;
use App\Models\existencias;
use App\Models\requisicion_detalles;
use App\Models\requisiciones;
use Illuminate\Http\Request;
#Agregar
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;



class RequisicionDetallesController extends Controller
{
    private $table = 'requisicion_detalles';

    public function __construct()
    {
        $this->getTh($this->table, 'Requisicion Detalles');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Storerequisicion_detallesRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Storerequisicion_detallesRequest $r)
    {
         try {
            $productos_id = $r->productos_id;
            $requisiciones_id = $r->requisiciones_id;
            $cantidad_solicitada = $r->cantidad;
            $vencimiento = $r->vencimiento;
            $lotes_id = $r->lotes_id;
             // Obtener las existencias correspondientes al lote
                $existencias = existencias::where('id', $lotes_id)
                    ->where('productos_id', $productos_id)
                    ->where('vencimiento', $vencimiento)
                    ->where('estado', true)
                    ->first();

                if (!$existencias) {
                    return response([
                        'msj'  => 'Error al guardar el registro: No se encontraron existencias para el producto y lote especificados.',
                        'type' => 'danger',
                    ]);
                }

                // Valido que la cantidad solicitada sea menor o igual a la existencia disponible
                if ($cantidad_solicitada > $existencias->existencia || $cantidad_solicitada == 0) {
                    return response([
                        'msj'  => 'La cantidad solicitada es mayor que la existencia disponible || o la cantidad es igual a 0.',
                        'type' => 'danger',
                    ]);
                }

            // se crea o se actualiza el detalle de la requisición
            $detalleRequisicion = requisicion_detalles::where('productos_id', $productos_id)
                ->where('requisiciones_id', $requisiciones_id)
                ->where('vencimiento',$vencimiento)
                ->first();

            if ($detalleRequisicion) {
                // El detalle ya existe, actualiza la cantidad
                $detalleRequisicion->cantidad += $r->cantidad;
            } else {
                // El detalle no existe, crea uno nuevo
                $detalleRequisicion = new requisicion_detalles();
                $detalleRequisicion->productos_id = $productos_id;
                $detalleRequisicion->cantidad = $r->cantidad;
                $detalleRequisicion->users_id = Auth::id();
                $detalleRequisicion->requisiciones_id = $requisiciones_id;
                $detalleRequisicion->vencimiento = $vencimiento;
                $detalleRequisicion->lote_origen = $r->lotes_id;
            }

            $detalleRequisicion->save();

            while ($cantidad_solicitada > 0) {
                $existencias = existencias::where('bodegas_id', $detalleRequisicion->relacionRequisiciones->bodega_salida_id)
                    ->where('productos_id', $productos_id)
                    ->where('id', $lotes_id)
                    ->where('vencimiento', $vencimiento)
                    ->where('estado', true)
                    ->first();
                if ($existencias) {
                    $cantidad_descontar = $cantidad_solicitada <= $existencias->existencia ? $cantidad_solicitada : $existencias->existencia;

                    // Descontar la cantidad solicitada de las existencias
                    $existencias->existencia -= $cantidad_descontar;
                    if ($existencias->existencia == 0) {
                        $existencias->estado = false;

                    }
                    $existencias->save();
                } else {
                    // cuando se de el caso en que $existencias es nulo (puede lanzar una excepción o mostrar un mensaje de error)
                    return response([
                        'msj' => 'Error al guardar el registro: No se encontraron existencias para el producto en la bodega de salida.',
                        'type' => 'danger',
                    ]);
                }

                // Creo un registro en la tabla de existencias para representar la salida corregida
                $nuevaExistencia = new existencias();
                $nuevaExistencia->productos_id = $productos_id;
                $nuevaExistencia->existencia = $cantidad_descontar;
                $nuevaExistencia->cantidad_historial = $cantidad_descontar;
                $nuevaExistencia->precio_costo = number_format($existencias->precio_costo, 2, '.', '');
                $nuevaExistencia->bodegas_id = $detalleRequisicion->relacionRequisiciones->bodega_entrada_id;
                $nuevaExistencia->vencimiento = $existencias->vencimiento;
                $nuevaExistencia->requisicion_detalles_id = $detalleRequisicion->id;
                $nuevaExistencia->save();

                $cantidad_solicitada -= $cantidad_descontar;
            }

            return response([
                'msj' => 'Registro guardado correctamente.',
                'type' => 'success',
            ]);
        } catch (\Throwable $th) {
            return response([
                'msj' => 'Error al guardar el registro: ' . $th->getMessage(),
                'type' => 'danger',
            ]);
        }
    }
    public function RequisicionDetalleCompleta(Request $r)
    {
        try {
            $msj = '';
            $type = 'success';

            // Inicia una transacción
            DB::beginTransaction();

            // Buscamos la bodega general.
            $bodegaGeneral = bodegas::where('tipo', 1)->first();

            if (!$bodegaGeneral) {
                throw new \Exception('¡ERROR! No se ha configurado una bodega general.');
            }

            // Se valida la requisición por si ya se ha completado.
            $requisicion = Requisiciones::findOrFail($r->requisicion);

            if ($requisicion->estado == 2) {
                throw new \Exception('¡ERROR! Esta requisición ya ha sido completada previamente.');
            }

            $detalleRequisicion = requisicion_detalles::where('requisiciones_id', $requisicion->id)
            ->where('estado',true)
            ->first();
            if (!$detalleRequisicion) {
                    throw new \Exception('¡ERROR! No puedes completar un detalle de requisición vacío sin productos en el detalle.');
                }

                if (!$detalleRequisicion->relacionRequisiciones) {
                    throw new \Exception('¡ERROR! No puedes completar un detalle de requisición vacío sin productos en el detalle.');
                }

            // Se cambia el estado a completada.
            $requisicion->estado = 2;
            $requisicion->save();
             $bodegaSalidaId = $requisicion->relacionBodegasSalida->id;
            broadcast(new BodegasEvent(
            $bodegaSalidaId,
            Auth::user()->name . " se solicita autorizar la requisicion #" . $requisicion->id,
            2,"/requisiciones/autorizar/requisiciones",
            route('requisiciones.index')
        ));
            // Si se confirma la transacción
            DB::commit();

            return response([
                'msj' => 'Requisición completada exitosamente.',
                'type' => 'success',
            ]);
        } catch (\Throwable $exception) {
            // En caso de error, revertimos la transacción y proporcionamos detalles del error.
            DB::rollback();

            return response([
                'msj' => 'Error al completar la requisición: ' . $exception->getMessage(),
                'type' => 'danger',
            ]);
        }
    }
    public function devolverProducto(Request $r)
    {
          try {
        $detalle = requisicion_detalles::find($r->idEliminardetalle); // aquí encuentro la requisición en la tabla de requisicion_detalles
        $productos_id = $detalle->productos_id;
        $cantidad = $detalle->cantidad;
        $lotes_id = $detalle->lote_origen;
        $vencimiento = $detalle->vencimiento;
        $requisiciones_id = $detalle->requisiciones_id;
        $estado = $detalle->estado;
            $re_id = Crypt::encryptString($requisiciones_id);


        // Verifico si el detalle de la requisición existe
        $detalleRequisicion = requisicion_detalles::where('productos_id', $productos_id)
            ->where('requisiciones_id', $requisiciones_id)
            ->where('estado',true)
            ->first();

        if (!$detalleRequisicion) {
            return response([
                'message' => 'El producto no ha sido agregado al detalle de la requisición.',
                'type' => 'danger',
            ]);
        }

        // Validar que la cantidad a devolver no sea mayor que la cantidad en el detalle
        if ($cantidad > $detalleRequisicion->cantidad) {
            return redirect()
            ->route('existencias.index', ['existenciasId' => $re_id])
            ->with([
                'message' => 'La cantidad a devolver es mayor que la cantidad en el detalle de la requisición.',
                'type' => 'danger',
            ]);
        }

        // Actualizo la cantidad en el detalle de la requisición
        $detalleRequisicion->cantidad -= $cantidad;


        $detalleRequisicion->save();

        // Agregar la cantidad devuelta a las existencias en la bodega de entrada
        $existencias = existencias::where('bodegas_id', $detalleRequisicion->relacionRequisiciones->bodega_salida_id)
            ->where('productos_id', $productos_id)
            ->where('id', $lotes_id)
            ->where('vencimiento', $vencimiento)
            ->first();

        if ($existencias) {
            // Si existencias ya existe, simplemente aumento la cantidad
            // Cambia el estado a true
            $existencias->existencia += $cantidad;
            $existencias->estado = true;
        }


        $existencias->save();

        // Eliminar el detalle después de devolver el producto
        $detalle->delete();

        return redirect()
            ->route('existencias.index', ['existenciasId' => $re_id])
            ->with([
                'message' => 'Producto eliminado',
                'type' => 'success',
            ]);
    } catch (\Throwable $th) {
        return redirect()
            ->route('existencias.index', ['existenciasId' => $re_id])
            ->with([
                'message' => 'Error al devolver el producto: ' . $th->getMessage(),
                'type' => 'danger',
            ]);
    }
    }

    public function setMessage($msj, $type)
    {
        return response([
            'msj' => $msj,
            'type' => $type,
        ]);
    }

    public static function crearDetallesRequisicion($idProducto, $cantidad, $idUsuario, $idLotes, $idRequisicion,$idfechaVencimiento)
    {
        try {
            $p = new requisicion_detalles();
            $p->productos_id = $idProducto;
            $p->cantidad = $cantidad;
            $p->users_id = $idUsuario;
            $p->lotes_id = $idLotes;
            $p->requisiciones_id = $idRequisicion;
            $p->estado = false;
            $p->vencimiento = $idfechaVencimiento;
            $p->save();

            return $p;
        } catch (\Throwable $th) {
            return null;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\requisicion_detalles  $requisicion_detalles
     * @return \Illuminate\Http\Response
     */
    public function show(requisicion_detalles $requisicion_detalles)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\requisicion_detalles  $requisicion_detalles
     * @return \Illuminate\Http\Response
     */
    public function edit(requisicion_detalles $requisicion_detalles)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Updaterequisicion_detallesRequest  $request
     * @param  \App\Models\requisicion_detalles  $requisicion_detalles
     * @return \Illuminate\Http\Response
     */
    public function update(Updaterequisicion_detallesRequest $request, requisicion_detalles $requisicion_detalles)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\requisicion_detalles  $requisicion_detalles
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $r)
    {
        try {
            requisicion_detalles::destroy($r->idEliminardetalle); //aqui elimino la requisicion

            return redirect()
                ->back()
                ->with('message', 'Registro eliminado correctamente.')
                ->with('type', 'success');
        } catch (\Throwable $th) {
            return redirect()
                ->back()
                ->with('message', 'Error al eliminar el registro: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }

    public static function borrarRequisicionDetalles($requisicionId)
    {
        #return (requisicion_detalles::destroy($requisicion_detalle->id)) ? true : false;
        requisicion_detalles::where('requisiciones_id', '=', $requisicionId->id)->delete();
    }
}

