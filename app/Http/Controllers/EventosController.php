<?php

namespace App\Http\Controllers;

use App\Exports\ReservacionesExport;
use App\Http\Requests\DuplicarEventoRequest;
use App\Http\Requests\StoreeventosRequest;
use App\Http\Requests\UpdateFechaEventoRequest;
use App\Models\anticipos;
use App\Models\aplicacion_pagos;
use App\Models\bodega_cajas;
use App\Models\cajas;
use App\Models\categoria_fotos;
use App\Models\categorias_precios;
use App\Models\clientes;
use App\Models\comanda_detalles;
use App\Models\comandas;
use App\Models\cortesias;
use App\Models\descuentos;
use App\Models\detalle_montaje_eventos;
use App\Models\detalle_ordenes;
use App\Models\evento_cuentas;
use App\Models\eventos;
use App\Models\eventos_galerias;
use App\Models\forma_pagos;
use App\Models\galerias;
use App\Models\montajes;
use App\Models\ordenes;
use App\Models\salones;
use App\Models\sonidos;
use App\Models\tipo_eventos;
use App\Models\tipo_reservaciones;
use App\Models\turnos;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Barryvdh\DomPDF\PDF as DomPDFPDF;
use Barryvdh\Snappy\Facades\SnappyPdf;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;

class EventosController extends Controller
{
    private $table = 'eventos';

