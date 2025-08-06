<?php

namespace App\Http\Controllers;

use App\Events\CajasEvent;
use App\Exports\ReservacionesExport;
use App\Exports\viewExport;
use App\Http\Requests\StorerecepcionesRequest as StoreRequest;
use App\Http\Requests\UpdaterecepcionesRequest as UpdateRequest;
use App\Mail\changeMail;
use App\Mail\ReporteHuespedesMail;
use App\Models\anticipo_reservacion;
use App\Models\anticipos;
use App\Models\cargos;
use App\Models\clientes;
use App\Models\detalle_reservas;
use App\Models\estado_habitaciones;
use App\Models\forma_habitaciones;
use App\Models\forma_pagos;
use App\Models\habitaciones;
use App\Models\huesped_recepciones;
use App\Models\huespedes;
use App\Models\recepcion_salida_anticipadas;
use App\Models\recepcion_salidas;
use App\Models\recepciones;
use App\Models\reservaciones;
use App\Models\sucursales;

use App\Models\tarifa_detalles;
use App\Models\tarifas;
use App\Models\tipo_habitaciones;
use App\Models\tipo_mantenimientos;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Barryvdh\DomPDF\PDF as DomPDF;
use Barryvdh\Snappy\Facades\SnappyPdf;
use Carbon\Carbon;
use Exception;
use Throwable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;

class RecepcionesController extends Controller
{
    private $table = 'recepciones';

