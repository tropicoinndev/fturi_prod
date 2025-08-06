<?php

namespace App\Http\Controllers;

use App\Events\CajasEvent;
use App\Exports\ViewToExcel;
use App\Http\Requests\StoreordenesRequest;
use App\Http\Requests\UpdateordenesRequest;
use App\Models\cajas;
use App\Models\clientes;
use App\Models\detalle_ordenes;

#Agregar
use App\Models\eventos;
use App\Models\ordenes;
use Barryvdh\Snappy\Facades\SnappyPdf;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;
use Throwable;

class OrdenesController extends Controller
{
    private $table = 'ordenes';

    public function __construct()
    {
        $this->getTh($this->table, 'Ordenes');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (session('caja') == null) {
            return redirect()->route('cajas.login');
        }

        return view($this->table . '.index', [
            'th' => $this->th['index'],
            'p' => ordenes::detalle(session('caja')->id), #El metodo: detalle() viene del modelo.
            'table' => $this->table,
        ]);
    }
    /***historial historico de ordenes */
    public function historialOrdenes()
    {
        try {
            $ordenes = ordenes::with(['clientes', 'cajas', 'detalle_orden'])
                ->where('estado', 'true')
                ->orderBy('id', 'asc')
                ->get();

            $ordenesDetalle = detalle_ordenes::with(['descuentos', 'servicios', 'ordenes'])->get();

            return view('ordenes.ordenesHistorial', [
                'ordenes' => $ordenes,
                'ordenes_detalle' => $ordenesDetalle,
            ]);
        } catch (\Throwable $th) {
            return 'Ocurrió un error al cargar este panel de ordenes historia, (Tome una captura a esta pantalla y envíe a informatica@tropicoinn.com.sv) error: ' . $th->getMessage();
        }
    }
    /***cambiar comprobante desde ordenes historia */
    public function cambiarComprobante(Request $r)
    {
        try {
            $ordenId = $r->input('ordenId');
            $orden = Ordenes::findOrFail(Crypt::decryptString($ordenId));
            $orden->comprobante = $orden->comprobante == 1 ? 0 : 1;
            $orden->save();
            return response()->json([
                'type' => 'success',
                'msj' => 'Comprobante cambiado correctamente.',
            ]);
        } catch (\Exception $e) {
            // Manejar la excepción aquí
            return response()->json(
                [
                    'type' => 'error',
                    'msj' => 'Error al cambiar el comprobante',
                    'error' => $e->getMessage(),
                ],
                500,
            );
        }
    }
    /**bloquear desde ordenes evento */
    public function bloquear($id)
    {
        try {
            $orden = ordenes::findOrFail(Crypt::decryptString($id));
            $detalleOrden = detalle_ordenes::where('ordenes_id', $orden->orden)->get();
            if ($orden->comprobante == true) {
                return back()->with('message', 'ya se  bloqueó la orden Nº ' . $orden->orden)->with('type', 'info');
            }
            if ($detalleOrden->count() > 0) {
                $orden->comprobante = true;
                $orden->save();
                (new EventoCuentasController())->montoCuentas($orden->orden, $orden->sumOrden, 1);
                return back()->with('message', 'Se bloqueó la orden Nº ' . $orden->orden)->with('type', 'success');
            } else {
                return back()->with('message', 'No se puede bloquear la orden Nº ' . $orden->orden . ' porque no tiene productos asociados.')->with('type', 'warning');
            }
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('type', 'danger')
                ->with('message', $e->getMessage());
        }
    }
    public function search(Request $request)
    {
        if (isset($request->txtBusqueda)) {
            $p = ordenes::where('numero_orden', 'ilike', '%' . $request->txtBusqueda . '%')
                ->orWhere('fecha', 'like', '%' . $request->txtBusqueda . '%')
                ->orWhere('titular', 'like', '%' . $request->txtBusqueda . '%')
                ->paginate();

            return view($this->table . '.index', [
                'th' => $this->th['index'],
                'p' => $p,
                'txtBusqueda' => $request->txtBusqueda,
                'data' => [
                    'clientes' => clientes::orderBy('nombre', 'ASC')->where('estado', true)->get(),
                    'cajas' => cajas::orderBy('caja', 'ASC')->where('estado', true)->get(),
                ],
            ]);
        } else {
            return to_route($this->table . '.index');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view($this->table . '.create', [
            'th' => $this->th['create'],
            'table' => $this->table,
            'data' => [
                'clientes' => clientes::orderBy('nombre', 'ASC')->where('estado', true)->get(),
                'cajas' => cajas::orderBy('caja', 'ASC')->where('estado', true)->get(),
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreordenesRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreordenesRequest $request)
    {
        if (session('caja') == null) {
            return redirect()->route('cajas.login');
        }

        $p = new ordenes();
        $p->fecha = date('Y-m-d');
        $p->titular = $request->titular;
        $p->clientes_id = $request->cliente == 0 ? null : $request->cliente;
        $p->cajas_id = session('caja')->id;
        $p->turnos_id = session('turno')->id;
        $p->descripcion = $request->descripcion;
        $p->save();

        return redirect()->route('detalle_ordenes.index', [
            'detalleOrdenesId' => $p->id,
        ]);
    }
    public function ordenEvento(Request $r)
    {
        try {
            $eventoId = Crypt::decryptString($r->input('eventos_id'));
            $evento = eventos::with('clientes')->find($eventoId);
            if (!isset($eventoId) || $eventoId == 0) {
                return redirect()->back()->with('message', 'No se encontro evento.')->with('type', 'danger');
            }
            //pdt corregir esto
            $p = new ordenes();
            $p->fecha = date('Y-m-d');
            $p->titular =  $evento->titular ?? null;
            $p->clientes_id = $evento->clientes_id ?? null;
            $p->cajas_id = session('caja')->id;
            $p->tipo_orden = 5;
            $p->save();
            (new EventoCuentasController())->eventoCuentas($p->orden, $eventoId, 1);
            return redirect()->route('eventos.detalle_orden', [
                'id' => $p->id,
                'eventoId' => $evento->cid,
            ]);
        } catch (\Throwable $th) {
            return redirect()
                ->route($this->table . '.index')
                ->with('message', 'Error al guardar el registro: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }
    public function getClientes()
    {
        return response()->json([
            'clientes' => DB::table('clientes')->get()->last()->nombre,
        ]);
    }

    public function prefacturacion()
    {
        return view('ordenes.prefacturacion');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ordenes  $ordenes
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            return view($this->table . '.show', [
                'th' => $this->th['show'],
                'p' => ordenes::findOrFail(Crypt::decryptString($id)),
                'table' => $this->table,
                'data' => [
                    'clientes' => clientes::orderBy('nombre', 'ASC')->where('estado', true)->get(),
                    'cajas' => cajas::orderBy('caja', 'ASC')->where('estado', true)->get(),
                ],
            ]);
        } catch (\Throwable $th) {
            return to_route($this->table . '.index')
                ->with('message', 'Error al encontrar el registro: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ordenes  $ordenes
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            return view($this->table . '.edit', [
                'th' => $this->th['edit'],
                'p' => ordenes::findOrFail(Crypt::decryptString($id)),
                'table' => $this->table,
                'data' => [
                    'clientes' => clientes::orderBy('nombre', 'ASC')->where('estado', true)->get(),
                    'cajas' => cajas::orderBy('caja', 'ASC')->where('estado', true)->get(),
                ],
            ]);
        } catch (\Throwable $th) {
            return to_route($this->table . '.index')
                ->with('message', 'Error al encontrar el registro: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateordenesRequest  $request
     * @param  \App\Models\ordenes  $ordenes
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateordenesRequest $request)
    {
        try {
            $p = ordenes::findOrFail($request->id_orden);


            $p->clientes_id = $request->clientes_id;

            $p->save();

            return redirect()
                ->route('detalle_ordenes.index', [
                    'detalleOrdenesId' => $p->id,
                ])
                ->with('message', 'Cliente agregado correctamente.')
                ->with('type', 'success');
        } catch (\Throwable $th) {
            return to_route($this->table . '.index')
                ->with('message', 'Error al actualizar el registro: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }

    public function updateTitular(Request $request)
    {
        try {
            $p = ordenes::findOrFail($request->id_orden);

            $p->titular = $request->titular;
            $p->save();

            return redirect()
                ->route('detalle_ordenes.index', [
                    'detalleOrdenesId' => $p->id,
                ])
                ->with('message', 'Titular editado correctamente.')
                ->with('type', 'success');
        } catch (\Throwable $th) {
            return to_route($this->table . '.index')
                ->With('message', 'Error al actualizar el registro: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }

    public function confirm($id)
    {
        try {
            return view('confirmOrdenes', [
                'th' => $this->th['confirmOrden'],
                'p' => ordenes::findOrFail(Crypt::decryptString($id)),
            ]);
        } catch (\Throwable $t) {
            return redirect()
                ->back()
                ->with('message', 'Ocurrio un error (' . $t->getMessage() . ')')
                ->with('type', 'danger');
        }
    }



    #UNA ORDEN NO SE ELIMINA, SOLO SE CAMBIA DE ESTADO.
    public function status(Request $r)
    {
        try {
            $p = ordenes::findOrFail(Crypt::decryptString($r->id));
            $p->estado = !$p->estado;
            $p->save();

            return to_route($this->table . '.index')
                ->with('message', 'Orden anulada correctamente: ' . $p->titular)
                ->with('type', 'primary');
        } catch (\Throwable $th) {
            return response()->json([
                'msj' => 'Error al anular la orden: ' . $th->getMessage(),
                'type' => 'danger',
            ]);
        }
    }

    public function comprobante(Request $r)
    {
        try {
            if (!session('caja') || !session('turno')) {
                return redirect()->route('cajas.login');
            }

            $p = ordenes::findOrFail(Crypt::decryptString($r->id));
            // Verificar si el detalle de la orden está vacío
            if ($p->detalle_orden->isEmpty()) {
                return redirect()
                    ->route('detalle_ordenes.index', [
                        'detalleOrdenesId' => $p->id,
                    ])
                    ->with('message', 'No se puede completar la orden sin ningún concepto.')
                    ->with('type', 'danger');
            }
            $p->comprobante = true;
            $p->save();
            broadcast(
                new CajasEvent(
                    session('caja')->id,
                    Auth::user()->name . ' solicita el comprobante de la orden #' . $p->orden,
                    1,
                    route('cobros.create', [
                        'origen' => Crypt::encryptString(1),
                        'origen_id' => $p->id,
                        'tipo_comprobante' => Crypt::encryptString(7002),
                    ]),
                ),
            );
            return to_route($this->table . '.index')
                ->with('message', 'Orden completada: ' . $p->titular)
                ->with('type', 'primary')
                ->with('redirect', route('ordenes.container', ['id' => $r->id]));
        } catch (\Throwable $th) {
            return to_route($this->table . '.index')
                ->with('message', 'Error al anular la orden: : ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }
    public function desbloquear(Request $r)
    {
        try {
            $p = ordenes::findOrFail(Crypt::decryptString($r->id));
            $p->comprobante = false;
            $p->save();
            return redirect()
                ->back()
                ->with('message', 'Se desbloqueo la orden Nº ' . $p->orden);
        } catch (\Throwable $th) {
            return redirect()
                ->back()
                ->with('message', 'Error al desbloquear la orden, detalle: ' . $th->getMessage());
        }
    }

    public function precio(Request $r)
    {
        try {

            $r->validate(['id' => ['required', 'string'], 'precio' => ['required', 'numeric'], 'propina' => ['nullable', 'boolean'],]);
            $id = Crypt::decryptString($r->id);
            $precio = $r->precio;
            $propina = null;
            if (isset($r->propina))
                $propina = $r->propina;
            $detalleOrden = detalle_ordenes::findOrFail($id);
            $precioServicio = (new DetalleOrdenesController)->calServicio($detalleOrden->servicios_id, null, $precio, $propina);
            if ($precioServicio == null)
                return throw new Exception('Ocurrió un error, no se puede calcular el precio.');

            $detalleOrden->precio_unitario = $precio;
            $detalleOrden->neto = $precioServicio['neto'];
            $detalleOrden->cesc = $precioServicio['cesc'];
            $detalleOrden->advalorem = $precioServicio['advalorem'];
            $detalleOrden->iva = $precioServicio['iva'];

            $detalleOrden->propina = $propina ? $precioServicio['propina'] : 0;
            $detalleOrden->save();

            return response()->json(['status' => true]);
        } catch (ValidationException $e) {
            return response()->json(['message' => $e->errors(), 'status' => false]);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Ocurrió un error: ' . $th->getMessage()]);
        }
    }
    ///*funcion para vanular eventos desde ordenes
    public function anularOrden($id)
    {
        try {
            $orden = Crypt::decryptString($id);
            $p = ordenes::findOrFail($orden);
            $p->anulada = true;
            $p->estado = false;
            $p->save();

            return redirect()->back()
                ->with('message', 'Registro eliminado con exito')->with('type', 'danger');
        } catch (\Throwable $t) {
            return redirect()->back()
                ->with('message', 'Ocurrio un error (' . $t->getMessage() . ')')
                ->with('type', 'danger');
        }
    }

    public function editarDescripcion(Request $r)
    {
        try {
            $p = ordenes::find(Crypt::decryptString($r->ordenId));

            if (!$p)
                throw new Exception('No se encontró el registro solicitado.');

            $p->descripcion = $r->descripcion;
            $p->save();

            return redirect()->back()
                ->with('type', 'success')
                ->with('message', 'Observación editada correctamente: ' . $p->descripcion);
        } catch (Throwable $th) {
            return redirect()->back()
                ->with('type', 'danger')
                ->with('message', 'Error: ' . $th->getMessage());
        }
    }

    public function impresion(Request $r)
    {
        return view('ordenes.container_print', ['url' => route('detalle_ordenes.printDetallePdf', ["id" => $r->id])]);
    }

    public function reporteForm()
    {
        return view('ordenes.reportes.form', [
            'cajas' => cajas::getUser(Auth::user()->id),

        ]);
    }
    public function reporteAcciones(Request $r)
    {
        try {
            $inicio = Carbon::parse($r->inicio);
            $fin = Carbon::parse($r->fin);
            $cliente = strtoupper(trim($r->cliente ?? ''));
            $cajas = $this->getCajas($r->cajas_id);
            $detalle = $r->detallado ? true : false;
            $data = ordenes::whereBetween('fecha', [$inicio->format("Y-m-d"), $fin->format("Y-m-d")])
                ->whereIn('cajas_id', $cajas->pluck('id'))
                ->leftJoin('clientes', 'clientes.id', 'ordenes.clientes_id')
                ->select('ordenes.*');

            if ($cliente != null && strlen($cliente) > 0)
                $data = $data->where(function ($q) use ($cliente) {
                    $q->where(DB::raw('ordenes.titular'), 'like', '%' . $cliente . '%')
                        ->orWhere(DB::raw('clientes.nombre'), 'like', '%' . $cliente . '%');
                });

            if ($r->estado && $r->estado != null)
                switch ($r->estado) {
                    case 1:
                        $data = $data->where('facturada', true);
                        break;
                    case 2:
                        $data = $data->where('anulada', true);
                        break;
                    case 3:
                        $data = $data->where('ordenes.anulada', false)
                            ->where('ordenes.facturada', false)
                            ->where('ordenes.estado', true);
                        break;
                }
            $data = $data->orderBy('ordenes.fecha')->get();

            switch ($r->opcion) {
                case 1:
                    return view('ordenes.reportes.preview', ['cajas' => $cajas, 'data' => $data, 'detalle' => $detalle]);
                    break;
                case 2:
                    $snap = SnappyPdf::loadView('ordenes.reportes.print', ['cajas' => $cajas, 'data' => $data, 'detalle' => $detalle])
                        ->setPaper('letter')
                        ->setOrientation('landscape')
                        ->setOption('margin-top', '10mm')
                        ->setOption('margin-bottom', '10mm')
                        ->setOption('margin-left', '10mm')
                        ->setOption('margin-right', '10mm');

                    return $snap->inline('reporte_de_ordenes.pdf');

                    break;
                case 3:
                    $v = view('ordenes.reportes.excel', ['cajas' => $cajas, 'data' => $data, 'detalle' => $detalle]);
                    $rs = Excel::download(new ViewToExcel($v), 'reporte_ordenes.xlsx', \Maatwebsite\Excel\Excel::XLSX);
                    ob_end_clean();
                    return $rs;
                    break;
            }
        } catch (\Throwable $th) {
            return redirect()
                ->back()
                ->with('message', 'Error: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }

    public function getCajas($cajas)
    {
        $a = [];
        foreach ($cajas as $c) {
            if ($c == 0) {
                $array = [];
                break;
            }
            array_push($a, $c);
        }
        if ($a && count($a) > 0)
            return cajas::whereIn('id', $a);

        return cajas::getUser(Auth::user()->id);
    }
}
