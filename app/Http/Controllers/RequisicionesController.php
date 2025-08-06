<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorerequisicionesRequest;
use App\Models\bodega_users;
use App\Models\bodegas;
use App\Models\existencias;

#Agregar.
use App\Models\requisicion_detalles;
use App\Models\requisiciones;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Barryvdh\DomPDF\PDF as DomPDFPDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Barryvdh\Snappy\Facades\SnappyPdf;

class RequisicionesController extends Controller
{
    private $table = 'requisiciones';

    public function __construct()
    {
        $this->getTh($this->table, 'Requisiciones');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $usuarioLogueado = auth()->user();
        $bodega = session('bodega');
        $bodegaId = $bodega->id;

        return view('bodegas.panel', [
            'th' => $this->th['index'],
            'p' => requisiciones::with(['relacionBodegasSalida', 'relacionBodegasEntrada', 'relacionUserAutorizacion', 'relacionUserCreacion'])
                ->where(function ($query) use ($bodegaId, $usuarioLogueado) {
                    $query
                        ->where('estado', 1)
                        ->orWhere('estado', 4)
                        ->where('bodega_entrada_id', $bodegaId)
                        ->orWhere('user_elimina_id', $usuarioLogueado->id);
                })
                ->orderBy('id', 'DESC')
                ->get(),
            'table' => $this->table,
            'data' => [
                'bodegaUsers' => bodega_users::orderBy('id', 'DESC')->get(),
            ],
        ]);
    }
    public function search(Request $r)
    {
        $p = requisiciones::with(['relacionBodegasSalida', 'relacionBodegasEntrada', 'relacionUserAutorizacion', 'relacionUserCreacion'])
            ->where(function ($query) use ($r) {
                // Buscar por ID de requisición si el texto de búsqueda es un número
                if (is_numeric($r->txtBusqueda)) {
                    $query->orWhere('id', $r->txtBusqueda);
                }

                // Buscar por bodega de entrada
                $query->orWhereHas('relacionBodegasEntrada', function ($subquery) use ($r) {
                    $subquery->where('bodega', 'ilike', '%' . $r->txtBusqueda . '%');
                });
                $query->orWhereHas('relacionBodegasSalida', function ($subquery) use ($r) {
                    $subquery->where('bodega', 'ilike', '%' . $r->txtBusqueda . '%');
                });
            })
            ->paginate(15);

        return view($this->table . '.requisicion', [
            'th' => $this->th['create'],
            'p' => $p,
            'txtBusqueda' => $r->txtBusqueda,
            'table' => $this->table,
            'data' => [
                'bodegaUsers' => bodega_users::orderBy('id', 'DESC')->get(),
            ],
        ]);
    }