    public function __construct()
    {
        $this->getTh($this->table, 'Eventos');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    //**esta funcion muestra la lista de eventos creados */
    public function eventos()
    {
        try {
            $eventos = eventos::with(['clientes', 'forma_pagos', 'tipo_eventos'])
                ->Where('estado', true)
                ->Where('facturado', false)
                ->orderBy('id', 'DESC')
                ->paginate(15);
            return view('eventos.index', [
                'eventos' => $eventos,
            ]);
        } catch (\Throwable $th) {
            return 'Ocurrió un error al cargar index de eventos, (Tome una captura a esta pantalla y envíe a informatica@tropicoinn.com.sv) error: ' . $th->getMessage();
        }
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    //*se utiliza para la vista de agregar nuevo evento y se refactorizo es mas eficiente de esta forma
    public function evento()
    {
        $tipoEventos = tipo_eventos::all();
        $salones = salones::all();
        $Fpagos = forma_pagos::whereIn('token', [6001, 6002])->get();
        $retraso = env('retraso', 3);


        return view('eventos.create', compact('tipoEventos', 'Fpagos', 'salones', 'retraso'));
    }
    //*esta funcion es para detalles de eventos y sus cuentas con sus detalles y configuraciones
    public function detalle($id)
    {
        try {

            $evento = eventos::with(['clientes', 'usuarios', 'sonidos', 'forma_pagos', 'tipo_eventos', 'autorizacion', 'montajes'])->find(Crypt::decryptString($id));
            $ordenes = $evento
                ->ordenesEvento($evento->id)
                ->with(['detalle_orden', 'clientes', 'cajas'])
                ->where('tipo_orden', 5)
                ->where('anulada', false)
                ->orderBy('id', 'DESC')
                ->get();

            $comandas = $evento
                ->comandasEvento($evento->id)
                ->with(['clientes', 'usuarios', 'turnos', 'cajas', 'detalles_comanda'])
                ->where('eliminada', false)
                ->whereIn('tipo_comanda', [3, 5])
                ->orderBy('mesa', 'DESC')
                ->get();
            // $salones = $evento->salonesEvento($evento->id, $evento->fecha, $evento->inicio, $evento->finalizacion);
            $salones = salones::all();
            $reservaciones = $evento->reservaciones()->orderBy('id', 'DESC')->get();

            $sonidos = sonidos::all();
            $montajes = montajes::all();
            $tipo_eventos = tipo_eventos::all();
            $galerias = galerias::all();
            $categoria_fotos = categoria_fotos::all();
            $retraso = env('retraso', 3);

            return view('eventos.detalle', [
                'evento' => $evento,
                'ordenes' => $ordenes,
                'comandas' => $comandas,
                'sonidos' => $sonidos,
                'montajes' => $montajes,
                'tipo_eventos' => $tipo_eventos,
                'galerias' => $galerias,
                'categoria_fotos' => $categoria_fotos,
                'salones' => $salones,
                'retraso' => $retraso,
                'reservas' => $reservaciones
            ]);
        } catch (\Throwable $th) {
            return redirect()
                ->back()
                ->with('message', 'Error al cargar el detalle del evento: ' . $th->getMessage())
                ->with('type', 'error');
        }
    }
    //**comanda eventos */
    public function comandaEventos($id, $eventoId)
    {
        try {
            $caja = session('caja');
            if (!$caja) {
                return redirect()->route('eventos.eventos')->with('message', 'No hay una sesión activa para la caja.')->with('type', 'danger');
            }
            $comanda = comandas::with(['clientes', 'usuarios'])
                ->where('id', Crypt::decryptString($id))
                ->where('eliminada', false)
                ->where('anulada', false)
                ->where('cajas_id', $caja->id)
                ->whereIn('tipo_comanda', [3, 5])
                ->firstOrFail();

            $c_detalle = $comanda->detalles_comanda;
            $eventoId = eventos::find(Crypt::decryptString($eventoId));

            return view('eventos.comanda', [
                'c' => $comanda,
                'c_detalle' => $c_detalle,
                'evento' => $eventoId,
                'bodegas' => bodega_cajas::where('cajas_id', session('caja')->id)
                    ->with('bodegas')
                    ->get(),
            ]);
        } catch (\Throwable $th) {
            return redirect()->route('eventos.eventos')->with('message', 'La comanda no existe o no está disponible.')->with('type', 'danger');
        }
    }
    private function getComandas()
    {
        return comandas::with(['clientes', 'usuarios'])
            ->where('comprobante', false)
            ->where('estado', true)
            ->where('facturada', false)
            ->where('eliminada', false)
            ->where('cajas_id', session('caja')->id)
            ->whereIn('tipo_comanda', [3, 5])
            ->orderBy('mesa', 'asc')
            ->get();
    }

    //**funcion para crear anticipos desde eventos  */
    public function anticiposEventos($id)
    {
        if (!session('caja') || !session('turno')) {
            return redirect()->route('cajas.login');
        }

        $evento = eventos::with(['clientes', 'forma_pagos'])->find(Crypt::decryptString($id));
        if (!$evento) {
            return redirect()->back()->with('error', 'Evento no encontrado');
        }
        $Fpagos = forma_pagos::whereNotIn('token', [6002, 6004])->get();

        return view('eventos.anticipos', [
            'evento' => $evento,
            'Fpagos' => $Fpagos,
        ]);
    }
    public function solicitarAutorizacionEvento($id)
    {
        try {
            $evento = eventos::find(Crypt::decryptString($id));

            if (!$evento) {
                return redirect()->back()->with('message', 'Evento no encontrado')->with('type', 'danger');
            }
            if ($evento->solicita) {
                return redirect()->back()->with('message', 'El evento ya ha sido solicitado anteriormente')->with('type', 'danger');
            }
            if (
                (!isset($evento->salones) || $evento->salones->count() === 0) ||
                (!isset($evento->tipo_eventos) || $evento->tipo_eventos->count() === 0) || (!isset($evento->clientes) || $evento->clientes->count() === 0)
                || (!isset($evento->sonidos) || $evento->sonidos->count() === 0) &&
                (!isset($evento->montajes) || $evento->montajes->count() === 0)
            ) {
                return redirect()->back()->with('message', 'El evento no puede solicitar autorización, faltan agregar  salones, o asignar cliente o sonidos o montajes')->with('type', 'danger');
            }

            $evento->solicita = date('Y-m-d H:i:s');


            // Obtener y procesar órdenes asociadas al evento
            $ordenes = $evento->ordenesTest()->with('detalle_orden')->where('anulada', false)->get();
            // Obtener y procesar comandas asociadas al evento
            $comandas = $evento->comandasTest()->with('detalles_comanda')->where('eliminada', false)->get();
            // Verificar si no hay órdenes ni comandas
            if (($ordenes->count() === 0) && ($comandas->count() === 0)) {
                return redirect()->back()->with('message', 'El evento no tiene cuentas asociadas, agregue cuentas para poder solicitar la autorización.')->with('type', 'warning');
            }
            if (isset($ordenes) && ($ordenes->count() > 0)) {
                foreach ($ordenes as $orden) {
                    if ($orden->detalle_orden->count() === 0) {
                        return redirect()->back()->with('message', 'Esta cuenta de orden ' . $orden->orden . ' del evento no posee detalles, agregue detalles a la cuenta para poder solicitar la autorización.')->with('type', 'warning');
                    }
                    $orden->comprobante = true;
                    $orden->save();
                    (new EventoCuentasController())->montoCuentas($orden->orden, $orden->sumOrden, 1);
                }
            }


            if (isset($comandas) && ($comandas->count() > 0)) {
                foreach ($comandas as $comanda) {
                    if ($comanda->detalles_comanda->count() === 0) {
                        return redirect()->back()->with('message', 'Esta cuenta de comanda #' . $comanda->id . ' del evento no posee detalles, agregue detalles a la cuenta para poder solicitar la autorización.')->with('type', 'warning');
                    }
                    $comanda->comprobante = true;
                    $comanda->save();
                    (new EventoCuentasController())->montoCuentas($comanda->id, $comanda->sum_comanda, 3);
                }
            }


            $evento->save();
            return redirect()->back()->with('message', 'Se ha solicitado la autorización correctamente')->with('type', 'success');
        } catch (\Exception $e) {
            // Manejar la excepción
            return redirect()
                ->back()
                ->with('error', 'Error al solicitar la autorización del evento: ' . $e->getMessage());
        }
    }
    //**funcion para replica de panel comandas de eventos */
    public function comandasbyEvento(Request $r)
    {
        try {
            $comandas  = $this->getComandas();
            $comandas_detalle = null;
            $cortesias = null;
            $id = null;
            if (isset($r->id)) {
                $id = (int) Crypt::decryptString($r->id);
            } else {
                if ($comandas->count() > 0) {
                    $id = $comandas->first()->id;
                }
            }
            $comanda = null;
            if ($id != null) {
                $comanda = comandas::find($id);
                $evento = eventos::find($r->eventoId);

                $comandas_detalle = $comanda->detalles_comanda;
                if ($comanda->tipo_comanda == 3) {
                    $cortesias = cortesias::where('origen', 3)
                        ->where('origen_id', $comanda->id)
                        ->with('titular')
                        ->first();
                }
            }

            return view('eventos.comandas-evento', [
                'comanda' => $comanda,
                'comandas' => $comandas,
                'evento' => $evento,
                'cortesias' => $cortesias,
                'comandas_detalle' => $comandas_detalle,
                'bodegas' => bodega_cajas::where('cajas_id', session('caja')->id)
                    ->with('bodegas')
                    ->get(),
            ]);
        } catch (\Throwable $th) {
            return 'Ocurrió un error al cargar esta comanda,(Tome una captura a esta pantalla y envié a informatica@tropicoinn.com.sv) error: ' . $th->getMessage();
        }
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreeventosRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreeventosRequest $r)
    {
        try {

            //validacion debe existir un cliente o titular
            if (!$r->clientes_id && !$r->titular) {
                return redirect()
                    ->back()
                    ->with('message', 'Debe agregar un cliente o un titular para crear el evento.')
                    ->with('type', 'info');
            }
            //obtengo los datos de salones
            $separado = $r->opcion_salon;
            $salones = $r->salones_seleccionados;
            $salonesArray = array_map('intval', explode(',', $salones));
            if (!isset($salonesArray) || empty($salonesArray)) {
                throw new \Exception('Debe seleccionar al menos un salón para crear el evento.');
            }

            $actual = Carbon::now('America/El_Salvador')->addHours(3)->format('H:i');
            $inicio = Carbon::createFromFormat('H:i', $r->inicio, 'America/El_Salvador')->format('H:i');
            $finaliza = Carbon::createFromFormat('H:i', $r->finalizacion, 'America/El_Salvador')->format('H:i');

            $fechaA = now()->format("Y-m-d");
            $fechaRequerida = $r->fecha;

            if (isset($fechaA) && $fechaA == $fechaRequerida) {
                // Validar que la hora de inicio sea igual a la hora actual ajustada
                if ($inicio < $actual) {
                    return redirect()
                        ->back()
                        ->with('message', 'La hora de inicio no es válida.')
                        ->with('type', 'danger');
                }
            } else {
                // Validar que la hora de inicio sea menor que la hora de finalización
                if ($inicio > $finaliza) {
                    return redirect()
                        ->back()
                        ->with('message', 'La hora de inicio debe ser menor que la hora de finalización.')
                        ->with('type', 'danger');
                }
            }


            //creo el evento
            $data = new eventos();
            $data->fecha = $r->fecha;
            $data->fecha_fin = $r->fecha_fin;
            $data->titular = $r->titular ?? null;
            $data->inicio = $r->inicio;
            $data->encargado = $r->encargado;
            $data->finalizacion = $r->finalizacion;
            $data->minimo_personas = $r->minimo_personas;
            $data->maximo_personas = $r->maximo_personas;
            $data->forma_pagos_id = $r->fpago;
            $data->clientes_id = $r->clientes_id ?? null;
            $data->tipo_eventos_id = $r->Tipoevento;
            $data->users_id = auth()->user()->id;

            $data->save();


            (new EventosSalonesController())->createEventoSalones($separado, $data->id, $salones);


            return redirect()->route('eventos.eventos')->with('message', 'Evento creado correctamente con salones')->with('type', 'success');
        } catch (\Throwable $th) {
            return redirect()
                ->route('eventos.eventos')
                ->with('message', 'Error al guardar el registro: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }
    //**funcion para actualizar fecha y hora de inicio y finalizacion de el evento */
    public function updateFechaHoraEvento(UpdateFechaEventoRequest $r)
    {
        try {
            $actual = Carbon::now('America/El_Salvador')->addHours(3)->format('H:i');
            $inicio = Carbon::createFromFormat('H:i', $r->inicio, 'America/El_Salvador')->format('H:i');
            $finaliza = Carbon::createFromFormat('H:i', $r->finalizacion, 'America/El_Salvador')->format('H:i');

            $fechaA = now()->format("Y-m-d");
            $fechaRequerida = $r->fecha;

            if (isset($fechaA) && $fechaA == $fechaRequerida) {
                // Validar que la hora de inicio sea igual a la hora actual ajustada
                if ($inicio < $actual) {
                    return redirect()
                        ->back()
                        ->with('message', 'La hora de inicio no es válida.')
                        ->with('type', 'danger');
                }
            } else {
                // Validar que la hora de inicio sea menor que la hora de finalización
                if ($inicio > $finaliza) {
                    return redirect()
                        ->back()
                        ->with('message', 'La hora de inicio debe ser menor que la hora de finalización.')
                        ->with('type', 'danger');
                }
            }
            $id = Crypt::decryptString($r->eventos_id);
            $evento = eventos::find($id);
            $evento->fecha = $r->fecha;
            $evento->inicio = $r->inicio;
            $evento->finalizacion = $r->finalizacion;
            $evento->save();
            (new EventosSalonesController())->eliminarSalones($evento->id);
            return redirect()
                ->back()
                ->with('message', 'se actualizo la fecha y hora de inicio, finalizacion del evento: ' . $evento->id)
                ->with('type', 'success');
        } catch (\Throwable $th) {
            return redirect()
                ->route('eventos.eventos')
                ->with('message', 'Error al guardar el registro: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }
    //**funcion para actualizar minimo y maximo de poersonas de sde el detalle de eventos */
    public function updateCantidadPersonas(Request $r)
    {
        try {
            $r->validate([
                'minimo_personas' => ['required', 'numeric'],
                'maximo_personas' => ['required', 'numeric'],
                'confirm_edit_personas' => ['required', 'in:1'],
                'eventos_id' => ['required', 'string', 'max:256'],
            ]);

            $minimo = $r->minimo_personas;
            $maximo = $r->maximo_personas;
            $id = Crypt::decryptString($r->eventos_id);

            if ($minimo > $maximo) {
                return redirect()
                    ->back()
                    ->with('message', 'La cantidad mínima de personas debe ser menor que la cantidad máxima de personas.')
                    ->with('type', 'danger');
            }

            $evento = eventos::find($id);

            if (!$evento) {
                return redirect()
                    ->back()
                    ->with('message', 'No se encontró el evento especificado.')
                    ->with('type', 'danger');
            }

            $evento->minimo_personas = $minimo;
            $evento->maximo_personas = $maximo;
            $evento->save();

            return redirect()
                ->back()
                ->with('message', 'Se actualizó la cantidad mínima y máxima de personas del evento: ' . $evento->id)
                ->with('type', 'success');
        } catch (\Throwable $th) {
            return redirect()
                ->route('eventos.eventos')
                ->with('message', 'Error al guardar el registro: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }
    //**funcion para autorizar eventos  */
    public function autorizarEvento(Request $r)
    {
        try {
            $r->validate([
                'password' => ['required', 'string'],
                'confirm' => ['required', 'in:1'],
                'modificacion' => ['nullable', 'in:1'],
                'eventos_id' => ['required', 'string', 'max:256'],
            ]);
            $id = Crypt::decryptString($r->eventos_id);
            $this->validarID($id);
            $usuario = Auth::user()->name;
            if (!Hash::check($r->password, Auth::user()->password)) {
                return throw new Exception('Contraseña incorrecta');
            }
            $evento = eventos::find($id);
            if ($evento->autoriza && $evento->autoriza_users_id) {
                return redirect()
                    ->back()
                    ->with('message', 'Este evento ya fue autorizado por ' . $usuario)
                    ->with('type', 'danger');
            }
            $evento->autoriza = now()->format('Y-m-d H:i:s');
            $evento->autoriza_users_id = Auth::id();
            $evento->comprobante = true;
            $evento->modificacion = isset($r->modificacion) ? (bool) $r->modificacion : false;
            $evento->save();
            return redirect()
                ->route('eventos.detalle', ['id' => $evento->cid])
                ->with('message', 'El evento ha sido autorizado por:  ' . $usuario)
                ->with('type', 'success');
        } catch (\Throwable $th) {
            return redirect()
                ->back()
                ->with('message', 'Error al guardar el registro:' . $th->getMessage())
                ->with('type', 'danger');
        }
    }
    ///*negacion de eventos
    public function negarEvento(Request $r)
    {
        try {
            $r->validate([
                'password' => ['required', 'string'],
                'observacion_negacion' => ['nullable', 'string', 'max:256'],
                'confirm' => ['required', 'in:1'],
                'modificacion' => ['nullable', 'in:1'],
                'eventos_id' => ['required', 'string', 'max:256'],
            ]);
            $id = Crypt::decryptString($r->eventos_id);
            $this->validarID($id);
            $usuario = Auth::user()->name;
            if (!Hash::check($r->password, Auth::user()->password)) {
                return throw new Exception('Contraseña incorrecta');
            }
            $evento = eventos::find($id);
            if ($evento->negacion_users_id) {
                return redirect()
                    ->back()
                    ->with('message', 'Este evento ya fue negado por ' . $usuario)
                    ->with('type', 'danger');
            }

            $evento->negacion_users_id = Auth::id();
            $evento->comprobante = false;
            $evento->solicita = null;
            $evento->observacion_negacion = $r->observacion_negacion;
            $evento->save();

            return redirect()->route('eventos.detalle', ['id' => Crypt::encryptString($evento->id),])
                ->with('message', 'El evento ha sido negada la autorizacion por:  ' . $usuario)
                ->with('type', 'success');
        } catch (\Throwable $th) {
            return redirect()
                ->back()
                ->with('message', 'Error al guardar el registro:' . $th->getMessage())
                ->with('type', 'danger');
        }
    }
    /**desbloquear eventos autorizados */
    public function desbloquearEvento(Request $r)
    {
        try {
            $r->validate([
                'password' => ['required', 'string'],
                'observacion_negacion' => ['nullable', 'string', 'max:256'],
                'confirm' => ['required', 'in:1'],
                'eventos_id' => ['required', 'string', 'max:256'],
            ]);
            $id = Crypt::decryptString($r->eventos_id);
            $this->validarID($id);
            $usuario = Auth::user()->name;
            if (!Hash::check($r->password, Auth::user()->password)) {
                return throw new Exception('Contraseña incorrecta');
            }
            $evento = eventos::find($id);

            $evento->autoriza_users_id = null;
            $evento->solicita = null;
            $evento->comprobante = false;
            $evento->autoriza = null;
            $evento->observacion_negacion = $r->observacion_negacion;
            $evento->save();

            return redirect()->back()
                ->with('message', 'El evento ha sido desbloqueado por :  ' . $usuario)
                ->with('type', 'success');
        } catch (\Throwable $th) {
            return redirect()
                ->back()
                ->with('message', 'Error al guardar el registro:' . $th->getMessage())
                ->with('type', 'danger');
        }
    }
    //**asignacion de sonidos a un evento  */
    public function asignarSonido(Request $r)
    {

        $r->validate([
            'sonidos_id' => ['nullable', 'string', 'max:200'],
            'observaciones_sonidos' => ['nullable', 'string', 'min:3', 'max:256'],
            'eventos_id' => ['required', 'string', 'max:256'],

        ]);
        try {

            $id = Crypt::decryptString($r->eventos_id);
            $this->validarID($id);
            $evento = eventos::find($id);
            $sonidos_id = isset($r->sonidos_id) ? Crypt::decryptString($r->sonidos_id) : null;
            $id = Crypt::decryptString($r->eventos_id);
            $this->validarID($id);


            $evento = eventos::find($id);
            if ($sonidos_id == null && $r->observaciones_sonidos == null) {
                return redirect()->back()->with('message', 'Realize una opcion agregar o actualizar sonido o la observacion .')->with('type', 'danger');
            }
            if (isset($sonidos_id) && $evento->sonidos_id) {
                if ($evento->sonidos_id == $sonidos_id) {
                    return redirect()->back()->with('message', 'Este sonido ya está asignado al evento. No se requieren cambios.')->with('type', 'info');
                } else {
                    if (isset($r->observaciones_sonidos))
                        $evento->observaciones_sonidos = $r->observaciones_sonidos;
                    if ($sonidos_id !== null)
                        $evento->sonidos_id = $sonidos_id;
                    $evento->save();
                    return redirect()
                        ->back()
                        ->with('message', 'Se actualizó el sonido asignado al evento: ' . $id)
                        ->with('type', 'success');
                }
            } else {
                if (isset($r->observaciones_sonidos))
                    $evento->observaciones_sonidos = $r->observaciones_sonidos;
                if ($sonidos_id !== null)
                    $evento->sonidos_id = $sonidos_id;
                $evento->save();
                return redirect()
                    ->back()
                    ->with('message', 'Se asignó el sonido al evento: ' . $id)
                    ->with('type', 'success');
            }
        } catch (\Throwable $th) {
            return redirect()
                ->back()
                ->with('message', 'Error al guardar el registro: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }
    //*funcion para actualizar observaciones sonidos
    public function updateObservacionSonido(Request $r)
    {
        $r->validate([

            'observaciones_sonidos' => ['required', 'string', 'min:3', 'max:256'],
            'eventos_id' => ['required', 'string', 'max:256'],

        ]);
        $e = eventos::find(Crypt::decryptString($r->eventos_id));
        $ob_sonidos = $r->observaciones_sonidos;

        try {
            $this->validarID($e->id);
            //actualizacion de observaciones de sonidos
            $e->observaciones_sonidos = $ob_sonidos;
            $e->save();
            return redirect()->back()->with('message', 'se actualizo la observaciones de el sonido del evento #:' . $e->id)->with('type', 'success');
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    //*funcion para actualizar el encargado
    public function actualizarEncargado(Request $r)
    {
        try {
            $r->validate([
                'encargado' => ['nullable', 'string', 'min:3', 'max:200'],
                'eventos_id' => ['required', 'string', 'max:256'],

            ]);
            $id = Crypt::decryptString($r->eventos_id);
            $this->validarID($id);
            $e = eventos::find($id);
            $e->encargado = $r->encargado;
            $e->save();
            return redirect()
                ->back()
                ->with('message', 'Se ha actualizado el encargado de el evento #: ' . $id)
                ->with('type', 'info');
        } catch (\Throwable $th) {
            return redirect()
                ->back()
                ->with('message', 'Error al actualizar el registro :' . $th->getMessage())
                ->with('type', 'danger');
        }
    }
    //*funcion para observaciones generales de evento
    public function observacionGeneral(Request $r)
    {
        try {
            $r->validate([
                'observaciones' => ['nullable', 'string', 'min:3'],
                'eventos_id' => ['required', 'string', 'max:256'],

            ]);
            $id = Crypt::decryptString($r->eventos_id);
            $this->validarID($id);
            $e = eventos::find($id);

            if ($e->observaciones) {
                $e->observaciones = $r->observaciones;
                $message = 'Se ha actualizado la observación general del evento #' . $id;
            } else {
                $e->observaciones = $r->observaciones;
                $message = 'Se ha agregado la observación general del evento #' . $id;
            }

            $e->save();
            return redirect()->back()->with('message', $message)->with('type', 'success');
        } catch (\Throwable $th) {
            return redirect()
                ->back()
                ->with('message', 'Error al agregar el registro: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }
    //*funcion para observaciones de como se va facturar el evento de evento
    public function observacionFactura(Request $r)
    {
        try {
            $r->validate([
                'observaciones_facturacion' => ['nullable', 'string', 'min:3', 'max:256'],
                'eventos_id' => ['required', 'string', 'max:256'],

            ]);
            $id = Crypt::decryptString($r->eventos_id);
            $this->validarID($id);
            $e = eventos::find($id);

            if ($e->observaciones_factura) {
                $e->observaciones_factura = $r->observaciones_facturacion;
                $message = 'Se ha actualizado la observación general del evento #' . $id;
            } else {
                $e->observaciones_factura = $r->observaciones_facturacion;
                $message = 'Se ha agregado la observación general del evento #' . $id;
            }

            $e->save();
            return redirect()->back()->with('message', $message)->with('type', 'success');
        } catch (\Throwable $th) {
            return redirect()
                ->back()
                ->with('message', 'Error al agregar el registro: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }
    //*funcion para observaciones montajes del evento
    public function updateMontaje(Request $r)
    {
        try {
            $r->validate([
                'montaje' => ['nullable', 'string', 'min:3', 'max:256'],
                'eventos_id' => ['required', 'string', 'max:256'],

            ]);
            $id = Crypt::decryptString($r->eventos_id);
            $this->validarID($id);
            $e = eventos::find($id);

            if ($e->montaje) {
                $e->montaje = $r->montaje;
                $message = 'Se ha actualizado la observación del montaje del evento #' . $id;
            } else {
                $e->montaje = $r->montaje;
                $message = 'Se ha agregado la observacion del montaje del evento #' . $id;
            }

            $e->save();
            return redirect()->back()->with('message', $message)->with('type', 'success');
        } catch (\Throwable $th) {
            return redirect()
                ->back()
                ->with('message', 'Error al agregar el registro: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }
    //*funcion de asignar montajes a evento
    public function asignarMontaje(Request $r)
    {
        try {
            $r->validate([
                'eventos_id' => ['required', 'string', 'max:256'],
                'montajes_id' => ['nullable', 'string', 'max:256'],
                'montaje' => ['nullable', 'string', 'min:3']

            ]);
            $id = Crypt::decryptString($r->eventos_id);
            $this->validarID($id);
            $evento = eventos::find($id);

            $montaje_id = isset($r->montajes_id) ? Crypt::decryptString($r->montajes_id) : null;
            $id = Crypt::decryptString($r->eventos_id);
            $this->validarID($id);

            $evento = eventos::find($id);
            if ($montaje_id == null && $r->montaje == null) {
                return redirect()->back()->with('message', 'Realize una accion una opcion agregar o actualizar sonido o la observacion .')->with('type', 'danger');
            }
            if (isset($montaje_id) && $evento->montajes_id) {
                if ($evento->montajes_id == $montaje_id) {
                    return redirect()->back()->with('message', 'Este montaje ya está asignado al evento. No se requieren cambios.')->with('type', 'info');
                } else {
                    if (isset($r->montaje))
                        $evento->montaje = $r->montaje;
                    if ($montaje_id !== null)
                        $evento->montajes_id = $montaje_id;
                    $evento->save();
                    return redirect()
                        ->back()
                        ->with('message', 'Se actualizó el montaje asignado al evento: ' . $id)
                        ->with('type', 'success');
                }
            } else {
                if (isset($r->montaje))
                    $evento->montaje = $r->montaje;
                if ($montaje_id !== null)
                    $evento->montajes_id = $montaje_id;
                $evento->save();
                return redirect()
                    ->back()
                    ->with('message', 'Se asignó el montaje al evento: ' . $id)
                    ->with('type', 'success');
            }
        } catch (\Throwable $th) {
            return redirect()
                ->back()
                ->with('message', 'Error al guardar el registro: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }
    //*esta funcion actualizara el tipo de eventos desde el detalle de el evento
    public function actualizarTipoEvento(Request $r)
    {
        try {
            $r->validate([
                'eventos_id' => ['required', 'string', 'max:256'],
                'tipo_eventos_id' => ['required', 'string', 'max:256'],

            ]);
            $id = Crypt::decryptString($r->eventos_id);
            $tipo = Crypt::decryptString($r->tipo_eventos_id);
            $this->validarID($id, $tipo);
            $e = eventos::find($id);
            if ($e->tipo_eventos_id) {
                if ($e->tipo_eventos_id == $tipo) {
                    return redirect()
                        ->back()
                        ->with('message', 'Este tipo de evento ya esta asignado al evento #: ' . $id)
                        ->with('type' . 'info');
                } else {
                    $e->tipo_eventos_id = $tipo;
                    $e->save();
                    return redirect()
                        ->back()
                        ->with('message', 'se actualizo el tipo de eventos en el evento # :' . $id)
                        ->with('type', 'success');
                }
            } else {
                $e->tipo_eventos_id = $tipo;
                $e->save();
                return redirect()->back()->with('message', 'se guardo el tipo de evento a este evento')->with('type', 'success');
            }
        } catch (\Throwable $th) {
            return redirect()
                ->back()
                ->with('message', 'Error al registrar el registro:' . $this->getMessage())
                ->with('type', 'danger');
        }
    }
    //**funcion para duplicar eventos y sus cuentas*/
    public function duplicarEvento($id)
    {
        try {
            $eventoId = Crypt::decryptString($id);

            $this->validarID($eventoId);
            if (!session()->has('caja')) {
                return redirect()->route('cajas.my')->with('message', 'Por favor inicie sesión en la caja')->with('type', 'info');
            }
            $evento = eventos::with(['clientes',  'sonidos', 'forma_pagos', 'tipo_eventos',  'montajes'])->find($eventoId);
            $ordenes = $evento->ordenesEvento($evento->id)
                ->orderBy('id', 'DESC')
                ->get();
            $comandas = $evento->comandasEvento($evento->id)
                ->orderBy('mesa', 'DESC')
                ->get();
            $salones = salones::all();
            $salonesEventoA = $evento->salonesEventoAnterior($evento->id);

            return view('eventos.duplicar', [
                'evento' => $evento,
                'ordenes' => $ordenes,
                'comandas' => $comandas,
                'salones' => $salones,
                'salonesEventoAnterior' => $salonesEventoA,
            ]);
        } catch (\Throwable $th) {
            return redirect()
                ->back()
                ->with('message', 'Error al cargar el detalle del evento: ' . $th->getMessage())
                ->with('type', 'error');
        }
    }
    /***funcion refactorizar para add cliente desde el detalle de evento */
    public function addClienteEvento(Request $r)
    {
        try {
            $r->validate([
                'eventos_id' => ['required', 'string', 'max:256'],
                'clientes_id' => ['required'],
            ]);
            $id = Crypt::decryptString($r->eventos_id);
            $c = $r->clientes_id;
            $this->validarID($id, $c);

            $evento = eventos::find($id);
            $cliente = clientes::find($r->clientes_id);
            if (!intval($evento->id) || !intval($cliente->id)) {
                throw new \Exception('No se pudo encontrar el evento y cliente .');
            }

            $evento->clientes_id = $cliente->id;
            $evento->save();
            $this->addClienteCuentasEvento($evento->titular, $evento->clientes_id);

            return redirect()->back()->with('message', 'Se agrego  el cliente a este evento.  ')->with('type', 'info');
        } catch (\Throwable $th) {
            return redirect()
                ->back()
                ->with('message', 'Ocurrio un error al intentar editar el cliente, error: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }
    private function addClienteCuentasEvento($titular, $cliente)
    {
        comandas::where('titular', $titular)->update(['clientes_id' => $cliente]);
        ordenes::where('titular', $titular)->update(['clientes_id' => $cliente]);
    }


    //***funcion para actualizar cliente desde el detalle de eventos */
    public function updateClienteEvento(Request $r)
    {
        try {
            $r->validate([
                'eventos_id' => ['required', 'string', 'max:256'],
                'clientes_id' => ['required'],
            ]);
            $id = Crypt::decryptString($r->eventos_id);
            $c = $r->clientes_id;
            $this->validarID($id, $c);
            $evento = eventos::find($id);
            $cliente = clientes::find($r->clientes_id);
            if (!$evento || !$cliente) {
                throw new \Exception('No se pudo encontrar el evento .');
            }

            $clienteAnterior = $evento->clientes_id;
            $clienteNuevo = $cliente->id;

            $this->updateClienteCuentasEvento($clienteAnterior, $clienteNuevo);
            $evento->clientes_id = $cliente->id;
            $evento->save();
            if (isset($r->anticipos_id) && count($r->anticipos_id) > 0) {
                foreach ($r->anticipos_id as $ap) {
                    $anticipo_id = Crypt::decryptString($ap);
                    if (!intval($anticipo_id) || $anticipo_id <= 0) {
                        throw new \Exception('Error no se encontraron los anticipos.');
                    }
                    $atpo = anticipos::find($anticipo_id);
                    $atpo->clientes_id = $cliente->id;
                    $atpo->save();
                }
                return redirect()
                    ->back()
                    ->with('message', 'Se cambio el cliente a este evento y se cambiaron ' . count($r->anticipos_id) . ' anticipo(s).')
                    ->with('type', 'info');
            }
            return redirect()
                ->back()
                ->with('message', 'Se cambio el cliente a este evento # ' . $evento->id)
                ->with('type', 'info');
        } catch (\Throwable $th) {
            return redirect()
                ->back()
                ->with('message', 'Ocurrio un error al intentar editar el cliente, error: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }
    private function updateClienteCuentasEvento($clienteAnterior, $clienteNuevo)
    {
        comandas::where('clientes_id', $clienteAnterior)->update(['clientes_id' => $clienteNuevo]);
        ordenes::where('clientes_id', $clienteAnterior)->update(['clientes_id' => $clienteNuevo]);
    }
    public function duplicacionEvento(DuplicarEventoRequest $r)
    {
        try {
            $id = Crypt::decryptString($r->eventos_id);
            $this->validarID($id);
            if ($r->fecha === $r->fecha_fin) {
                $actual = Carbon::now('America/El_Salvador')->addHours(3)->format('H:i');
                $inicio = Carbon::createFromFormat('H:i', $r->inicio, 'America/El_Salvador')->format('H:i');
                $finaliza = Carbon::createFromFormat('H:i', $r->finalizacion, 'America/El_Salvador')->format('H:i');

                $fechaA = now()->format("Y-m-d");
                $fechaRequerida = $r->fecha;

                if (isset($fechaA) && $fechaA == $fechaRequerida) {
                    // Validar que la hora de inicio sea igual a la hora actual ajustada
                    if ($inicio < $actual) {
                        return redirect()
                            ->back()
                            ->with('message', 'La hora de inicio no es válida.')
                            ->with('type', 'danger');
                    }
                } else {
                    // Validar que la hora de inicio sea menor que la hora de finalización
                    if ($inicio > $finaliza) {
                        return redirect()
                            ->back()
                            ->with('message', 'La hora de inicio debe ser menor que la hora de finalización.')
                            ->with('type', 'danger');
                    }
                }
            }
            $e = eventos::find($id);

            $de = $e->replicate();


            $de->fecha = $r->fecha;
            $de->fecha_fin = $r->fecha_fin;
            $de->inicio = $r->inicio;
            $de->finalizacion = $r->finalizacion;
            $de->solicita = null;
            $de->autoriza = null;
            $de->autoriza_users_id = null;
            $de->negacion_users_id = null;
            $de->observacion_negacion = null;
            $de->modificacion = false;
            $de->comprobante = false;
            $de->save();
            // Duplicar eventos_galerias
            $eventosGalerias = eventos_galerias::where('eventos_id', $e->id)->get();
            if (isset($eventosGalerias) && count($eventosGalerias) > 0) {
                foreach ($eventosGalerias as $eventoGaleria) {
                    $nuevaEventoGaleria = $eventoGaleria->replicate();
                    $nuevaEventoGaleria->eventos_id = $de->id;
                    $nuevaEventoGaleria->save();
                }
            }
            // Duplicar detalle_montajes_eventos
            $detalle_montajes = detalle_montaje_eventos::where('eventos_id', $e->id)->get();
            if (isset($detalle_montajes) && count($detalle_montajes) > 0) {
                foreach ($detalle_montajes as $d_m) {
                    $n_detalle_montaje = $d_m->replicate();
                    $n_detalle_montaje->eventos_id = $de->id;
                    $n_detalle_montaje->save();
                }
            }

            if (isset($r->comandas) && count($r->comandas) > 0) {
                foreach ($r->comandas as $comandaId) {
                    $id_comanda = Crypt::decryptString($comandaId);
                    if (!intval($id_comanda) || $id_comanda <= 0) {
                        throw new \Exception('No se encontraron comandas válidas.');
                    }

                    $comandaOriginal = comandas::find($id_comanda);
                    if (!$comandaOriginal) {
                        throw new \Exception('No se encontró la comanda con ID: ' . $id_comanda);
                    }

                    $nuevaComanda = $comandaOriginal->replicate();
                    $nuevaComanda->clientes_id = $de->clientes_id ?? null;
                    $nuevaComanda->titular = $de->titular ?? null;
                    $nuevaComanda->comprobante = false;
                    $nuevaComanda->facturada = false;
                    $nuevaComanda->estado = true;
                    $nuevaComanda->save();


                    foreach ($comandaOriginal->detalles_comanda as $detalle) {
                        $nuevoDetalle = $detalle->replicate();
                        $nuevoDetalle->comandas_id = $nuevaComanda->id;
                        $nuevoDetalle->save();
                    }

                    (new EventoCuentasController())->eventoCuentas($nuevaComanda->id, $de->id, 3);
                }
            }

            if (isset($r->ordenes) && count($r->ordenes) > 0) {
                foreach ($r->ordenes as $ordenId) {
                    $id_orden = Crypt::decryptString($ordenId);
                    if (!intval($id_orden) || $id_orden <= 0) {
                        throw new \Exception('No se encontraron órdenes válidas.');
                    }
                    $ordenOriginal = ordenes::find($id_orden);
                    if (!$ordenOriginal) {
                        throw new \Exception('No se encontró la orden con ID: ' . $id_orden);
                    }
                    $nuevaOrden = $ordenOriginal->replicate();
                    $nuevaOrden->clientes_id = $de->clientes_id ?? null;
                    $nuevaOrden->titular = $de->titular ?? null;
                    $nuevaOrden->comprobante = false;
                    $nuevaOrden->facturada = false;
                    $nuevaOrden->estado = true;
                    $nuevaOrden->save();

                    foreach ($ordenOriginal->detalle_orden as $detalle) {
                        $nuevoDetalle = $detalle->replicate();
                        $nuevoDetalle->ordenes_id = $nuevaOrden->orden;
                        $nuevoDetalle->save();
                    }


                    (new EventoCuentasController())->eventoCuentas($nuevaOrden->orden, $de->id, 1);
                }
            }


            (new EventosSalonesController())->duplicarEventoSalones($de->id, $r->salones, $r->mismos);

            return redirect()->route('eventos.eventos')->with('message', 'El evento se duplicó exitosamente.')->with('type', 'success');
        } catch (\Throwable $th) {
            return redirect()
                ->back()
                ->with('message', 'Ocurrio un error al intentar duplicar el evento, error: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }
    private function validarID($evento, $cliente = null)
    {

        if (!intval($evento) || $evento <= 0) {
            throw new \Exception('Error no se encontro el evento evento.');
        }
        if ($cliente != null) {
            if (!intval($cliente) || $cliente <= 0)
                throw new \Exception('Error no se encontro el cliente.');
        }
    }
    //*/*Todo reporte de eventos pendiente
    //**funcion para poder usar pdf con http */
    protected function getPDF(): DomPDFPDF
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
    //**esta funcion imprimira el detalle de evento no importara si esta autorizado */
    public function printEvento($id)
    {
        $evento = eventos::with(['clientes', 'usuarios', 'sonidos', 'forma_pagos', 'tipo_eventos', 'autorizacion', 'montajes', 'salones'])->find(Crypt::decryptString($id));
        $pago_anticipado = DB::table('evento_cuentas')
            ->leftJoin('aplicacion_pagos', 'evento_cuentas.origen_id', '=', 'aplicacion_pagos.origen_id')
            ->leftJoin('pago_anticipados', 'aplicacion_pagos.pago_anticipados_id', '=', 'pago_anticipados.id')
            ->where('evento_cuentas.eventos_id', $evento->id)
            ->distinct()
            ->pluck('pago_anticipados.monto')
            ->first();

        $impresionEvento = $evento->id;
        return $this->getImpresionEvento($impresionEvento);
        return view('eventos.print', [
            'evento' => $evento,
            'pago_anticipado',
            $pago_anticipado,
        ]);
    }
    public function getImpresionEvento($impresionEvento)
    {

        $evento = eventos::find($impresionEvento);
        $pago_anticipado = DB::table('evento_cuentas')
            ->leftJoin('aplicacion_pagos', 'evento_cuentas.origen_id', '=', 'aplicacion_pagos.origen_id')
            ->leftJoin('pago_anticipados', 'aplicacion_pagos.pago_anticipados_id', '=', 'pago_anticipados.id')
            ->where('evento_cuentas.eventos_id', $evento->id)
            ->distinct()
            ->pluck('pago_anticipados.monto')
            ->first();
        return view('eventos.containerbyEvento', compact('evento', 'pago_anticipado'), ['url' => route('eventos.getImpresionEvento', ['impresionEvento' => Crypt::encryptString($impresionEvento)])]);
    }
    public function reportebyEvento(Request $r)
    {
        $evento = eventos::find(Crypt::decryptString($r->impresionEvento));
        $pago_anticipado = DB::table('evento_cuentas')
            ->leftJoin('aplicacion_pagos', 'evento_cuentas.origen_id', '=', 'aplicacion_pagos.origen_id')
            ->leftJoin('pago_anticipados', 'aplicacion_pagos.pago_anticipados_id', '=', 'pago_anticipados.id')
            ->where('evento_cuentas.eventos_id', $evento->id)
            ->distinct()
            ->pluck('pago_anticipados.monto')
            ->first();
        /*$pdf = $this->getPDF();
        $pdf->loadView('eventos.print', [
            'evento' => $evento,
            'pago_anticipado' => $pago_anticipado,
        ]);


        return $pdf->stream();
        */
        $snap = SnappyPdf::loadView('eventos.print', [
            'evento' => $evento,
            'pago_anticipado' => $pago_anticipado,
        ])
            ->setPaper('letter');

        return $snap->inline('Contrato_' . $evento->id . '.pdf');
    }
    /**funcion para la vista del panel de eventos pendientes por autorizar  */
    public function panelAutorizacion()
    {
        return view('eventos.autorizar-eventos', [
            'p' => eventos::with(['clientes', 'usuarios', 'sonidos', 'forma_pagos', 'tipo_eventos', 'autorizacion', 'montajes', 'salones'])
                ->whereNotNull('solicita')
                ->whereNull('autoriza')
                ->whereNull('autoriza_users_id')
                ->orderBy('id', 'DESC')
                ->get(),
        ]);
    }
    //**funcion para vizualizar el detalle del evento y autorizarlo */
    public function detalleAutorizar(Request $r)
    {
        $id = Crypt::decryptString($r->id);
        $this->validarID($id);
        $evento = eventos::with(['clientes', 'usuarios', 'sonidos', 'forma_pagos', 'tipo_eventos', 'autorizacion', 'montajes', 'salones'])->find($id);

        return view('eventos.detalle-autorizar-evento', [
            'evento' => $evento
        ]);
    }
    /***funcion para facturacion anticipada de eventos */
    public function facturaAnticipada($id)
    {
        try {
            $id = Crypt::decryptString($id);

            $this->validarID($id);
            if (!session()->has('caja')) {
                return redirect()->route('cajas.my')->with('message', 'Por favor inicie sesión en la caja')->with('type', 'info');
            }
            $evento = eventos::find($id);
            $ordenes = $evento->cuentaOrdenes($evento->id)->where('tipo_orden', 5)->where('comprobante', true)->where('facturada', false)->where('estado', true)
                ->orderBy('id', 'DESC')
                ->get();
            $comandas = $evento->cuentaComandas($evento->id)->where('comprobante', true)->where('facturada', false)->where('estado', true)
                ->orderBy('mesa', 'DESC')
                ->get();
            $anticipos_reservados = $evento->getAnticipos()->get();
            $forma_pagos = forma_pagos::all();

            return view('eventos.factura-anticipada', [
                'p' => $evento,
                'comandas' => $comandas,
                'ordenes' => $ordenes,
                'formas' => $forma_pagos,
                'anticipos' => $anticipos_reservados
            ]);
        } catch (\Throwable $th) {
            return redirect()
                ->back()
                ->with('message', 'Error al cargar el detalle del evento: ' . $th->getMessage())
                ->with('type', 'error');
        }
    }
    //**esta function retornara la vista para crear reservaciones de eventos */
    public function reservacionEvento($id)
    {
        try {
            $id = Crypt::decryptString($id);

            $this->validarID($id);
            if (!session()->has('caja')) {
                return redirect()->route('cajas.my')->with('message', 'Por favor inicie sesión en la caja')->with('type', 'info');
            }
            $evento = eventos::find($id);
            $tipo_reservaciones = tipo_reservaciones::all();

            return view('eventos.reservaciones_evento', [
                'evento' => $evento,
                'tipo_reservaciones' => $tipo_reservaciones,
            ]);
        } catch (\Throwable $th) {
            return redirect()
                ->back()
                ->with('message', 'Error al cargar el detalle del evento: ' . $th->getMessage())
                ->with('type', 'error');
        }
    }
    /**funcion replica de detalle_ordenes index para poder usarlo desde el detalle de eventos */
    public function detalleOrden($id, $eventoId)
    {

        $ordenes = ordenes::findOrFail(Crypt::decryptString($id));
        $evento = eventos::findOrFail(Crypt::decryptString($eventoId));

        return view('eventos.ordenes-evento', [
            'th' => $this->th['ordenesEvento'],
            'p' => $ordenes,
            'eventoId' => $evento,
            'detalleOrdenes' => $this->getDetalleOrden(Crypt::decryptString($ordenes->id)),
            'descuentos' => descuentos::orderBy('descuento', 'ASC')->get(),
            'table' => $this->table,
        ]);
    }
    public function getDetalleOrden($ordenId)
    {
        return detalle_ordenes::with(['servicios', 'descuentos'])->where('ordenes_id', '=', $ordenId)->orderBy('id', 'DESC')->get();
    }
    /**section para reporte de eventos */

    public function eventos_reporte(Request $r)
    {

        if (isset($r->fecha_inicio) || isset($r->fecha_fin)) {
            $validar = $r->validate(['fecha_inicio' => ['required', 'date'], 'fecha_fin' => ['required', 'date']]);
            if (!$validar) {
                return redirect()->back()->with('message', 'Las fechas no son validas')->with('type', 'danger');
            }
        }

        if (isset($r->accion)) {
            $accion = Crypt::decryptString($r->accion);
            if ($accion == 2 || $accion == 3 && isset($r->fecha_inicio) && isset($r->fecha_fin)) {
                return $this->getReporteEventos($r->fecha_inicio, $r->fecha_fin, $accion);
            }
        }
        $fecha_inicio = $r->fecha_inicio ?? date('Y-m-d');
        $fecha_fin = $r->fecha_fin ?? date('Y-m-d');
        $eventos = eventos::with(['clientes', 'sonidos', 'tipo_eventos', 'forma_pagos', 'montajes', 'usuarios', 'autorizacion'])
            ->where(fn($q) => $q->whereBetween('fecha', [$fecha_inicio, $fecha_fin])->orWhereBetween('fecha_fin', [$fecha_inicio, $fecha_fin]))
            ->orderBy('id', 'DESC')
            ->get();
        return view('eventos.reporte_eventos', compact('fecha_inicio', 'fecha_fin', 'eventos'));
    }
    public function getReporteEventos($fecha_inicio, $fecha_fin, $accion)
    {
        if ($accion == 2)
            return view('eventos.container_eventos', ['url' => route('eventos.eventos_reporte_pdf', ['fecha_inicio' => Crypt::encryptString($fecha_inicio), 'fecha_fin' => Crypt::encryptString($fecha_fin)])]);
        if ($accion == 3)
            return view('eventos.container_eventos', ['url' => route('eventos.eventos_reporte_general_pdf', ['fecha_inicio' => Crypt::encryptString($fecha_inicio), 'fecha_fin' => Crypt::encryptString($fecha_fin)])]);
    }
    //**reporte de eventos con precios en detalle de cuentas y anticipos */
    public function getReporteEventosPDF(Request $r)
    {
        $fecha_inicio = Crypt::decryptString($r->fecha_inicio);
        $fecha_fin = Crypt::decryptString($r->fecha_fin);


        $eventos = eventos::where(fn($q) => $q->whereBetween('fecha', [$fecha_inicio, $fecha_fin])->orWhereBetween('fecha_fin', [$fecha_inicio, $fecha_fin]))
            ->orderBy('id', 'ASC')
            ->get();
        $pdf = $this->getPDF();
        $pdf->loadView('eventos.eventos_print', [
            'eventos' => $eventos
        ]);
        return $pdf->stream('eventos.eventos_print');
    }
    /**reporte de eventos sin precios en su detalle de cuentas y sin anticipos  */
    public function getReporteByEventos(Request $r)
    {
        $fecha_inicio = Crypt::decryptString($r->fecha_inicio);
        $fecha_final = crypt::decryptString($r->fecha_fin);
        $eventos = eventos::whereBetween('fecha', [$fecha_inicio, $fecha_final])->orderBy('id', 'ASC')->get();
        $pdf = $this->getPDF();
        $pdf->loadView('eventos.eventos_print_general', [
            'eventos' => $eventos,
        ]);
        return $pdf->stream('eventos.eventos_print_general');
    }
    //* reporte de sonidos usados en eventos
    public function reporteSonidos(Request $r)
    {
        $fecha = $r->fecha ?? date('Y-m-d');
        $sonido = $r->sonido ?? (session('sonido') ? session('sonido')->id : null);
        $eventos = $this->getDataEvento($fecha, $sonido, null);
        $opcion = $r->opcion ?? 1;

        switch ($opcion) {
            case 1:
                return view('report.eventos.sonido', ['data' => $eventos, 'fecha' => $fecha, 'sonidos' => sonidos::all(), 'sonido' => $sonido]);
                break;
            case 2:
                $pdf = $this->getPDF();

                $pdf->loadView(
                    'report.eventos.sonido_print',
                    ['data' => $eventos]
                );

                $pdf->setPaper('letter', 'landscape');
                return $pdf->stream();
                break;
            default:
                # code...
                break;
        }
    }

    private function getDataEvento($fecha, $sonido = null, $montaje = null)
    {
        $evento = eventos::whereBetween(DB::raw("'" . $fecha . "'"), [DB::raw('fecha'), DB::raw('fecha_fin')]);

        $evento->when($sonido > 0, function ($query) use ($sonido) {
            $query->where('sonidos_id', $sonido);
        });

        $evento->when($montaje > 0, function ($query) use ($montaje) {
            $query->where('montajes_id', $montaje);
        });

        return $evento->get();
    }
    //**reporte de montaje de eventos */
    public function reporteMontajes(Request $r)
    {
        $fecha = $r->fecha ?? date('Y-m-d');
        $montaje = $r->montaje ?? (session('montaje') ? session('montaje')->id : null);
        $eventos = $this->getDataEvento($fecha, null, $montaje);
        $opcion = $r->opcion ?? 1;

        switch ($opcion) {
            case 1:
                return view('report.eventos.montaje', ['data' => $eventos, 'fecha' => $fecha, 'montajes' => montajes::all(), 'montaje' => $montaje]);
                break;
            case 2:
                $pdf = $this->getPDF();

                $pdf->loadView(
                    'report.eventos.montaje_print',
                    ['data' => $eventos]
                );

                $pdf->setPaper('letter', 'landscape');
                return $pdf->stream();
                break;
            default:
                # code...
                break;
        }
    }

    //**reporte de ventas de eventos */
    public function reporteVenta(Request $r)
    {
        return view('report.eventos.venta', [
            'tipo' => tipo_eventos::all(),
        ]);
    }
    public function reporteVentas(Request $r)
    { //refactorizar no debe duplicar
        try {
            $opcion = $r->opcion;
            $inicio = $r->inicio;
            $fin = $r->fin;
            $tipo_evento = $r->tipo_evento;
            $eventos = eventos::whereBetween('fecha', [$inicio, $fin]);
            if ($tipo_evento > 0)
                $eventos = $eventos->where('tipo_eventos_id', $tipo_evento);

            $eventos = $eventos->with(['tipo_eventos', 'usuarios'])->get();
            $usuarios = $eventos->pluck('users_id')->unique();

            $vendedor = User::whereIn('id', $usuarios)->get();
            switch ($opcion) {
                case 1:
                    return view('report.eventos.venta', [
                        'tipo' => tipo_eventos::all(),
                        'eventos' => $eventos,
                        'vendedor' => $vendedor,
                        'inicio' => $inicio,
                        'fin' => $fin,
                        'evento' => $tipo_evento,
                    ]);
                    break;
                case 2:
                    $pdf = $this->getPdf();
                    $pdf->loadView(
                        'report.eventos.venta_print',
                        [
                            'eventos' => $eventos,
                            'vendedor' => $vendedor
                        ]
                    );
                    $pdf->setPaper('letter', 'landscape');
                    return $pdf->stream();
                    break;
                case 3:
                    $view = view('report.eventos.venta_excel', [
                        'eventos' => $eventos,
                        'vendedor' => $vendedor
                    ]);
                    $rs = Excel::download(new ReservacionesExport($view), 'reporte_ventas_' . $inicio . '_al_' . $fin . '.xlsx', \Maatwebsite\Excel\Excel::XLS);
                    ob_end_clean();
                    return $rs;
                    break;
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    //**Todo se trabajara el reporte de descargo por evento */
    public function descargo()
    {
        return view('report.eventos.descargo', [
            'tipo' => tipo_eventos::all(),
        ]);
    }

    public function reporteDescargo(Request $r) //se refactorizo la forma
    {
        try {
            $opcion = $r->opcion;
            $inicio = $r->inicio;
            $fin = $r->fin;
            $tipo_evento = $r->tipo_evento;
            $eventos = eventos::whereBetween('fecha', [$inicio, $fin]);
            if ($tipo_evento > 0)
                $eventos = $eventos->where('tipo_eventos_id', $tipo_evento);
            $eventos = $eventos->with(['tipo_eventos', 'usuarios'])->get();
            $usuarios = $eventos->pluck('users_id')->unique();

            $vendedor = User::whereIn('id', $usuarios)->get();
            //encontrando las cuentas de los eventos
            $cuentas_eventos = evento_cuentas::whereIn('eventos_id', $eventos->pluck('id'))->get();
            $cuentaComanda = comandas::whereIn('id', $cuentas_eventos->where('origen', 3)->pluck('origen_id'))->get();
            $detallesComanda = $cuentaComanda->flatMap(function ($comanda) {
                return $comanda->detalles_comanda;
            });

            $caja_id = $cuentaComanda->pluck('cajas_id')->first();
            $turno_id = $cuentaComanda->pluck('turnos_id')->first();
            $caja = cajas::find($caja_id);
            $turno = turnos::with('uapertura')
                ->with('ucierre')
                ->with('opcion')
                ->find($turno_id);
            switch ($opcion) {
                case 1:
                    return view('report.eventos.descargo', [
                        'tipo' => tipo_eventos::all(),
                        'eventos' => $eventos,
                        'inicio' => $inicio,
                        'fin' => $fin,
                        'evento' => $tipo_evento,
                        'caja' => $caja,
                        'turno' => $turno,
                        'vendedor' => $vendedor,

                    ]);
                    break;
                case 2:
                    $pdf = $this->getPdf();
                    $pdf->loadView(
                        'report.eventos.descargo_print',
                        [
                            'eventos' => $eventos,
                            'caja' => $caja,
                            'turno' => $turno,
                            'vendedor' => $vendedor,
                            'inicio' => $inicio,
                            'fin' => $fin,
                            'comandas' => $detallesComanda

                        ]
                    );
                    $pdf->setPaper('letter', 'landscape');
                    return $pdf->stream();
                    break;
                case 3:
                    $view = view('report.eventos.descargo_excel', [
                        'eventos' => $eventos,
                        'caja' => $caja,
                        'turno' => $turno,
                        'vendedor' => $vendedor,
                        'comandas' => $detallesComanda,
                    ]);
                    $rs = Excel::download(new ReservacionesExport($view), 'reporte_descargo_' . $inicio . '_al_' . $fin . '.xlsx', \Maatwebsite\Excel\Excel::XLS);
                    ob_end_clean();
                    return $rs;
                    break;
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    //reporte de produccion de cocina de comandas de eventos
    public function cocina()
    {
        return view('report.eventos.cocina', [
            'tipo' => tipo_eventos::all(),
        ]);
    }
    public function cocinaDescargo(Request $r)
    {
        try {
            $opcion = $r->opcion ?? 1;
            $inicio = $r->inicio ?? date('Y-m-d');
            $fin = $r->fin ?? date('Y-m-d');

            $eventos = eventos::whereBetween('fecha', [$inicio, $fin]);
            $cuentas_eventos = evento_cuentas::whereIn('eventos_id', $eventos->pluck('id'))->get();
            $cuentaComanda = comandas::whereIn('id', $cuentas_eventos->where('origen', 3)->pluck('origen_id'))->whereBetween('fecha', [$inicio, $fin])->get();

            $caja_id = $cuentaComanda->pluck('cajas_id')->first();
            $turno_id = $cuentaComanda->pluck('turnos_id')->first();
            $caja = cajas::find($caja_id);
            $turno = turnos::with('uapertura')
                ->with('ucierre')
                ->with('opcion')
                ->find($turno_id);
            $detalle = comanda_detalles::whereIn('comandas_id', $cuentaComanda->pluck('id'))
                ->leftJoin('precios', "comanda_detalles.precios_id", 'precios.id')
                ->leftJoin('users', "comanda_detalles.users_comanda_id", 'users.id')
                ->with("comandas")
                ->with("user_comanda")
                ->select(['comanda_detalles.*', 'precios.detalle', 'categorias_precios_id', 'users.email'])
                ->get();
            $categorias = categorias_precios::whereIn('id', $detalle->pluck('categorias_precios_id'))
                ->orderBy('categoria')
                ->get();
            switch ($opcion) {
                case 1:
                    return view('report.eventos.cocina', [

                        'inicio' => $inicio,
                        'fin' => $fin,

                        'caja' => $caja,
                        'turno' => $turno,
                        'categorias' => $categorias,
                        'comandas' => $detalle,
                    ]);
                    break;
                case 2:
                    $pdf = $this->getPdf();
                    $pdf->loadView(
                        'report.eventos.cocina_print',
                        [
                            'eventos' => $eventos,
                            'caja' => $caja,
                            'turno' => $turno,
                            'categorias' => $categorias,
                            'comandas' => $detalle,
                        ]
                    );
                    $pdf->setPaper('letter', 'landscape');
                    return $pdf->stream();
                    break;
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
