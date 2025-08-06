<?php

namespace App\Http\Controllers;


use App\Http\Controllers\ExistenciasController;
use App\Http\Controllers\RequisicionDetallesController;
use App\Http\Controllers\RequisicionesController;

#Agregar.
use App\Http\Requests\StorecomprasRequest;
use App\Http\Requests\UpdatecomprasRequest;
use App\Models\bodegas;
use App\Models\compras;
use App\Models\existencias;
use App\Models\lotes;
use App\Models\proveedores;
use App\Models\tipo_pagos;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Throwable;

class ComprasController extends Controller
{
    private $table = 'compras';

    public function __construct()
    {
        $this->getTh($this->table, 'Compras');
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
            'p' => compras::with('relacionProveedores')
                ->with('relacionTipoPagos')
                ->with('relacionUsuarios')
                ->orderBy('id', 'DESC')
                ->where('estado', true)
                ->where('completado', false)
                ->get(),
            'table' => $this->table,
            'data' => [
                'tipoPagos' => tipo_pagos::orderBy('tipo_pago', 'ASC')->get()
            ]
        ]);
    }
    //HISTORIAL COMPRAS
    public function historialCompras(Request $r)
    {

        $compras = compras::with(['relacionProveedores', 'relacionTipoPagos', 'relacionUsuarios'])
            ->orderBy('id', 'DESC')
            ->where('estado', true)
            ->where('completado', true)
            ->paginate(10);

        return view('compras.historialCompras', compact('compras'));
    }
    #API BUSCAR PROVEEDOR
    public function apiSearchProveedores(Request $r)
    {
        $proveedores = proveedores::where('proveedor', 'ilike', '%' . $r->txtProveedor . '%')->get();

        if (count($proveedores) === 0) {
            return response([
                'proveedores' => [],
                'mensaje' => 'PROVEEDOR NO ENCONTRADO SE LE SUGIERE AGREGARLO'
            ]);
        }

        return response([
            'proveedores' => $proveedores
        ]);
    }

    #API CAMBIAR ESTADO DE LA COMPRA.
    public function apiEstadoCompletado(Request $r)
    {
        try {
            $errores = 0;
            $msj = '';
            $type = 'success';

            #Bodega general
            $bodega_general = bodegas::where('tipo', 1)->first();
            if ($bodega_general == null)
                throw new Exception("No hay una bodega configurada como general.");

            #Validación de compra
            $compra = compras::find(Crypt::decryptString($r->id));
            if ($compra == null)
                throw new Exception("No se encontró la compra");

            if ($compra->completado)
                throw new Exception("Esta compra ya fue completada");

            #Seleccionar las columnas 'productos_id' y 'cantidad' de la tabla 'lotes', y esos id's guardarlos en la tabla de 'existencias'.
            $lotes = lotes::where('compras_id', Crypt::decryptString($r->id))->get();
            if ($lotes->count() == 0)
                throw new Exception("No se puede completar, compra vacía");

            //cSpell:ignore retencion, requisicion
            // aquí seria La sumatoria de los lotes para la compra
            $sumatorias = DB::table('lotes')
                ->select(DB::raw('SUM(total) as total'), DB::raw('SUM(iva) as iva'), DB::raw('SUM(retencion) as retencion'))
                ->where('compras_id', $compra->compra)
                ->first();

            if ($sumatorias == null)
                throw new Exception("Ocurrió un error al realizar la sumatoria de la compra.");

            $compra->total = $sumatorias->total;
            $compra->iva = $sumatorias->iva;
            $compra->retencion = $sumatorias->retencion;

            #1.- CREAR REQUISICIÓN:
            $fecha         = date('Y-m-d h:i:s');
            $solicitud     = 'Requisición automática realizada por la compra N° ' . $compra->compra; #Concatenamos el id de la compra.
            $bodegaEntrada = $bodega_general->id; #Se agregan por defecto a bodega general
            $bodegaSalida  = $bodega_general->id; #Se agregan por defecto a bodega general
            $idUsuarioCrea = Auth::id();
            $estado        = 3; #Se autoriza por defecto en bodega general #1 Activa, 2 Completa, 3 Autorizada, 4 Eliminada.

            $retornoObjetoRequisicion = RequisicionesController::crearRequisicion($fecha, $solicitud, $bodegaEntrada, $bodegaSalida, $idUsuarioCrea, $estado);


            #Validación si se crea la requisición
            if ($retornoObjetoRequisicion == null)
                throw new Exception("Ocurrió un error al realizar la requisición.");

            foreach ($lotes as $itemLotes) {
                #Agregar Id requisición y usar como parámetro $retornoObjetoRequisicion->id
                #2.- CREAR DETALLE DE REQUISICIÓN:
                $requisicion_detalle = RequisicionDetallesController::crearDetallesRequisicion(
                    $itemLotes->productos_id,
                    $itemLotes->cantidad,
                    $idUsuarioCrea,
                    $itemLotes->id,
                    $retornoObjetoRequisicion->id,
                    $itemLotes->fecha_vencimiento,

                );

                if ($requisicion_detalle == null) {
                    $errores++;
                    $msj = '¡ERROR! No se guardo el detalle de la requisicion.';
                    $type = 'danger';
                    break;
                }

                #3.- CREAR EXISTENCIAS: Guardar en la tabla 'existencias' los registros que coincidieron de la tabla 'lotes'.
                $errores += (ExistenciasController::crearExistencias(
                    $bodega_general->id, #Se agrega por defecto a la bodega general cuando son compras
                    $itemLotes->cantidad,
                    $itemLotes->cantidad,
                    $itemLotes->precio,
                    $itemLotes->productos_id,
                    $requisicion_detalle->id,
                    $itemLotes->fecha_vencimiento
                )) ? 0 : 1;
            }

            #Rollback.
            if ($errores > 0) {
                /*
                Refactorizacion de eliminación
                ExistenciasController::borrarExistencias($requisicion_detalle->id);
                RequisicionDetallesController::borrarRequisicionDetalles($requisicion_detalle);
                */
                RequisicionesController::borrarRequisicion($retornoObjetoRequisicion->id);
                throw new Exception("Ocurrió un error al realizar al completar la compra: se eliminaron las requisiciones");
            }

            #CAMBIAR ESTADO DE LA COMPRA
            $compra->completado = 1;
            $compra->save();

            return response([
                'msj' => $msj,
                'type' => $type,
                'lotes' => $lotes,
                'redirect_url' => route('requisiciones.printRequisicion', ['id' => Crypt::encryptString($retornoObjetoRequisicion->id)]),
            ]);
        } catch (\Throwable $th) {
            return response([
                'msj' => 'Error al cambiar estado de la compra: ' . $th->getMessage(),
                'type' => 'danger'
            ]);
        }
    }
    public function setMessage($msj, $type)
    {
        return response([
            'msj' => $msj,
            'type' => $type
        ]);
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
     * @param  \App\Http\Requests\StorecomprasRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorecomprasRequest $r)
    {
        $p = new compras;
        $p->proveedores_id = $r->proveedores_id;
        $p->fecha          = date('Y-m-d h:m:i');
        $p->fecha_factura  = $r->fecha_factura;
        $p->total          = 0.00;
        $p->iva            = 0.00;
        $p->retencion      = 0.00;
        $p->fovial         = $r->fovial;
        $p->correlativo    = $r->correlativo;
        $p->serie          = $r->serie;
        $p->tipo_pagos_id  = $r->tipo_pagos_id;
        $p->users_id       = Auth::user()->id;
        $p->requisiones_id = null;
        $p->estado         = true;
        $p->save();

        return redirect()->route('lotes.index', [
            'lotesId' => $p->id,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\compras  $compras
     * @return \Illuminate\Http\Response
     */
    public function show(compras $compras)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\compras  $compras
     * @return \Illuminate\Http\Response
     */
    public function edit(compras $compras)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatecomprasRequest  $request
     * @param  \App\Models\compras  $compras
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatecomprasRequest $request, compras $compras)
    {
        //
    }

    public function confirm($id)
    {
        try {
            return view('confirmCompras', [
                'th' => $this->th['confirmCompra'],
                'p' => compras::findOrFail(Crypt::decryptString($id))
            ]);
        } catch (\Throwable $th) {
            return redirect()->back()
                ->with('message', 'Ocurrio un error: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\compras  $compras
     * @return \Illuminate\Http\Response
     */
    public function destroy(compras $compras)
    {
        //
    }

    #UNA COMPRA NO SE ELIMINA, SOLO SE CAMBIA DE ESTADO.
    public function status(Request $r)
    {
        try {
            $p = compras::findOrFail(Crypt::decryptString($r->id));
            $p->estado = !$p->estado;
            $p->save();

            return to_route($this->table . '.index')
                ->with('message', 'Compra eliminada correctamente: ' . $p->correlativo)
                ->with('type', 'success');
        } catch (\Throwable $th) {
            return to_route($this->table . '.index')
                ->with('message', 'Error al eliminar la compra: ' . $th->getMessage())
                ->with('type', 'error');
        }
    }
}