    public function requisicion()
    {
        $bodega = session('bodega');
        $bodegaId = $bodega->id;

        return view('requisiciones.requisicion', [
            'th' => $this->th['create'],
            'p' => requisiciones::with(['relacionUsuarios', 'relacionBodegasEntrada', 'relacionBodegasSalida', 'relacionBodegas', 'relacionUserCreacion', 'relacionUserAutorizacion'])
                ->where('bodega_entrada_id', $bodegaId)
                ->whereNotNull('user_autorizacion_id')
                ->orderBy('id', 'DESC')
                ->paginate(15),
            'table' => $this->table,
            'data' => [
                'bodegaUsers' => bodega_users::orderBy('id', 'DESC')->get(),
            ],
        ]);
    }
    /**historial de requisiciones completada o autorizadas */
    public function historialBodegaRequisiciones(Request $r)
    {
        $p = requisiciones::with(['relacionBodegasSalida', 'relacionBodegasEntrada', 'relacionUserAutorizacion', 'relacionUserCreacion'])
            ->where('estado', '=', 2)
            ->orWhere('estado', '=', 3)
            ->orderBy('id', 'DESC')
            ->paginate(10);
        return view('requisiciones.historialBodegaRequisiciones', [
            'th' => $this->th['index'],
            'p' => $p,
        ]);
    }
    /**historial de requisiciones de el usuario solicitadas y se muestran por bodegas */
    public function historialSolicitudesEntrada(Request $r)
    {
        $bodega = session('bodega');
        $bodegaId = $bodega->id;
        $usuarioLogueado = auth()->user();

        // Luego, busco las requisiciones que pertenecen a las bodegas obtenidas.
        $requisiciones = requisiciones::with(['relacionUsuarios', 'relacionBodegasEntrada', 'relacionBodegasSalida', 'relacionBodegas', 'relacionUserCreacion', 'relacionUserAutorizacion', 'elimina'])
            ->where('bodega_entrada_id', $bodegaId)
            ->orderBy('id', 'DESC')
            ->paginate(15);

        return view('requisiciones.solicitudesHistorialEntrada', compact('requisiciones'));
    }
    /**solicitudes de requisiciones para poder ser autorizadas */
    public function autorizarRequisiciones(Request $r)
    {
        $usuarioLogueado = auth()->user();
        $requisiciones = requisiciones::with(['relacionUsuarios', 'relacionBodegasEntrada', 'relacionBodegas', 'relacionBodegasSalida', 'relacionUserCreacion', 'relacionUserAutorizacion'])
            ->whereIn('bodega_salida_id', function ($q) use ($usuarioLogueado) {
                $q->from('bodega_users')
                    ->where('users_id', $usuarioLogueado->id)
                    ->select('bodegas_id');
            })
            ->where('estado', 2)
            ->whereNull('user_autorizacion_id')
            ->orderBy('id', 'DESC')
            ->paginate(10);
        return view('requisiciones.autorizarRequisiciones', compact('requisiciones'));
    }
    //**funcion para autorizar requisiciones */
    public function autorizar(Request $request)
    {
        try {
            $msj = '';
            $type = 'success';
            $id = $request->input('requisicionId');

            $user = Auth::user();
            $password = $request->input('password');

            if (!Hash::check($password, $user->password)) {
                return redirect()
                    ->route('requisiciones.autorizarRequisiciones')
                    ->with(['message' => 'Contraseña es incorrecta', 'type' => 'danger']);
            }

            $requisiciones = requisiciones::find($id);
            // Verifica si la requisición ya está autorizada
            if ($requisiciones->estado === 3) {
                return redirect()
                    ->route('requisiciones.printRequisicion', ['id' => Crypt::encryptString($requisiciones->id)])
                    ->with(['message' => 'Requisición ya está autorizada', 'type' => 'warning']);
            }
            $requisiciones->user_autorizacion_id = Auth::id();
            $requisiciones->estado = 3;
            $requisiciones->save();
            //cambiar a estado a false en el detalle de la requisicion
            $detalleRequisicion = requisicion_detalles::where('requisiciones_id', $requisiciones->id)
                ->where('estado', true)
                ->get();
            foreach ($detalleRequisicion as $detalle) {
                $detalle->estado = false;
                $detalle->save();
            }

            return redirect()
                ->route('requisiciones.printRequisicion', ['id' => Crypt::encryptString($requisiciones->id)])
                ->with(['message' => 'Requisición autorizada con éxito', 'type' => $type]);
        } catch (\Throwable $exception) {
            return response([
                'msj' => 'Error al autorizar la requisición: ' . $exception->getMessage(),
                'type' => 'danger',
            ]);
        }
    }
    //**funcion para negar requisiciones */
    public function negar(Request $request)
    {
        try {
            $msj = '';
            $type = 'success';
            $id = $request->input('requisicion');

            $user = Auth::user();
            $observacion = $request->input('observacionNegacion');

            // Buscar la requisición
            $requisicion = requisiciones::find($id);

            // Verificar si la requisición existe
            if ($requisicion) {
                // Verificar si la requisición no ha sido negada previamente
                if ($requisicion->estado !== 4) {
                    $requisicion->user_elimina_id = $user->id;
                    $requisicion->estado = 4; // Estado de requisición negada
                    $requisicion->observacion_negacion = $observacion;
                    $requisicion->save();

                    return redirect()
                        ->back()
                        ->with(['message' => 'Requisición negada con éxito', 'type' => $type]);
                } else {
                    return redirect()
                        ->back()
                        ->with(['message' => 'La requisición ya ha sido negada previamente', 'type' => 'warning']);
                }
            } else {
                return redirect()
                    ->back()
                    ->with(['message' => 'No se encontró la requisición con el ID proporcionado', 'type' => 'danger']);
            }
        } catch (\Throwable $exception) {
            return redirect()
                ->back()
                ->with(['message' => 'Error al negar la requisición: ' . $exception->getMessage(), 'type' => 'danger']);
        }
    }
    //mostrar el detalle de la requiscion
    public function mostrarDetalle(Request $request)
    {
        try {
            $msj = '';
            $type = 'success';

            $id = $request->input('id');

            $requisiciones = requisiciones::find($id);
            //cambiar a estado a false en el detalle de la requisicion
            $detalleRequisicion = requisicion_detalles::with(['relacionLotes', 'relacionProductos', 'relacionRequisiciones', 'relacionExistencias'])
                ->where('requisiciones_id', $requisiciones->id)
                ->where('estado', true)
                ->get();
            return response()->json(['msj' => 'Requisición autorizada con éxito', 'detalleRequisicion' => $detalleRequisicion, 'type' => $type]);
        } catch (\Throwable $exception) {
            return response([
                'msj' => 'Error al autorizar la requisición: ' . $exception->getMessage(),
                'type' => 'danger',
            ]);
        }
    }
    public function previsualizarRequisicion(Request $request)
    {
        try {
            $msj = '';
            $type = 'success';

            $id = $request->input('id');

            $requisiciones = requisiciones::find($id);
            return response([
                'msj' => $msj,
                'type' => $type,
                'redirect_url' => route('requisiciones.printRequisicion', ['id' => Crypt::encryptString($requisiciones->id)]),
            ]);
        } catch (\Throwable $exception) {
            return response([
                'msj' => 'Error al autorizar la requisición: ' . $exception->getMessage(),
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorerequisicionesRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorerequisicionesRequest $request)
    {
        try {
            $p = new requisiciones();
            $p->fecha = date('Y-m-d h:i:s');
            $p->solicitud = $request->solicitud;
            $p->bodega_entrada_id = $request->bodega_entrada_id;
            $p->bodega_salida_id = $request->bodega_salida_id;
            $p->user_creacion_id = Auth::id();
            $p->estado = 1; #1 Activa, 2 Completa, 3 Autorizada, 4 Eliminada.
            $p->save();

            return redirect()->route('existencias.index', [
                'existenciasId' =>  Crypt::encryptString($p->id), //aqui funciona cuando se crea desde el formulario

            ]);
        } catch (\Throwable $th) {
            return to_route($this->table . '.index')
                ->with('message', 'Error al guardar el registro: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }

    public static function crearRequisicion($fecha, $solicitud, $bodegaEntrada, $bodegaSalida, $idUsuarioCrea, $estado)
    {
        try {
            $p = new requisiciones();
            $p->fecha = $fecha;
            $p->solicitud = $solicitud;
            $p->bodega_entrada_id = $bodegaEntrada;
            $p->bodega_salida_id = $bodegaSalida;
            $p->user_creacion_id = $idUsuarioCrea;
            $p->user_autorizacion_id = $idUsuarioCrea;
            $p->estado = $estado;
            $p->save();

            return $p;
        } catch (\Throwable $th) {
            return $th;
        }
    }

    public function confirm($id)
    {
        try {
            return view('confirmRequisiciones', [
                'th' => $this->th['confirmRequisicion'],
                'p' => requisiciones::findOrFail($id),
            ]);
        } catch (\Throwable $th) {
            return redirect()
                ->back()
                ->with('message', 'Ocurrio un error: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }

    public static function borrarRequisicion($requisicion)
    {
        try {

            $p = requisiciones::find($requisicion);

            foreach ($p->detalle_requisicion as $detalle) {
                ExistenciasController::borrarExistencias($detalle->id);
            }
            requisicion_detalles::where('requisiciones_id', '=', $p->id)->delete();
            $p->delete();

            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }

    #UNA REQUISICION NO SE ELIMINA, SOLO SE CAMBIA DE ESTADO.
    public function status(Request $r)
    {
        try {
            $p = requisiciones::findOrFail($r->id);
            if ($p->detalle_requisicion->isEmpty()) {
                $p->estado = !$p->estado;
                $p->save();
            } else {
                return to_route('bodegas.my')
                    ->with('message', 'La requisicion no se puede eliminar por que posee detalles  revisar en el detalle de requisicion.')
                    ->with('type', 'danger');
            }


            return to_route('bodegas.my')
                ->with('message', 'Requisicion anulada correctamente.')
                ->with('type', 'success');
        } catch (\Throwable $th) {
            return to_route($this->table . '.index')
                ->with('message', 'Error al anular la requisicion: ' . $th->getMessage())
                ->with('type', 'error');
        }
    }
    protected function getPDF(): DomPDFPDF
    {
        $pdf = PDF::getFacadeRoot();
        $dompdf = $pdf->getDomPDF();
        $dompdf->setHttpContext(stream_context_create([
            'ssl' => [
                'verify_peer' => FALSE,
                'verify_peer_name' => FALSE,
                'allow_self_signed' => TRUE
            ],
        ]));
        return $pdf;
    }
    /**funcion para imprimir por requisicion */
    public function printRequisicion($id)
    {
        $p = requisiciones::find(Crypt::decryptString($id));
        $d = requisicion_detalles::with(['relacionLotes', 'relacionProductos', 'relacionRequisiciones', 'relacionExistencias'])
            ->where('requisiciones_id', $p->id)
            ->get();
        $t1 = Carbon::parse($p->created_at);
        $t2 = Carbon::parse($p->update_at);
        $tiempoTardado = $t2->diffForHumans($t1);
        $ImpreID = optional($p)->id;

        if ($ImpreID) {
            return $this->getImpresion($ImpreID);
        }

        /*return view('requisiciones.printByRequisicion', [
            'p' => $p,
            'd' => $d,
            'resolver' => $tiempoTardado,
        ]);*/

        $snap = SnappyPdf::loadView('requisiciones.printByRequisicion', [
            'p' => $p,
            'd' => $d,
            'resolver' => $tiempoTardado,
        ]);

        return $snap->inline('historialRequisiciones.pdf');
    }
    public function getImpresion($ImpreID)
    {
        if (!session('bodega')) {
            return redirect()->route('bodegas.login');
        }
        return view('requisiciones.containerbyRequisicion', ['url' => route('requisiciones.getImpresion', ['ImpreID' => Crypt::encryptString($ImpreID)])]);
    }
    public function reportebyRequisicion(Request $r)
    {
        $ImpreID = Crypt::decryptString($r->ImpreID);

        $p = requisiciones::find($ImpreID);
        $d = requisicion_detalles::with(['relacionLotes', 'relacionProductos', 'relacionRequisiciones', 'relacionExistencias'])
            ->where('requisiciones_id', $p->id)
            ->get();

        $t1 = Carbon::parse($p->created_at);
        $t2 = Carbon::parse($p->updated_at);
        $tiempoTardado = $t2->diffForHumans($t1);
        $pdf = $this->getPDF();
        $pdf->loadView('requisiciones.printByRequisicion', [
            'p' => $p,
            'd' => $d,
            'resolver' => $tiempoTardado,
        ]);

        $pdf->setPaper('letter', 'landscape');
        return $pdf->stream();
    }

    //REPORTE DE REQUISICIONES
    public function requisiciones_reporte(Request $r)
    {
        if (!session('bodega')) {
            return redirect()->route('bodegas.login');
        }
        if (isset($r->bodega_users_id) || isset($r->fecha_inicio) || isset($r->fecha_fin)) {
            $v = $r->validate([
                'bodega_users_id' => ['required', 'int'],
                'fecha_inicio' => ['required', 'date'],
                'fecha_fin' => ['required', 'date'],
            ]);
            if (!$v) {
                return redirect()
                    ->back()
                    ->with('message', 'las fechas o el id de boga no  es valido')
                    ->with('type', 'danger');
            }
        }
        if (isset($r->accion)) {
            $accion = Crypt::decryptString($r->accion);

            if (($accion == 2 && isset($r->bodega_users_id)) || (isset($r->fecha_incio) && isset($r->fecha_fin))) {
                return $this->getReporte($r->bodega_users_id, $r->fecha_inicio, $r->fecha_fin);
            }
        }
        $usuarioLogueado = Auth::user();
        $bodegalogueado = session('bodega')->id;
        $fecha_inicio = $r->fecha_inicio ?? date('Y-m-d');
        $fecha_fin = $r->fecha_fin ?? date('Y-m-d');
        $bodega = bodega_users::with(['relacionUsuarios', 'relacionBodegas'])
            ->where('users_id', $usuarioLogueado->id)
            ->orderBy('id', 'DESC')
            ->get();
        $bodegabyFiltro = $r->bodega_users_id;
        $requisiciones = requisiciones::with(['relacionUsuarios', 'relacionBodegasEntrada', 'relacionBodegasSalida', 'relacionUserAutorizacion', 'relacionUserCreacion'])
            ->whereIn('bodega_entrada_id', function ($q) use ($usuarioLogueado) {
                $q->from('bodega_users')
                    ->where('users_id', $usuarioLogueado->id)
                    ->select('bodegas_id');
            })
            ->whereIn('bodega_salida_id', function ($q) use ($usuarioLogueado) {
                $q->from('bodega_users')
                    ->where('users_id', $usuarioLogueado->id)
                    ->select('bodegas_id');
            })
            ->when($bodegabyFiltro, function ($query) use ($bodegabyFiltro) {
                return $query->where('bodega_entrada_id', $bodegabyFiltro);
            })
            ->when($fecha_inicio && $fecha_fin, function ($fe) use ($fecha_inicio, $fecha_fin) {
                return $fe->whereBetween('fecha', [$fecha_inicio, $fecha_fin]);
            })
            ->orderBy('id', 'DESC')
            ->get();
        $detalles = requisicion_detalles::with(['relacionProductos', 'relacionUsuarios', 'relacionLotes', 'relacionExistencias', 'relacionRequisiciones'])
            ->orderBy('id', 'DESC')
            ->get();
        $reporte = [];
        foreach ($requisiciones as $requisicion) {
            $reporte[$requisicion->id] = [
                'requisicion' => $requisicion,
                'detalles' => $detalles->where('requisiciones_id', $requisicion->id)->all(),
            ];
        }
        return view('requisiciones.reporte_requisiciones', compact('bodegabyFiltro', 'reporte', 'bodega', 'bodegalogueado', 'fecha_inicio', 'fecha_fin'));
    }
    public function getReporte($bodegabyFiltro, $fecha_inicio, $fecha_fin)
    {
        if (!session('bodega')) {
            return redirect()->route('bodegas.login');
        }
        return view('requisiciones.container_requisicion', ['url' => route('requisiciones.requisiciones_reporte_pdf', ['bodegabyFiltro' => Crypt::encryptString($bodegabyFiltro), 'fecha_inicio' => Crypt::encryptString($fecha_inicio), 'fecha_fin' => Crypt::encryptString($fecha_fin), 'bodega' => Crypt::encryptString(session('bodega')->id)])]);
    }
    public function getReportePDF(Request $r)
    {
        $usuarioLogueado = Auth::user(); //pendiente agregar que solo alas que tenga permiso podra ver las requisiciones en los reportes

        $bodega = bodega_users::with(['relacionUsuarios', 'relacionBodegas'])
            ->where('users_id', $usuarioLogueado->id)
            ->orderBy('id', 'DESC')
            ->get();
        $bodegabyFiltro = Crypt::decryptString($r->bodegabyFiltro);
        $bodega = bodegas::find(Crypt::decryptString($r->bodega));
        $fecha_inicio = Crypt::decryptString($r->fecha_inicio);
        $fecha_fin = Crypt::decryptString($r->fecha_fin);
        $requisiciones = requisiciones::with(['relacionUsuarios', 'relacionBodegasEntrada', 'relacionBodegasSalida', 'relacionUserAutorizacion', 'relacionUserCreacion'])
            ->whereIn('bodega_entrada_id', function ($e) use ($usuarioLogueado) {
                $e->from('bodega_users')
                    ->where('users_id', $usuarioLogueado->id)
                    ->select('bodegas_id');
            })
            ->whereIn('bodega_salida_id', function ($s) use ($usuarioLogueado) {
                $s->from('bodega_users')
                    ->where('users_id', $usuarioLogueado->id)
                    ->select('bodegas_id');
            })
            ->when($bodegabyFiltro, function ($query) use ($bodegabyFiltro) {
                return $query->where('bodega_entrada_id', $bodegabyFiltro);
            })
            ->when($fecha_inicio && $fecha_fin, function ($query) use ($fecha_inicio, $fecha_fin) {
                return $query->whereBetween('fecha', [$fecha_inicio, $fecha_fin]);
            })
            ->orderBy('id', 'DESC')
            ->get();
        $detalles = requisicion_detalles::with(['relacionProductos', 'relacionUsuarios', 'relacionLotes', 'relacionExistencias', 'relacionRequisiciones'])
            ->orderBy('id', 'DESC')
            ->get();
        $reporte = [];
        foreach ($requisiciones as $requisicion) {
            $reporte[$requisicion->id] = [
                'requisicion' => $requisicion,
                'detalles' => $detalles->where('requisiciones_id', $requisicion->id)->all(),
            ];
        }
        $pdf = $this->getPDF();
        $pdf->loadView('requisiciones.requisicion_print', compact('reporte', 'bodegabyFiltro', 'bodega', 'fecha_inicio', 'fecha_fin'));
        $pdf->setPaper('letter', 'landscape');
        return $pdf->stream();
    }

    public function reporteActivas(Request $r)
    {
        $fecha = Carbon::now()->subDays(5)->format("Y-m-d");
        $p = requisiciones::whereIn('estado', [1, 2])
            ->whereDate('fecha', '<=', $fecha)
            ->with([
                'detalle',
                'relacionBodegasEntrada',
                'relacionBodegasSalida',
                'relacionUserCreacion'
            ])
            ->get();

        return view('requisiciones.activas', ['p' => $p, 'bodegas' => bodegas::all()]);
    }
}