    public function __construct()
    {
        $this->getTh($this->table, 'Recepciones');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $habitaciones = habitaciones::with(['relacionTipoHabitaciones', 'relacionFormaHabitaciones', 'relacionEstadoHabitaciones', 'relacionUbicacionHabitaciones', 'reservas', 'recepcion', 'mantenimientos']);
        if (session('sucursal')) {
            $habitaciones = $habitaciones->where('sucursales_id', session('sucursal')->id);
        }

        $habitaciones = $habitaciones->orderBy('numero_habitacion', 'ASC')->get();
        return view($this->table . '.panel', [
            'table' => $this->table,
            'p' => $habitaciones,
            'tipos' => tipo_habitaciones::all(),
            'formas' => forma_habitaciones::all(),
            'sucursales' => sucursales::all(),
        ]);
    }
    public function search(Request $r)
    {
        return view($this->table . '.index', [
            'th' => $this->th['create'],
            'table' => $this->table,
            'p' => DB::table('get_recepciones')
                ->where(function ($q) use ($r) {
                    $q->where('nombre', 'like', '%' . strtoupper($r->txtBusqueda) . '%')->orWhere('id', 'like', '%' . strtoupper($r->txtBusqueda) . '%');
                })
                ->where('estado', true)
                ->orderBy('id', 'desc')
                ->paginate(),
            'txtBusqueda' => $r->txtBusqueda,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $r)
    {
        try {
            $hab = habitaciones::with('reserva')->find(Crypt::decryptString($r->id));

            if (
                recepciones::where('habitaciones_id', $hab->id)
                ->where('estado', true)
                ->count() > 0
            ) {
                throw new Exception('Esta habitacion esta ocupada');
            }

            if ($hab->relacionEstadoHabitaciones->token != 1401) {
                throw new Exception('La habitacion no esta vacia limpia, debe solicitar que se limpie, o pedir que se actualice el estado al depto. de ama de llaves');
            }

            $view = count($hab->reserva) > 0 ? $this->table . '.reserva' : $this->table . '.create';
            //$tarifas = count($hab->reserva) > 0 ? [] : $this->getTarifasHabitacionTipo($hab->tipo_habitaciones_id);
            return view($view, [
                'th' => $this->th['create'],
                'table' => $this->table,
                'hab' => $hab,
                //'tarifas' => $tarifas,
            ]);
        } catch (\Throwable $th) {
            return redirect()
                ->route('recepciones.index')
                ->with('message', 'Error: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }

    public function getTarifasHabitacionTipo($tipo_habitaciones_id)
    {
        return tarifa_detalles::with('tarifas')
            ->whereIn('tarifas_id', function ($q) {
                $q->from('tarifas')
                    ->leftJoin('temporadas', 'temporadas.id', 'tarifas.temporadas_id')
                    ->where('tarifas.estado', true)
                    ->where('temporadas.estado', true)
                    ->select('tarifas.id');
            })
            ->where('tipo_habitaciones_id', $tipo_habitaciones_id)
            ->distinct('tarifas_id')

            ->get();
    }
    public function getTarifasHabitacionFecha($habitacion, $entrada, $salida)
    {
        return habitaciones::with(
            [
                'getTarifas' => function ($q) use ($entrada, $salida) {
                    return $q->where('fecha_inicio', "<=", $entrada)->where('fecha_finalizacion', '>=', $salida);
                }
            ]
        )->find($habitacion);
    }
    public function cambiarTarifa(Request $r)
    {
        $r->validate([
            'recepcion_id' => ['required', 'string'],
            'tarifas_id' => ['required', 'string'],
            'justificacion' => ['required', 'string', 'min:10'],
            'confirm' => ['required', 'accepted'],
        ]);

        try {
            $tarifas_id = Crypt::decryptString($r->tarifas_id);
            $recepciones_id = Crypt::decryptString($r->recepcion_id);
            $tarifas = tarifas::find($tarifas_id);
            if (!isset($tarifas) || $tarifas == null || $tarifas->estado == false) {
                throw new Exception('No se encontró la tarifa, o la tarifa esta desactivada.');
            }

            $recepcion = recepciones::find($recepciones_id);

            if (!isset($recepcion) || $recepcion == null || $recepcion->estado == false || $recepcion->facturada || $recepcion->eliminado) {
                throw new Exception('No se encontró la tarifa, o la tarifa esta desactivada.');
            }

            $antigua = tarifas::find($recepcion->tarifas->id);
            $ms = 'Se cambio la tarifa de la recepcion Nº' . $recepcion->id . ', Habitación #' . $recepcion->habitaciones->numero_habitacion . ' detalle: (' . $recepcion->fecha_ingreso . ' a ' . $recepcion->fecha_salida . ') cambio de la tarifa: ' . $antigua->tarifa . " $" . number_format($antigua->precio, 2) . '. Nueva tarifa: ' . $tarifas->tarifa . " $" . number_format($tarifas->precio, 2) . ', el cambio fue realizado por: ' . Auth::user()->name . ', justificación del cambio: ' . $r->justificacion;
            $recepcion->descripcion = $recepcion->descripcion . ' ' . $ms;
            $recepcion->tarifas_id = $tarifas->id;
            $recepcion->save();

            if ($recepcion->detalle_reservas_id && $recepcion->detalle_reservas_id != null && $recepcion->detalle_reservas_id > 0) {
                $reserva = detalle_reservas::find($recepcion->detalle_reservas_id);
                $reserva->tarifas_id = $tarifas->id;
                $reserva->save();
            }

            $mail = env('MAIL_NOTIFICACION', 'change@tropicoinn.com.sv');
            if (strlen($mail) > 5 && strlen($ms) > 5) {
                Mail::to(trim($mail))->queue(new changeMail($ms));
            }
            return redirect()->back()->with('message', 'Se realizo el cambio de tarifa');
        } catch (\Throwable $th) {
            return redirect()
                ->back()
                ->with('message', 'Error: ' . $th->getMessage());
        }
    }

    public function anulacion(Request $r)
    {
        $r->validate([
            'recepcion_id' => ['required', 'string'],
            'justificacionAnular' => ['required', 'string', 'min:10'],
            'confirm' => ['required', 'accepted'],
        ]);

        try {
            $recepciones_id = Crypt::decryptString($r->recepcion_id);
            $recepcion = recepciones::find($recepciones_id);
            if (!isset($recepcion) || $recepcion == null || $recepcion->estado == false || $recepcion->facturada || $recepcion->eliminado) {
                throw new Exception('No se encontró la estadía, o la estadía esta facturada.');
            }

            $habitacion = habitaciones::find($recepcion->habitaciones_id);
            $ms = 'Se anulo la recepcion Nº' . $recepcion->id . ', Habitación #' . $recepcion->habitaciones->numero_habitacion . ' detalle: ' . $recepcion->fecha_ingreso . ' a ' . $recepcion->fecha_salida . ' tarifa: ' . $recepcion->tarifas->tarifa . " $" . number_format($recepcion->tarifas->precio, 2) . ', anulado por: ' . Auth::user()->name . ', justificación: ' . $r->justificacionAnular;

            $recepcion->descripcion = $recepcion->descripcion . ' ' . $ms;
            $recepcion->eliminado = true;
            $recepcion->estado = false;
            $recepcion->facturada = false;
            $recepcion->detalle_reservas_id = null;
            $recepcion->users_eliminado_id = Auth::user()->id;
            $recepcion->save();

            $status = estado_habitaciones::where('token', 1401)->first();
            $habitacion->estado_habitaciones_id = $status->id;
            $habitacion->save();

            $mail = env('MAIL_NOTIFICACION', 'change@tropicoinn.com.sv');
            if (strlen($mail) > 5 && strlen($ms) > 5) {
                Mail::to(trim($mail))->queue(new changeMail($ms));
            }
            return redirect()
                ->route('recepciones.index')
                ->with('message', 'Se anulo la recepcion Nº' . $recepcion->id);
        } catch (\Throwable $th) {
            return redirect()
                ->back()
                ->with('message', 'Error: ' . $th->getMessage());
        }
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorereservacionesRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $r)
    {
        $hab = habitaciones::findOrFail(Crypt::decryptString($r->habitaciones_id));
        try {
            if ((new HabitacionesController())->isValidHabitacion(date('Y-m-d'), $r->fecha_salida, $hab->id)) {



                if ($this->getIsNotEmpty($hab->id)) {
                    throw new Exception('Esta habitacion ya tiene una estadía registrada');
                }

                $p = new recepciones();
                $p->fecha_ingreso = date('Y-m-d');
                $p->hora_entrada = date('H:i:s');
                $p->fecha_salida = $r->fecha_salida;
                $p->clientes_id = $r->clientes_id;
                $p->tarifas_id = Crypt::decryptString($r->tarifas_id);
                $p->habitaciones_id = $hab->id;
                $p->users_id = Auth::user()->id;
                $p->save();

                $estado = estado_habitaciones::where('token', env('estado_entrada', 1404))->first();
                if (isset($estado->id)) {
                    $hab->estado_habitaciones_id = $estado->id;
                    $hab->save();
                } else {
                    Log::error('No se pudo cambiar el estado de habitacion');
                }
                return redirect()->route('recepciones.show', [
                    'id' => Crypt::encryptString($p->id),
                ]);
            } else {
                return redirect()->back()->with('message', 'Esta habitación no esta disponible para las fechas que eligió, elija otra fecha e intente de nuevo.')->with('type', 'danger');
            }
        } catch (\Throwable $th) {
            return redirect()
                ->route($this->table . '.index')
                ->with('message', 'Error al guardar el registro: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }
    public function isValidRecepcion(Request $r)
    {
        $habitacion =  Crypt::decryptString($r->habitaciones_id);
        $entrada = date('Y-m-d');
        $salida = Carbon::parse($r->fecha_salida)->format("Y-m-d");
        $isValid = (new HabitacionesController())->isValidHabitacion($entrada, $salida, $habitacion);
        $tarifas = [];
        if ($isValid)
            $tarifas = $this->getTarifasHabitacionFecha($habitacion, $entrada, $salida);
        return response()->json([
            'valid' => $isValid,
            'tarifas' => $tarifas
        ]);
    }
    public function store_reserva(Request $r)
    {
        try {
            $dr = detalle_reservas::findOrFail(Crypt::decryptString($r->detalle_reservaciones_id));
            $p = $this->save_from_detalle($dr);
            if ($p->id > 0) {
                $dr->ingreso = true;
                $dr->estado = false;
                $dr->descripcion = substr($dr->descripcion . ' Se realizo el ingreso por el usuario: ' . Auth::user()->name . ' (' . date('d-m-Y h:i:s a') . ')', 0, 255);
                $dr->save();
                if (
                    detalle_reservas::where('estado', true)
                    ->where('reservaciones_id', $dr->reservaciones_id)
                    ->count() == 0
                ) {
                    $rs = reservaciones::find($dr->reservaciones_id);
                    $rs->estado = false;
                    $rs->save();
                }

                anticipo_reservacion::where('tipo_reservacion', 1)
                    ->where('reservacion_id', $dr->reservaciones_id)
                    ->update(['tipo_reservacion' => 2, 'reservacion_id' => $p->id]);
            }

            return redirect()->route('recepciones.show', ['id' => Crypt::encryptString($p->id)]);
        } catch (\Throwable $th) {
            return redirect()
                ->back()
                ->with('message', 'Error al realizar el ingreso de esta reservación, por favor tome una captura del error y envié a soporte@tropicoinn.com.sv detalle del error: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }

    public function show(Request $r)
    {
        $p = recepciones::find(Crypt::decryptString($r->id));
        if (!$p->estado) {
            return redirect()->route('recepciones.index')->with('message', 'La estadia seleccionada ya no esta activa o se agrego a un pospago');
        }

        return view($this->table . '.show', [
            'th' => $this->th['create'],
            'table' => $this->table,
            'p' => recepciones::find(Crypt::decryptString($r->id)),
            'forma_pagos' => forma_pagos::whereNotIn('token', [6002, 6004])->get(),
            'tipo_mantenimientos' => tipo_mantenimientos::all(),
            'cargos' => cargos::where('estado', true)->get(),
            'tarifas' => $this->getTarifasHabitacionTipo($p->habitaciones->tipo_habitaciones_id),
        ]);
    }

    public function checkout(Request $r)
    {
        return view('recepciones.checkout', [
            'p' => recepciones::whereDate('fecha_salida', '<=', date('Y-m-d'))->where('estado', true)->get(),
        ]);
    }

    private function getIsNotEmpty($habitaciones_id)
    {
        return recepciones::where('estado', true)
            ->where('habitaciones_id', $habitaciones_id)
            ->count() > 0;
    }
    public function save_from_detalle($dr)
    {
        try {

            if ($this->getIsNotEmpty($dr->habitaciones_id)) {
                throw new Exception('Esta habitacion ya tiene una estadía registrada');
            }

            $p = new recepciones();
            $p->titular = $dr->relacionReservaciones->titular ?? null;
            $p->contacto = $dr->relacionReservaciones->contacto ?? null;
            $p->fecha_ingreso = $dr->fecha_ingreso;
            $p->fecha_salida = $dr->fecha_salida;
            $p->descripcion = $dr->descripcion;
            $p->habitaciones_id = $dr->habitaciones_id;
            $p->tarifas_id = $dr->tarifas_id;
            $p->detalle_reservas_id = $dr->id;
            $p->clientes_id = $dr->relacionReservaciones->clientes_id ?? null;
            $p->users_id = Auth::user()->id;
            $p->save();

            if ($p->id > 0 && count($dr->huespedes) > 0) {
                foreach ($dr->huespedes as $h) {
                    $this->addHuesped($h->huespedes_id, $p->id);
                }
            }

            $hab = habitaciones::findOrFail($dr->habitaciones_id);
            $estado = estado_habitaciones::where('token', env('estado_entrada', 1404))->first();

            if (isset($estado->id)) {
                $hab->estado_habitaciones_id = $estado->id;
                $hab->save();
            } else {
                Log::error('No se pudo cambiar el estado de habitacion');
            }

            return $p;
        } catch (\Throwable $e) {
            return $e;
        }
    }
    public function addHuespedRecepcion(Request $r)
    {
        try {
            foreach ($r->huespedes as $h) {
                $this->addHuesped(intval($h), Crypt::decryptString($r->recepciones_id));
            }
            return redirect()->back()->with('message', 'Huéspedes agregados correctamente')->with('type', 'info');
        } catch (\Throwable $th) {
            return redirect()
                ->back()
                ->with('message', 'Error al agregar los huespedes' . $th->getMessage())
                ->with('type', 'danger');
        }
    }

    public function delHuespedRecepcion(Request $r)
    {
        try {
            huesped_recepciones::destroy(Crypt::decryptString($r->id));
            return redirect()->back()->with('message', 'Huésped eliminado')->with('type', 'info');
        } catch (\Throwable $th) {
            return redirect()
                ->back()
                ->with('message', 'Error al eliminar un huésped' . $th->getMessage())
                ->with('type', 'danger');
        }
    }

    public function addHuesped($huesped, $recepcion)
    {
        if (huesped_recepciones::where('huespedes_id', $huesped)->where('recepciones_id', $recepcion)->count() == 0) {
            $hr = new huesped_recepciones();
            $hr->huespedes_id = $huesped;
            $hr->recepciones_id = $recepcion;
            $hr->save();
        }
    }
    public function comprobante(Request $r)
    {
        try {
            $p = recepciones::findOrFail(Crypt::decryptString($r->id));
            $p->comprobante = true;
            $p->save();
            broadcast(
                new CajasEvent(
                    session('caja')->id,
                    Auth::user()->name . ' solicita el comprobante de la estadía #' . $p->id,
                    1,
                    route('cobros.create', [
                        'origen' => Crypt::encryptString(2),
                        'origen_id' => Crypt::encryptString($p->id),
                        'tipo_comprobante' => Crypt::encryptString(7002),
                    ]),
                ),
            );
            return redirect()
                ->route('recepciones.show', ['id' => Crypt::encryptString($p->id)])
                ->with('message', 'Se solicito el comprobante anticipado de esta estadía');
        } catch (\Throwable $th) {
            return redirect()
                ->back()
                ->with('message', 'Error al guardar el registro: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }
    public function desbloquear(Request $r)
    {
        try {
            $p = recepciones::findOrFail(Crypt::decryptString($r->id));
            $p->comprobante = false;
            $p->save();

            return redirect()->back()->with('message', 'Se desbloqueo la estadía');
        } catch (\Throwable $th) {
            return redirect()
                ->back()
                ->with('message', 'Error al guardar el registro: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }
    public function imprimir(Request $r)
    {
        $view = view('reservaciones.print', [
            'p' => recepciones::find(Crypt::decryptString($r->id)),
        ])->render();
        //return $view;
        $pdf = Pdf::loadHTML($view);
        $pdf->setPaper('letter');

        return $pdf->stream();
    }

    public function updateFechaSalida(Request $r)
    {
        $p = recepciones::findOrFail(Crypt::decryptString($r->id));
        if ($p->fecha_salida >= $r->fecha_salida) {
            return redirect()->back()->with('message', 'No es una fecha valida, la nueva fecha debe ser mayor a la actual.')->with('type', 'danger');
        }
        try {
            if ((new HabitacionesController())->isValidSalida($p->id, $r->fecha_salida)) {
                $p->fecha_salida = $r->fecha_salida;
                $p->save();
                return redirect()->back()->with('message', 'Se cambio la fecha de salida a esta estadía.')->with('type', 'info');
            } else {
                return redirect()->back()->with('message', 'No es una fecha valida, es posible que antes de guardar, otro usuario haya guardado una reservación a esta habitación.')->with('type', 'danger');
            }
        } catch (\Throwable $th) {
            return redirect()
                ->back()
                ->with('message', 'Ocurrió un error al intentar editar la fecha, error:' . $th->getMessage())
                ->with('type', 'danger');
        }
    }

    public function updateCliente(Request $r)
    {
        try {
            $p = recepciones::find(Crypt::decryptString($r->recepciones_id));
            $cl = clientes::find($r->clientes_id);
            $p->clientes_id = $cl->id;
            $p->save();
            if (isset($r->anticipos_id) && count($r->anticipos_id) > 0) {
                foreach ($r->anticipos_id as $anticipos_id) {
                    $a = anticipos::find(Crypt::decryptString($anticipos_id));
                    if (!intval($a->id) || $a->id <= 0) {
                        throw new \Exception('Error no se encontraron los anticipos.');
                    }
                    $a->clientes_id = $cl->id;
                    $a->save();
                }
                return redirect()
                    ->back()
                    ->with('message', 'Se cambio el cliente a esta estadía y se cambiaron: ' . count($r->anticipos_id) . ' anticipo(s).')
                    ->with('type', 'info');
            }
            return redirect()->back()->with('message', 'Se cambio el cliente a esta estadía. ')->with('type', 'info');
        } catch (\Throwable $th) {
            return redirect()
                ->back()
                ->with('message', 'Ocurrió un error al intentar editar el cliente, error:' . $th->getMessage())
                ->with('type', 'danger');
        }
    }

    public function print(Request $r)
    {
        $view = view('recepciones.print', [
            'p' => recepciones::find(Crypt::decryptString($r->id)),
        ])->render();
        //return $view;
        $pdf = Pdf::loadHTML($view);
        $pdf->setPaper('letter');

        return $pdf->stream();
    }

    public function salida(Request $r)
    {
        $recepcion = recepciones::findOrFail(Crypt::decryptString($r->id));
        $sugerencias = recepciones::where('clientes_id', $recepcion->clientes_id)
            ->where(function ($q) use ($recepcion) {
                $q->where('estado', true)->orWhereIn(
                    'id',
                    recepcion_salidas::where('estado', true)
                        ->where('facturada', false)
                        ->where('clientes_id', $recepcion->clientes_id)
                        ->pluck('recepciones_id'),
                );
            })
            ->where('facturada', false)
            ->where('eliminado', false)
            ->get();
        return view('recepciones.salida', [
            'p' => $recepcion,
            'sugerencias' => $sugerencias,
        ]);
    }
    public function checkoutStore(Request $r)
    {
        try {
            $message = '';
            if ($r->recepciones == null || count($r->recepciones) < 1) {
                throw new Exception('Debe seleccionar uno o mas registros para realizar la salida');
            }

            foreach ($r->recepciones as $v) {
                if (strlen($v) == 200) {
                    $p = recepciones::find(Crypt::decryptString($v));
                    if ($p->facturada) {
                        $this->salidaUpdate($p);
                        $message = $message . '· Se realizo la salida de la habitación #' . $p->habitaciones->numero_habitacion . " \n";
                    } else {
                        $message = $message . '· No se puede realizar la salida de la habitación #' . $p->habitaciones->numero_habitacion . " \n";
                    }
                }
            }
            return redirect()
                ->back()
                ->with('message', "Por favor revise el detalle automático: \n" . $message);
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()
                ->back()
                ->with('type', 'warning')
                ->with('message', 'Error: ' . $th->getMessage());
        }
    }
    public function salidaConfirm(Request $r)
    {
        try {
            $p = recepciones::find(Crypt::decryptString($r->id));
            $this->salidaUpdate($p);
            $this->salidaHabitacion($p->habitaciones_id);
            return redirect()
                ->route('recepciones.index')
                ->with('message', 'Se realizo la salida de la estadía No.' . $p->id);
        } catch (\Throwable $th) {
            return redirect()
                ->back()
                ->with('message', 'Ocurrió un error al realizar la salida. Error:' . $th->getMessage())
                ->with('type', 'danger');
        }
    }
    private function salidaUpdate($p)
    {
        $p->estado = false;
        $p->hora_salida = date('H:i:s');
        $p->descripcion = $p->descripcion . Auth::user()->name . ' realizo la salida ' . date('Y-m-d h:i:s');
        return $p->save();
    }
    public function salidaHabitacion($habitacion)
    {
        try {
            $estado = estado_habitaciones::where('token', 1402)->first();
            if (isset($estado->id)) {
                $hab = habitaciones::find($habitacion);
                $hab->estado_habitaciones_id = $estado->id;
                $hab->save();
            }
            return true;
        } catch (\Throwable $th) {
            return throw $th;
        }
    }
    public function container(Request $r)
    {
        return view('recepciones.container_print', ['url' => route('recepciones.print', ['id' => $r->id]), 'id' => $r->id]);
    }
    public function salidaAnticipada(Request $r)
    {
        $r->validate(
            [
                //esto seria decuado agregar el archivo salidasRequest.php
                'salida' => ['required', 'date'],
                'confirm' => ['required', 'numeric', 'in:1'],
                'id' => ['required', 'string'],
                'razon' => ['required', 'string'],
            ],
            [
                'razon.required' => 'Escriba la razón de la salida anticipada.',
                'salida.required' => 'La fecha de salida es obligatoria.',
                'salida.date' => 'La fecha de salida debe ser una fecha válida.',
                'confirm.required' => 'La confirmación es obligatoria.',
                'confirm.numeric' => 'La confirmación debe ser un valor numérico.',
                'confirm.in' => 'La confirmación debe ser igual a 1.',
                'id.required' => 'El ID es obligatorio, no se reconoce la información recargue y vuelva a intentar.',
            ],
        );

        try {
            $p = recepciones::find(Crypt::decryptString($r->id));
            $fingreso = Carbon::parse($p->fecha_ingreso);
            $fsalida = Carbon::parse($p->fecha_salida);
            $salida = Carbon::parse($r->salida);

            if (!$p->estado || $p->facturada || $p->eliminado || $fsalida->lessThan($salida) || $fingreso->greaterThan($salida)) {
                return throw new Exception('No se puede realizar esta acción, porque no cumple los requerimientos, revise que la estadía no haya sido cobrada, o este inactiva; la fecha de salida debe ser mayor a la fecha de ingreso, y menor a la fecha de salida original.');
            }

            if (recepcion_salida_anticipadas::where('recepciones_id', $p->id)->count() > 0) {
                return throw new Exception('Ya se realizo el cambio de fecha de salida para esta recepcion, no se puede agregar mas de un cambio de salida anticipada salida.');
            }

            $s = new recepcion_salida_anticipadas();
            $s->fecha_ingreso = $p->fecha_ingreso;
            $s->fecha_salida = $p->fecha_salida;
            $s->users_id = Auth::user()->id;
            $s->recepciones_id = $p->id;
            $s->razon = $r->razon;
            $s->save();

            $p->fecha_salida = $salida->format('Y-m-d');
            $p->save();

            return redirect()
                ->back()
                ->with('message', 'Se cambio la fecha de salida de ' . $s->fecha_salida . ' a ' . $p->fecha_salida);
        } catch (\Throwable $th) {
            return redirect()
                ->back()
                ->with('message', 'Ocurrió un error al realizar esta acción. Error: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }

    public function salidaSinCobro(Request $r)
    {
        $r->validate(
            [
                'confirm' => ['required', 'numeric', 'in:1'],
                'id' => ['required', 'string'],
                'razon' => ['required', 'string'],
            ],
            [
                'razon.required' => 'Escriba la razón de la salida anticipada.',
                'confirm.required' => 'La confirmación es obligatoria.',
                'confirm.numeric' => 'La confirmación debe ser un valor numérico.',
                'confirm.in' => 'La confirmación debe ser igual a 1.',
                'id.required' => 'El ID es obligatorio, no se reconoce la información recargue y vuelva a intentar.',
            ],
        );

        try {
            $p = recepciones::find(Crypt::decryptString($r->id));

            $cl = $p->clientes;
            if ($cl == null || !$cl->credito)
                throw new Exception('El cliente o titular no tiene crédito habilitado, en el caso que este cliente si deba permitirlo, solicítelo a la persona encargada de créditos');

            if (!$p->estado || $p->facturada || $p->eliminado) {
                return throw new Exception('No se puede realizar esta acción, porque no cumple los requerimientos, revise que la estadía no haya sido cobrada, o este inactiva.');
            }

            if (recepcion_salidas::where('recepciones_id', $p->id)->count() > 0) {
                return throw new Exception('Ya se realizo la salida para esta estadía.');
            }

            $this->salida_pospago($p, $r->razon);
            $p->estado = false;
            $p->comprobante = true;
            $p->descripcion = $p->descripcion . Auth::user()->name . ' realizo la salida ' . date('Y-m-d h:i:s');
            $p->hora_salida = date('H:i:s');
            $p->save();

            $this->salidaHabitacion($p->habitaciones_id);

            return redirect()
                ->back()
                ->with('message', 'Se creo el pospago de la estadia Nº ' . $p->id);
        } catch (\Throwable $th) {
            return redirect()
                ->back()
                ->with('message', 'Ocurrió un error al realizar esta acción. Error: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }

    public function salida_pospago($p, $razon)
    {
        $s = new recepcion_salidas();
        $s->users_id = Auth::user()->id;
        $s->clientes_id = $p->clientes_id;
        $s->recepciones_id = $p->id;
        $s->observacion = $razon;
        $s->save();
        return $s;
    }

    public function sucursal(Request $r)
    {
        try {
            $id = Crypt::decryptString($r->id);
            $sucursal = sucursales::find($id);
            session(['sucursal' => $sucursal]);
            return redirect()->back();
        } catch (\Throwable $th) {
            return redirect()
                ->back()
                ->with('message', 'Ocurrio un error: ' . $th->getMessage());
        }
    }
    public function sucursalClear(Request $r)
    {
        try {
            session()->forget('sucursal');
            return redirect()->back();
        } catch (\Throwable $th) {
            return redirect()
                ->back()
                ->with('message', 'Ocurrio un error: ' . $th->getMessage());
        }
    }

    public function containerTicket(Request $r)
    {
        return view('layouts.container_print', ['ruta' => route('recepciones.index'), 'url' => route('recepciones.ticket')]);
    }
    public function imprimirTicket(Request $r)
    {
        $manana = '' . Carbon::now()->addDay()->format('Y-m-d');
        //return $manana;
        $recepciones = recepciones::whereBetween(DB::raw("'" . $manana . "'"), [DB::raw('fecha_ingreso'), DB::raw('fecha_salida')]);
        if (session('sucursal')) {
            $recepciones = $recepciones->join('habitaciones', 'recepciones.habitaciones_id', 'habitaciones.id')->where('habitaciones.sucursales_id', session('sucursal')->id);
        }
        $recepciones = $recepciones->select('recepciones.id');
        $huespedes = huesped_recepciones::whereIn('recepciones_id', $recepciones)
            ->with(['huesped', 'recepcion'])
            ->orderBy('recepciones_id')
            ->get();
        $pdf = $this->getPDF();
        $pdf->setOption(['dpi' => 150]);
        $pdf->loadView('recepciones.ticket_print', [
            'huespedes' => $huespedes,
        ]);

        $pdf->setPaper('letter', 'landscape');

        return $pdf->stream();
    }
    protected function getPDF(): DomPDF
    {
        $pdf = PDF::getFacadeRoot();
        $dompdf = $pdf->getDomPDF();
        $dompdf->setHttpContext(
            stream_context_create([
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true,
                ],
            ]),
        );
        return $pdf;
    }
    public function reporteEstadia(Request $r)
    {
        $fecha = $r->fecha ?? date('Y-m-d');
        $sucursal = $r->sucursal ?? (session('sucursal') ? session('sucursal')->id : null);
        $recepcion = $this->getRecepciones($fecha, $sucursal);
        $opcion = $r->opcion ?? 1;

        switch ($opcion) {
            case 1:
                return view('recepciones.reporte', ['data' => $recepcion, 'fecha' => $fecha, 'sucursales' => sucursales::all(), 'sucursal' => $sucursal]);
                break;
            case 2:
                $sucursalNombre = $sucursal > 0 ? sucursales::find($sucursal)->sucursal : 'Todas las sucursales';
                $pdf = $this->getPDF();

                $pdf->loadView('recepciones.reporte_print', ['data' => $recepcion, 'fecha' => $fecha, 'sucursalNombre' => $sucursalNombre]);

                $pdf->setPaper('letter', 'landscape');
                return $pdf->stream();
                break;
            case 3:
                $sucursalNombre = $sucursal > 0 ? sucursales::find($sucursal)->sucursal : 'Todas las sucursales';
                $view = view('recepciones.reporte_excel', ['data' => $recepcion, 'fecha' => $fecha, 'sucursalNombre' => $sucursalNombre]);
                $rs = Excel::download(new viewExport($view), 'reporte_estadias.xlsx', \Maatwebsite\Excel\Excel::XLSX);
                ob_end_clean();
                return $rs;
                break;
            default:
                # code...
                break;
        }
    }

    private function getRecepciones($fecha, $sucursal = null)
    {
        $recepcion = recepciones::whereBetween(DB::raw("'" . $fecha . "'"), [DB::raw('fecha_ingreso'), DB::raw('fecha_salida')]);
        if ($sucursal > 0) {
            $recepcion = $recepcion->whereIn('habitaciones_id', fn($q) => $q->from('habitaciones')->where('sucursales_id', $sucursal)->select('id'));
        }
        return $recepcion->get();
    }
    //get huespedes con estadias
    private function getHuespedes($fecha)
    {
        try {
            $huespedes = DB::table('huesped_recepciones')
                ->leftJoin('recepciones', 'huesped_recepciones.recepciones_id', '=', 'recepciones.id')
                ->leftJoin('huespedes', 'huesped_recepciones.huespedes_id', '=', 'huespedes.id')
                ->leftJoin('identificaciones', 'huespedes.identificaciones_id', '=', 'identificaciones.id')
                ->leftJoin('paises', 'huespedes.paises_id', '=', 'paises.id')
                ->leftJoin('habitaciones', 'recepciones.habitaciones_id', '=', 'habitaciones.id')
                ->whereBetween(DB::raw("'" . $fecha . "'"), [DB::raw('recepciones.fecha_ingreso'), DB::raw('recepciones.fecha_salida')])
                ->select('huespedes.nombre as nombre', 'huespedes.identificacion as documento', 'identificaciones.identificacion as identificacion', 'habitaciones.numero_habitacion as n_h', 'paises.nacionalidad as p', 'recepciones.fecha_ingreso as fecha_ingreso', 'recepciones.fecha_salida as fecha_salida')
                ->get();
            return $huespedes;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    //pendiente mejorar y corregir esta consulta
    private function getDataTours($fechaInicio, $fechaFin, $sucursal = null)
    {
        try {
            $data =  DB::table('recepciones_datatour')->where(function ($query) use ($fechaInicio, $fechaFin) {
                $query->whereBetween('fecha_ingreso', [$fechaInicio, $fechaFin])
                    ->orWhereBetween('fecha_salida', [$fechaInicio, $fechaFin])
                    ->orWhere(function ($query) use ($fechaInicio, $fechaFin) {
                        $query->where('fecha_ingreso', '<=', $fechaInicio)
                            ->where('fecha_salida', '>=', $fechaFin);
                    });
            });
            if ($sucursal != null)
                $data = $data->where('sucursales_id', $sucursal);
            return $data->get();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function precios(Request $r)
    {
        try {
            $r->validate(['id' => ['required', 'string'], 'precio' => ['required', 'numeric'], 'propina' => ['nullable', 'boolean']]);
            $id = Crypt::decryptString($r->id);
            $precio = $r->precio;
            $recepcion = recepciones::findOrFail($id);
            $recepcion->tarifa = $precio;
            $recepcion->save();
            return response()->json(['status' => true]);
        } catch (ValidationException $e) {
            return response()->json(['message' => $e->errors(), 'status' => false]);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Ocurrió un error: ' . $th->getMessage()]);
        }
    }
    private function sendEmail($fecha, $data)
    {
        try {
            $email = env('MAIL_HOSPEDAJE', 'ramonsorto@tropicoinn.com.sv');
            if ($email != null && trim($email) != '' && strlen(trim($email)) > 5 && !filter_var(trim($email), FILTER_VALIDATE_EMAIL)) {
                throw new Exception('Correo no válido, agregue un correo en formato válido Ej. soporte@empresa.com.');
            }
            Mail::to(strtolower(trim($email)))->send(new ReporteHuespedesMail($fecha, $data));
        } catch (\Throwable $th) {
            return throw $th;
        }
    }
    //reporte huespedes
    public function reporteHuespedes(Request $r)
    {
        $fecha = $r->fecha ?? date('Y-m-d');
        $opcion = $r->opcion ?? 1;

        switch ($opcion) {
            case 1:
                return view('huespedes.reporte', ['data' => $this->getHuespedes($fecha), 'fecha' => $fecha]);
                break;
            case 2:
                $pdf = $this->getPDF();

                $pdf->loadView('huespedes.reporte_print', ['data' => $this->getHuespedes($fecha), 'fecha' => $fecha]);

                $pdf->setPaper('letter', 'landscape');
                return $pdf->stream();
                break;
            case 3:
                $data = $this->huespedeAdultos($fecha);
                $this->sendEmail($fecha, $data);
                return redirect()
                    ->back()
                    ->with('message', 'Se envio el correo de reporte de huespes, ' . $fecha . ' puede tardar de 1min ~ 5min');
                break;

            default:
                return redirect()->back()->with('message', 'Opción no válida.')->with('type', 'danger');
                break;
        }
    }
    //todo pendiente de revision
    public function reporteDataTours(Request $r)
    {
        $data = $this->loadDataTurForReport(
            $r->fecha ?? date('Y-m-d'),
            $r->fecha_final ?? date('Y-m-d'),
            $r->sucursal ?? (session('sucursal') ? session('sucursal')->id : null)
        );

        try {
            $opcion = $r->opcion ?? 1;
            switch ($opcion) {
                case 1:
                    return view('report.data_tours.data_tours', $data);
                case 2:
                    return $this->dataTurPDF($data);
                case 3:
                    return $this->dataTurExcel($data);
                default:
                    return redirect()->back()->with('message', 'Opción no válida.')->with('type', 'danger');
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    private function loadDataTurForReport($fechaInicio, $fechaFin, $sucursal = null, $r = null)
    {
        return [
            'fecha' => $fechaInicio,
            'fecha_final' => $fechaFin,
            'sucursal' => $sucursal,
            'data' => $this->getDataTours($fechaInicio, $fechaFin, $sucursal),
            'disponibles' => $this->getHabitacionesDisponibles($sucursal),
            'sucursales' => sucursales::all(),
            'opcion' => $r->opcion ?? 1,
        ];
    }

    private function dataTurPDF(array $data)
    {
        $sucursalNombre = $data['sucursal'] > 0 ? sucursales::find($data['sucursal'])->sucursal : 'Todas las sucursales';
        $pdf = $this->getPDF();

        $pdf->loadView('report.data_tours.data_tours_print', [
            'data' => $data['data'],
            'fecha' => $data['fecha'],
            'fecha_final' => $data['fecha_final'],
            'disponibles' => $data['disponibles'],
            'sucursalNombre' => $sucursalNombre,
        ]);

        $pdf->setPaper('letter', 'landscape');
        return $pdf->stream();
    }

    private function dataTurExcel(array $data)
    {
        $view = view('report.data_tours.data_tours_excel', [
            'data' => $data['data'],
            'fecha' => $data['fecha'],
            'fecha_final' => $data['fecha_final'],
            'disponibles' => $data['disponibles'],

        ]);

        $rs = Excel::download(new ReservacionesExport($view), 'reporte_data_tours_' . $data['fecha'] . $data['fecha_final'] . '.xlsx', \Maatwebsite\Excel\Excel::XLSX);
        ob_end_clean();
        return $rs;
    }


    public function dataTurExcelStore($fechaInicio, $fechaFinal)
    {
        $sucursal = env('sucursal_recepcion', 1);
        $data = $this->loadDataTurForReport($fechaInicio, $fechaFinal, $sucursal);

        $view = view('report.data_tours.data_tours_excel', [
            'data' => $data['data'],
            'fecha' => $data['fecha'],
            'fecha_final' => $data['fecha_final'],
            'disponibles' => $data['disponibles'],
        ]);
        $name = 'reporte_data_tours_' . $fechaInicio . $fechaFinal . '.xlsx';
        Excel::store(new ReservacionesExport($view), $name, 'dtes', \Maatwebsite\Excel\Excel::XLSX);
        return $name;
    }

    private function getHabitacionesDisponibles($sucursal = null)
    {
        $bloqueado = estado_habitaciones::where('token', 1406)->get();
        $disponibles = habitaciones::whereNotIn('estado_habitaciones_id', $bloqueado->pluck('id'));
        if ($sucursal != null)
            $disponibles = $disponibles->where('sucursales_id', $sucursal);

        return $disponibles->count();
    }
    private function huespedeAdultos($fecha)
    {
        $fechaActual = Carbon::now()->format('Y-m-d');
        return DB::table('huesped_recepciones')
            ->leftJoin('recepciones', 'huesped_recepciones.recepciones_id', '=', 'recepciones.id')
            ->leftJoin('huespedes', 'huesped_recepciones.huespedes_id', '=', 'huespedes.id')
            ->leftJoin('identificaciones', 'huespedes.identificaciones_id', '=', 'identificaciones.id')
            ->leftJoin('paises', 'huespedes.paises_id', '=', 'paises.id')
            ->leftJoin('habitaciones', 'recepciones.habitaciones_id', '=', 'habitaciones.id')
            ->whereBetween(DB::raw("'" . $fecha . "'"), [
                DB::raw('recepciones.fecha_ingreso'),
                DB::raw('recepciones.fecha_salida')
            ])
            ->whereRaw("DATE_PART('year', AGE(?, huespedes.nacimiento)) >= 18", [$fechaActual])
            ->select(
                'huespedes.nombre as nombre',
                'huespedes.identificacion as documento',
                'identificaciones.identificacion as identificacion',
                'habitaciones.numero_habitacion as n_h',
                'paises.nacionalidad as p',
                'recepciones.fecha_ingreso as fecha_ingreso',
                'recepciones.fecha_salida as fecha_salida'
            )
            ->get();
    }

    #---REPORTE BITÁCORA RECEPCIONES---
    public function bitacoraRecepciones()
    {
        $habitaciones = habitaciones::orderBy('numero_habitacion', 'asc')->get();

        return view($this->table . '.bitacora_recepciones', [
            'habitaciones' => $habitaciones,
        ]);
    }

    public function bitacoraRecepcionesSearch(Request $r)
    {
        $habitaciones = habitaciones::orderBy('numero_habitacion', 'asc')->get();
        $habitacionId = $r->habitaciones_id;
        $fechaIngreso = $r->f_ingreso ?? date('Y-m-d');
        $fechaSalida  = $r->f_salida ?? date('Y-m-d');
        $anuladas     = $r->anuladas ?? false;
        $opcion       = $r->opcion;

        $recepciones = recepciones::with(['habitaciones', 'tarifas', 'usuarios', 'clientes', 'huespedes', 'reservaciones'])
            ->where(fn($q) => $q->whereBetween('fecha_ingreso', [$fechaIngreso, $fechaSalida])
                ->orWhereBetween('fecha_salida', [$fechaIngreso, $fechaSalida]))
            ->orderBy('fecha_ingreso', 'asc')
            ->where('eliminado', $r->anuladas ? true : false);

        if ($habitacionId > 0)
            $recepciones = $recepciones->where('habitaciones_id', $habitacionId);

        $recepciones = $recepciones->get();

        $user = User::whereIn('id', $recepciones->pluck('users_id'))->get();

        switch ($opcion) {
            case 1:
                return view($this->table . '.bitacora_recepciones', [
                    'habitaciones' => $habitaciones,
                    'habitacionId' => $r->habitaciones_id,
                    'fechaIngreso' => $fechaIngreso,
                    'fechaSalida' => $fechaSalida,
                    'anuladas'    => $anuladas,

                    'recepciones' => $recepciones,
                    'user'       => $user,
                ]);
                break;
        }
    }

    public function bitacoraRecepcionesShow($id)
    {
        return view($this->table . '.bitacora_recepciones_show', [
            'detalles' => recepciones::with(['habitaciones' => function ($hab) {
                $hab->with('relacionFormaHabitaciones');
            }, 'tarifas', 'usuarios', 'clientes' => function ($cli) {
                $cli->with(['contactos', 'identificaciones', 'actividades', 'municipios', 'extranjero']);
            }, 'huespedes', 'reservaciones'])->find(Crypt::decryptString($id)),
        ]);

        /*$detalles = DB::table('recepciones')
            ->leftJoin('clientes','recepciones.clientes_id','=','clientes.id')
            ->leftJoin('municipios','clientes.municipios_id','=','municipios.id')
            ->leftJoin('contactos','clientes_contactos.contactos_id','=','contactos.id')
            ->leftJoin('clientes_contactos','clientes.id','=','clientes_contactos.clientes_id')
            ->select(
                'clientes.nombre as nombre_cliente',
                'municipios.municipio as municipio_cliente',
                'clientes.direccion as direccion_cliente',
                'clientes.tipo_cliente as tipo_cliente',
                'contactos.contacto as contacto_cliente',
                'clientes_contactos.valor as telefono_cliente',
            )
            ->where('recepciones.id',Crypt::decryptString($id))
            ->first();

        return view($this->table.'.bitacora_recepciones_show',[
            'detalles'=>$detalles,
        ]);*/
    }

    public function defensoriaIndex()
    {
        $sucursales = sucursales::orderBy('sucursal', 'asc')->get();

        return view($this->table . '.defensoria.form', [
            'sucursales' => $sucursales,
        ]);
    }
    public function defensoriaAcciones(Request $r)
    {
        try {
            $sucursales = sucursales::orderBy('sucursal', 'asc')->get();
            $sucursalId = $r->sucursales_id;

            #Old
            /*$recepciones = recepciones::with(['habitaciones','tarifas','reservaciones'])
                ->whereBetween('fecha_ingreso', [$fechaIngreso, $fechaSalida])
                ->where('eliminado',false)
                #->where('facturada',true)
                ->whereHas('habitaciones', function($q) use($sucursalId){
                    $q->where('sucursales_id', $sucursalId);
                })
                ->orderBy('fecha_ingreso','asc')
                ->get();*/

            #New 1
            /*$recepciones = recepciones::with(['habitaciones','tarifas','reservaciones'])
                ->whereBetween('fecha_ingreso', [$fechaIngreso, $fechaSalida])
                ->whereHas('habitaciones', function($q) use($sucursalId){
                    $q->where('sucursales_id', $sucursalId);
                })
                ->orderBy('fecha_ingreso','asc')
                ->get();*/

            #New 2
            /*$recepciones = recepciones::with(['habitaciones', 'tarifas', 'reservaciones'])
                ->whereDate('fecha_ingreso', '<=', $fechaIngreso)
                ->whereDate('fecha_salida', '>=', $fechaIngreso)
                ->whereHas('habitaciones', function($q) use($sucursalId){
                    $q->where('sucursales_id', $sucursalId)
                        ->where('glorieta', false);
                })
                #->where('eliminado',false)#Tropico Inn: false, Tropiclub: todas (tomar las facturadas y anuladas tambien)
                ->when($sucursalId == 1, function($q){
                    $q->where('eliminado',false);
                })
                ->orderByRaw('fecha_ingreso ASC')
                ->get();*/

            switch (intval($r->opcion)) {
                case 1: #Preview
                    return view($this->table . '.defensoria.form', [
                        'sucursales' => $sucursales,
                        'sucursalId' => $sucursalId,
                    ]);
                    break;
                case 3: #Excel
                    $view = view($this->table . '.defensoria.excel', [
                        'sucursalId' => $sucursalId,
                    ]);

                    $rs = Excel::download(new viewExport($view), 'defensoria_anexo2.xlsx', \Maatwebsite\Excel\Excel::XLSX);
                    ob_end_clean();
                    return $rs;
                    break;
            }
        } catch (Throwable $th) {
            return redirect()->back()
                ->with('type', 'danger')
                ->with('message', 'Error: ' . $th->getMessage());
        }
    }


    public function anulacionesView(Request $r)
    {

        return view('recepciones.anulaciones', [
            'p' => recepciones::find(Crypt::decryptString($r->id))
        ]);
    }

    public function anulacionesStore(Request $r)
    {
        $r->validate([
            'recepcion_id' => ['required', 'string'],
            'justificacionAnular' => ['required', 'string', 'min:10'],
            'confirm' => ['required', 'accepted'],
        ]);

        try {
            $recepciones_id = Crypt::decryptString($r->recepcion_id);
            $recepcion = recepciones::find($recepciones_id);
            if (!isset($recepcion) || $recepcion == null || $recepcion->facturada || $recepcion->eliminado) {
                throw new Exception('No se encontró la estadía, o la estadía esta facturada.');
            }

            $habitacion = habitaciones::find($recepcion->habitaciones_id);
            $ms = 'Se anulo la recepcion Nº' . $recepcion->id . ', Habitación #' . $recepcion->habitaciones->numero_habitacion . ' detalle: ' . $recepcion->fecha_ingreso . ' a ' . $recepcion->fecha_salida . ' tarifa: ' . $recepcion->tarifas->tarifa . " $" . number_format($recepcion->tarifas->precio, 2) . ', anulado por: ' . Auth::user()->name . ', justificación: ' . $r->justificacionAnular;

            $recepcion->descripcion = $recepcion->descripcion . ' ' . $ms;
            $recepcion->eliminado = true;
            $recepcion->estado = false;
            $recepcion->facturada = false;
            $recepcion->detalle_reservas_id = null;
            $recepcion->users_eliminado_id = Auth::user()->id;
            $recepcion->save();

            $mail = env('MAIL_NOTIFICACION', 'change@tropicoinn.com.sv');
            if (strlen($mail) > 5 && strlen($ms) > 5) {
                Mail::to(trim($mail))->queue(new changeMail($ms));
            }
            return redirect()
                ->route('cajas.my')
                ->with('message', 'Se anulo la recepcion Nº' . $recepcion->id);
        } catch (\Throwable $th) {
            return redirect()
                ->back()
                ->with('message', 'Error: ' . $th->getMessage());
        }
    }

    public function salidas()
    {
        $fecha = date("Y-m-d");
        $sucursal = session('sucursal')->id ?? null;
        $sucursales = sucursales::all();
        return view('recepciones.salidasPreview', [
            'data' => $this->getSalidasData($fecha, $sucursal),
            'sucursal' => $sucursal,
            'fecha' => $fecha,
            'sucursales' => $sucursales
        ]);
    }
    public function salidasAcciones(Request $r)
    {
        $fecha = $r->fecha;
        $sucursal = $r->sucursal;
        $sucursales = sucursales::all();
        $data = $this->getSalidasData($fecha, $sucursal);
        switch ($r->accion) {
            case 1:
                return view('recepciones.salidasPreview', [
                    'data' => $data,
                    'sucursal' => $sucursal,
                    'fecha' => $fecha,
                    'sucursales' => $sucursales
                ]);
                break;
            case 2:
                $snap = SnappyPdf::loadView(
                    'recepciones.salidasPrint',
                    [
                        'data' => $data,
                        'sucursal' => $sucursal > 0 ? sucursales::find($sucursal) : null,
                        'fecha' => $fecha,
                        'sucursales' => $sucursales
                    ]
                )
                    ->setPaper('letter', 'landscape')
                    ->setOption('margin-top', '10mm')
                    ->setOption('margin-bottom', '10mm')
                    ->setOption('margin-left', '10mm')
                    ->setOption('margin-right', '10mm');
                return $snap->inline('salidas_de_recepciones.pdf');
                break;
        }
    }
    public function getSalidasData($fecha, $sucursal = null)
    {
        $data = recepciones::where('fecha_salida', $fecha)->where('eliminado', false);
        if ($sucursal != null)
            $data = $data->leftJoin('habitaciones', 'recepciones.habitaciones_id', 'habitaciones.id')->where('habitaciones.sucursales_id', $sucursal);
        return  $data->get();
    }


    public function formPospago(Request $r)
    {
        return view('report.pospagos.form');
    }

    public function pospagoAcciones(Request $r)
    {

        $recepciones = recepciones::where('comprobante', true)
            ->whereIn(
                'id',
                recepcion_salidas::where('estado', true)
                    ->where('facturada', false)
                    ->pluck('recepciones_id')
            )
            ->whereDate('created_at', "<=", $r->fecha)
            ->where('facturada', false)
            ->where('eliminado', false)
            ->with(['clientes', 'habitaciones', 'pospago'])
            ->orderBy('fecha_ingreso')
            ->get();
        switch ($r->opcion) {
            case 1:
                return view('report.pospagos.resumenPreview', ['recepciones' => $recepciones]);
                break;
            case 2:
                $snap = SnappyPdf::loadView(
                    'report.pospagos.printResumen',
                    [
                        'recepciones' => $recepciones
                    ]
                )
                    ->setPaper('letter')
                    ->setOption('margin-top', '10mm')
                    ->setOption('margin-bottom', '10mm')
                    ->setOption('margin-left', '10mm')
                    ->setOption('margin-right', '10mm');
                return $snap->inline('reporte_venta_habitaciones.pdf');
                break;

            case 3:
                $view = view(
                    'report.pospagos.excelResumen',
                    [
                        'recepciones' => $recepciones
                    ]
                );
                $rs = Excel::download(new viewExport($view), 'reporte_estadias_pos_pago.xlsx', \Maatwebsite\Excel\Excel::XLSX);
                ob_end_clean();
                return $rs;
                break;
        }
    }
}
