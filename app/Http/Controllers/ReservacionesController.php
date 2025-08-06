<?php

namespace App\Http\Controllers;

use App\Exports\ReservacionesExport;
use App\Http\Requests\StorereservacionesRequest;
use App\Models\anticipo_reservacion;
use App\Models\detalle_reservas;
use App\Models\evento_cuentas;
use App\Models\forma_habitaciones;
use App\Models\forma_pagos;
use App\Models\habitaciones;
use App\Models\huesped_reservas;
#Agregar.
use App\Models\reservaciones;
use App\Models\sucursales;
use App\Models\tipo_habitaciones;
use App\Models\tipo_reservaciones;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use function Pest\Laravel\json;
use function PHPUnit\Framework\isEmpty;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

use Throwable;

class ReservacionesController extends Controller
{
    private $table = 'reservaciones';

    public function __construct()
    {
        $this->getTh($this->table, 'Reservaciones');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view($this->table . '.index', [
            'th'    => $this->th['index'],
            'table' => $this->table,
            'p'     => reservaciones::where('completa', false)
                ->where('estado', true)
                ->orderBy('id', 'desc')
                ->paginate(20),
        ]);
    }
    public function history()
    {
        $th = [
            'title'     => 'Historial de reservaciones',
            'table'     => $this->table,
            'bread'     => $this->table,
            'btnAdd'    => false,
        ];
        return view($this->table . '.index', [
            'th'    => $th,
            'table' => $this->table,
            'p'     => reservaciones::where('completa', true)->orWhere('eliminado', true)
                ->orderBy('id', 'desc')
                ->paginate(20),
        ]);
    }

    public function search(Request $r)
    {
        return view($this->table . '.index', [
            'th'    => $this->th['index'],
            'table' => $this->table,
            'p'     => reservaciones::whereIn('clientes_id', function ($q) use ($r) {
                $q->from('clientes')->where(DB::raw('UPPER(nombre)'), 'ilike', '%' . strtoupper($r->txtBusqueda) . '%')
                    ->select("id");
            })
                ->orWhere('id', 'ilike', '%' . $r->txtBusqueda . '%')
                ->orWhere('titular', 'ilike', '%' . $r->txtBusqueda . '%')
                ->orderBy('id', 'desc')
                ->paginate(1000),
            'txtBusqueda' => $r->txtBusqueda
        ]);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view($this->table . '.create', [
            'th'   => $this->th['create'],
            'table' => $this->table,
            'tipo_reservacion' => tipo_reservaciones::all(),
            'forma_pagos' => forma_pagos::all(),
        ]);
    }

    public function reservasDisponibles(Request $r)
    {
        try {
            $msj = $type = '';

            $duplicados = detalle_reservas::whereDate('fecha_ingreso', $r->fecha_ingreso)
                ->whereDate('fecha_salida', $r->fecha_salida)
                ->first();

            if ($duplicados != null) {
                $msj  = 'Ya existe una reservación para esta fecha.';
                $type = 'danger';
            } else {
                $msj  = 'Reserva disponible.';
                $type = 'success';
            }

            return response([
                'msj' => $msj,
                'type' => $type
            ]);
        } catch (\Throwable $th) {
            return response([
                'msj' => 'Error al consultar reservas: ' . $th->getMessage(),
                'type' => 'danger'
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorereservacionesRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorereservacionesRequest $r)
    {
        try {
            $p = new reservaciones;
            $p->clientes_id           = $r->clientes_id;
            $p->titular               = $r->titular;
            $p->contacto              = $r->contacto;
            $p->tipo_reservaciones_id = Crypt::decryptString($r->tipo_reservaciones_id);
            $p->users_id              = Auth::user()->id;
            $p->forma_pagos_id           = Crypt::decryptString($r->forma_pagos);
            $p->save();

            return redirect()->route('detalle_reservas.index', [
                'id' => Crypt::encryptString($p->id),
            ]);
        } catch (\Throwable $th) {

            return redirect()->route($this->table . '.index')
                ->with('message', 'Error al guardar el registro: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }
    //**reservacion de eventos */
    public function reservacionEvento(Request $r)
    {
        $r->validate([
            'eventos_id' => 'required|string',
            'clientes_id' => 'required|string',
            'tipo_reservaciones_id' => 'required|string',
        ]);
        try {
            $cliente = Crypt::decryptString($r->clientes_id);
            $tipo_reserva = Crypt::decryptString($r->tipo_reservaciones_id);
            $e = Crypt::decryptString($r->eventos_id);
            $p = new reservaciones;
            $p->clientes_id           = $cliente;
            $p->tipo_reservaciones_id = $tipo_reserva;
            $p->users_id              = Auth::user()->id;
            $p->save();
            (new EventoCuentasController())->eventoCuentas($p->id, $e, 2);
            return redirect()->route('detalle_reservas.index', [
                'id' => Crypt::encryptString($p->id),
            ]);
        } catch (\Throwable $th) {

            return redirect()->route($this->table . '.index')
                ->with('message', 'Error al guardar el registro: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }
    public function complete(Request $r)
    {
        try {

            $p = reservaciones::findOrFail(Crypt::decryptString($r->id));
            if ($p->completa)
                return throw new Exception('Ya se había completado antes');

            $p->completa = true;
            $p->save();
            $reservaEvento = evento_cuentas::where('origen', 2)->where('origen_id', $p->id)->first();
            if (isset($reservaEvento) && isset($p->id) && $p->monto_reserva > 0) {
                (new EventoCuentasController())->montoCuentas($p->id, $p->monto_reserva, 2);
            }
            return redirect()->route('reservaciones.imprimir_container', ['id' => Crypt::encryptString($p->id)]);
        } catch (\Throwable $th) {
            return redirect()->back()
                ->with('message', 'Error al guardar el registro: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }
    public function desbloquear(Request $r)
    {
        try {

            $p = reservaciones::findOrFail(Crypt::decryptString($r->id));
            $p->completa = false;
            $p->save();

            return redirect()->back()->with('message', 'Se desbloqueo la reservacion #' . $p->id);
        } catch (\Throwable $th) {
            return redirect()->back()
                ->with('message', 'Error al guardar el registro: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }
    public function imprimirContainer(Request $r)
    {
        return view('layouts.container_print', ['url' => route('reservaciones.imprimir', ['id' => $r->id]), 'ruta' => route('reservaciones.history')]);
    }
    public function imprimir(Request $r)
    {

        $view = view(
            'reservaciones.print',
            [
                'p' => reservaciones::find(Crypt::decryptString($r->id)),
            ]
        )->render();
        //return $view;
        $pdf = Pdf::loadHTML($view);
        $pdf->setPaper('letter');

        return $pdf->stream();
    }
    public function anular(Request $r)
    {
        return view('reservaciones.anular', [
            'p' => reservaciones::find(Crypt::decryptString($r->id))
        ]);
    }
    public function anulacion(Request $r)
    {
        if (intval($r->confirmacion) != 1)
            throw new Exception("Debe confirmar que esta seguro de eliminar la reservacion de habitaciones");

        if (intval($r->confirmacion) == 1) {
            $p = reservaciones::findOrFail(Crypt::decryptString($r->id));
            try {
                // Registrar la razón de la anulación
                $p->razon_eliminacion = "El usuario: " . Auth::user()->name . " anuló esta reservación (No." . $p->id . ") el día " . now()->format('d-m-Y H:i:s') . ". Razón de anulación del usuario: " . $r->razon;

                // Manejar los casos de detalle de reserva
                switch (true) {
                    case !$p->detalleReservaciones || $p->detalleReservaciones->count() == 0:
                        // Caso 1: La reservación no tiene detalles
                        $p->eliminado = true;
                        $p->estado = false;
                        $p->save();
                        break;

                    case $r->has('detalle_reservas') && is_array($r->detalle_reservas) && count($r->detalle_reservas) > 0:
                        // Caso 2: La reservación tiene detalles y se proporcionan detalles desde el frontend
                        foreach ($r->detalle_reservas as $d) {
                            $dr = detalle_reservas::find(Crypt::decryptString($d));
                            if ($dr && !$dr->ingreso) {
                                $dr->estado = false;
                                $dr->save();

                                // Eliminar anticipos asociados al detalle
                                anticipo_reservacion::where('tipo_reservacion', 1)
                                    ->where('reservacion_id', $dr->id)
                                    ->delete();
                            }
                        }

                        $p->eliminado = true;
                        $p->estado = false;
                        $p->save();

                        break;

                    default:
                        // Caso 3: Si hay detalles que están presentes pero no se seleccionaron para anulación
                        return redirect()->back()->with('message', 'Debe seleccionar una o más reservaciones para anular')->with('type', 'danger');
                }

                return redirect()->route('reservaciones.history')->with('message', "Se anuló la reservación No. " . $p->id);
            } catch (\Throwable $th) {
                return redirect()->back()->with('message', "Ocurrió un error al anular la reservación No. " . $p->id . ", error: " . $th->getMessage());
            }
        }
        return redirect()->back()
            ->with('message', "Ocurrio un error al anular la reservacion, debe confirmar que esta seguro de anular.");
    }
    public function anulacion_detalle(Request $r)
    {

        return view("reservaciones.anulacion", ['p' => reservaciones::findOrFail(Crypt::decryptString($r->id))]);
    }


    public function reportHabitacion(Request $r)
    {
        return view('reservaciones.habitacion', [
            'hab' => habitaciones::orderBy('numero_habitacion', 'asc')->get(),
        ]);
    }

    public function reporteVenta(Request $r)
    {
        return view('reservaciones.reportes', [
            'hab' => habitaciones::orderBy('numero_habitacion', 'asc')->get(),
        ]);
    }
    public function reporteIngreso(Request $r)
    {
        $fecha = $r->fecha ?? date('Y-m-d');
        $reservas = detalle_reservas::join('reservaciones', 'reservaciones.id', 'detalle_reservas.reservaciones_id')
            ->where('reservaciones.estado', true)
            ->where('reservaciones.eliminado', false)
            ->whereDate('fecha_ingreso', $fecha);

        $sucursal = $r->sucursal ?? (session('sucursal')->id ?? null);
        if ($sucursal) {
            $reservas =  $reservas->whereIn("habitaciones_id", fn($q) => $q->from('habitaciones')->where('sucursales_id', $sucursal)->select('id'));
        }

        $reservas = $reservas->with(['relacionHabitaciones', 'relacionTarifas'])->get();
        $opcion = $r->opcion ?? 1;
        switch ($opcion) {
            case 1:
                return view('reservaciones.reporte_ingreso', [
                    'sucursales' => sucursales::all(),
                    'reservaciones' => $reservas,
                    'sucursal' => $sucursal,
                    'fecha' => $fecha
                ]);
                break;
            case 2:
                $sucursalNombre = $sucursal ? sucursales::find($sucursal)->sucursal : 'Todas las sucursales';
                $pdf = $this->getPDF();
                $pdf->loadView(
                    'reservaciones.reporte_ingreso_print',
                    [
                        'reservaciones' => $reservas,
                        'fecha' => $fecha,
                        'sucursalNombre' => $sucursalNombre,
                    ]
                );
                $pdf->setPaper('letter', 'landscape');

                return $pdf->stream();
                break;
        }
    }

    public function reporteVentaOpciones(Request $r)
    {
        try {
            $opcion = $r->opcion;
            $inicio = $r->inicio;
            $fin = $r->fin;
            $hab = $r->habitacion;

            $reservas = detalle_reservas::where(fn($q) => $q->whereBetween('fecha_ingreso', [$inicio, $fin])->orWhereBetween('fecha_salida', [$inicio, $fin]));
            if ($hab > 0)
                $reservas =  $reservas->where("habitaciones_id", $hab);

            $reservas = $reservas->with(['relacionHabitaciones', 'relacionTarifas'])->get();

            $reservaciones = reservaciones::whereIn('id', $reservas->pluck('reservaciones_id'))->get();
            $vendedor = User::whereIn('id', $reservaciones->pluck('users_id'))->get();
            //return response()->json(['r' => $reservaciones, 'd' => $vendedor]);
            switch ($opcion) {
                case 1:
                    return view('reservaciones.reportes', [
                        'hab' => habitaciones::orderBy('numero_habitacion', 'asc')->get(),
                        'reservas' => $reservas,
                        'reservaciones' => $reservaciones,
                        'vendedor' => $vendedor,
                        'inicio' => $inicio,
                        'fin' => $fin,
                        'habitacion' => $hab,
                    ]);
                    break;
                case 2:

                    $pdf = $this->getPDF();

                    $pdf->loadView(
                        'reservaciones.reporte_print',
                        [
                            'reservas' => $reservas,
                            'reservaciones' => $reservaciones,
                            'vendedor' => $vendedor
                        ]
                    );
                    $pdf->setPaper('letter', 'landscape');

                    return $pdf->stream();
                    break;
                case 3:

                    $view = view(
                        'reservaciones.reporte_excel',
                        [
                            'reservas' => $reservas,
                            'reservaciones' => $reservaciones,
                            'vendedor' => $vendedor
                        ]
                    );
                    $rs = Excel::download(new ReservacionesExport($view), 'reporte_reservaciones_' . $inicio . '_al_' . $fin . '.xlsx', \Maatwebsite\Excel\Excel::XLSX);
                    ob_end_clean();
                    return $rs;
                    break;
            }
        } catch (\Throwable $th) {
            return throw $th;
        }
    }

    public function apiHabitaciones(Request $r)
    {
        try {
            $habitacion = $r->habitacion;
            $month = $r->month;
            $year = $r->year;

            $reservaciones = detalle_reservas::where('habitaciones_id', $habitacion)
                ->where(
                    fn($q) => $q->whereMonth('fecha_ingreso', $month)
                        ->orWhereMonth('fecha_salida', $month)
                        ->orWhereYear('fecha_ingreso', $year)
                        ->orWhereYear('fecha_salida', $year)
                )
                ->with(['relacionReservaciones', 'relacionTarifas'])
                ->get();
            return response()->json(['list' => $reservaciones]);
        } catch (\Throwable $th) {
            return response()->json(['list' => [], 'message' => $th->getMessage()]);
        }
    }

    protected function getPDF()
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


    public function disponibilidadHabitacionesView(Request $r)
    {
        return view('reservaciones.disponibilidad', ['forma_habitaciones' => forma_habitaciones::all(), 'tipo_habitaciones' => tipo_habitaciones::all()]);
    }

    #Detalle de hospedaje de huésped
    public function detalleHospedajeHuesped()
    {
        $fecha_ingreso = date('Y-m-d');
        $tipo_ingreso_huesped = null; #0 seran recepciones y 1 seran reservaciones

        return view('reservaciones.detalle_hospedaje', [
            'tipo_ingreso_huesped' => $tipo_ingreso_huesped,
            'fecha_ingreso' => $fecha_ingreso,
        ]);
    }

    public function detalleHospedajeHuespedSearch(Request $r)
    {
        $r->validate([
            'huesped_id'  => ['required', 'int'],
            'tipo_entrada' => ['required', 'string'],
            'f_ingreso'   => ['required', 'date'],
        ]);

        try {
            $huesped_id = $r->huesped_id;
            $fecha = $r->f_ingreso ?? date('Y-m-d');
            $tipo_ingreso_huesped = $r->tipo_entrada; #0 seran recepciones y 1 seran reservaciones

            if ($tipo_ingreso_huesped == 0) { #data huespedes recepciones
                $huespedes = $this->getEstadiaData($huesped_id, $fecha);
            } else if ($tipo_ingreso_huesped == 1) { #data reservas
                $huespedes = $this->getReservacionData($huesped_id, $fecha);
            } else {
                return redirect()->back()
                    ->with('message', 'Ocurrió un error al procesar los registros.');
            }

            return view('reservaciones.detalle_hospedaje', [
                'data' => $huespedes,
                'fecha_ingreso' => $fecha,
                'tipo_ingreso_huesped' => $tipo_ingreso_huesped,
            ]);
        } catch (\Throwable $th) {
            return redirect()->back()
                ->with('message', 'Ocurrió un error al consultar la información del huésped: ' . $th->getMessage());
        }
    }

    private function getEstadiaData($huesped_id, $fecha)
    {
        return DB::table('huespedes')
            ->leftJoin('huesped_recepciones', 'huespedes.id', '=', 'huesped_recepciones.huespedes_id')
            ->leftJoin('recepciones', 'huesped_recepciones.recepciones_id', '=', 'recepciones.id')
            ->leftJoin('habitaciones as recepcion_hab', 'recepciones.habitaciones_id', '=', 'recepcion_hab.id')
            ->leftJoin('identificaciones', 'huespedes.identificaciones_id', '=', 'identificaciones.id')
            ->leftJoin('paises', 'huespedes.paises_id', '=', 'paises.id')
            ->select(
                'huespedes.nombre as nombre_huesped',
                'huespedes.identificacion as documento_huesped',
                'identificaciones.identificacion as tipo_identificacion',
                'paises.nacionalidad as nacionalidad_huesped',
                'recepcion_hab.numero_habitacion as numero_habitacion',
                'recepciones.fecha_ingreso as fecha_ingreso',
                'recepciones.fecha_salida as fecha_salida',
                DB::raw("'estadia' as tipo_registro")
            )
            ->where('huespedes.id', $huesped_id)
            ->where(function ($query) use ($fecha) {
                $query->where('recepciones.fecha_ingreso', '<=', $fecha);
                #->where('recepciones.fecha_salida','>=',$fecha);
            })
            ->distinct()
            ->get();
    }

    #Función para obtener datos de Reservaciones
    private function getReservacionData($huesped_id, $fecha)
    {
        return DB::table('huespedes')
            ->leftJoin('huesped_reservas', 'huespedes.id', '=', 'huesped_reservas.huespedes_id')
            ->leftJoin('detalle_reservas', 'huesped_reservas.detalle_reservas_id', '=', 'detalle_reservas.id')
            ->leftJoin('reservaciones', 'detalle_reservas.reservaciones_id', '=', 'reservaciones.id')
            ->leftJoin('habitaciones as hab_reservacion', 'detalle_reservas.habitaciones_id', '=', 'hab_reservacion.id')
            ->leftJoin('identificaciones', 'huespedes.identificaciones_id', '=', 'identificaciones.id')
            ->leftJoin('paises', 'huespedes.paises_id', '=', 'paises.id')
            ->select(
                'huespedes.nombre as nombre_huesped',
                'huespedes.identificacion as documento_huesped',
                'identificaciones.identificacion as tipo_identificacion',
                'paises.nacionalidad as nacionalidad_huesped',
                'hab_reservacion.numero_habitacion as numero_habitacion',
                'detalle_reservas.fecha_ingreso as fecha_ingreso',
                'detalle_reservas.fecha_salida as fecha_salida',
                'reservaciones.id as numero_reserva',
                DB::raw("'reservacion' as tipo_registro")
            )
            ->where('huespedes.id', $huesped_id)
            ->where(function ($query) use ($fecha) {
                $query->where('detalle_reservas.fecha_ingreso', '>=', $fecha);
                #->where('detalle_reservas.fecha_salida','>=',$fecha);
            })
            ->distinct()
            ->get();
    }
}
