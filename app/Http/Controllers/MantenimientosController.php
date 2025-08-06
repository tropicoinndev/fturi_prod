<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoremantenimientosRequest;
use App\Http\Requests\UpdatemantenimientosRequest;
use App\Models\estado_habitaciones;
use App\Models\habitaciones;
use App\Models\mantenimientos;
use App\Models\recepciones;
use App\Models\tipo_mantenimiento_users;
use App\Models\tipo_mantenimientos;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Telegram\Bot\Laravel\Facades\Telegram;
//Quitar totas las declaraciones de interfaces no utilizadas  ya lo realize
class MantenimientosController extends Controller
{
    private $table = 'mantenimientos';

    public function __construct()
    {
        $this->getTh($this->table, 'Mantenimientos');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $mantenimientos = mantenimientos::with('tipo_mantenimientos', 'habitaciones', 'users','asignado')
                ->whereNull('finalizacion')
                ->orderBy('id', 'DESC')
                ->paginate(15);



        return view($this->table . '.index', [
            'th' => $this->th['index'],
            'p' => $mantenimientos,
            'table' => $this->table,
            'data' => [
                'tipo_mantenimientos' => tipo_mantenimientos::orderBy('id', 'DESC')->get(),
                'habitaciones' => habitaciones::orderBy('id', 'ASC')->get(),
                'users' => User::orderBy('id','DESC')->get(),
            ],
        ]);
    }
    //*funcion para mostrar usarios que pueden realizar el tipo de mantenimiento que se asigno en el mantenimiento/*/
    public function usuariosBytipoMantenimiento(Request $r){
        try {

            $t = tipo_mantenimientos::findOrFail($r->id);
            $usuarios = User::whereIn('id', function ($query) use ($t) {
                $query->select('users_id')
                    ->from('tipo_mantenimiento_users')
                    ->where('tipo_mantenimientos_id', $t->id);
            })->select('id', 'name')->get();
            return response()->json(['usuarios' => $usuarios]);
        } catch (\Exception $e) {
            // Maneja cualquier error que ocurra durante el proceso
            return response()->json(['message' => 'Error al obtener usuarios por tipo de mantenimiento mantenimiento', 'error' => $e->getMessage()], 500);
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
            'p' => mantenimientos::with('tipo_mantenimientos', 'habitaciones', 'users')
                ->orderBy('id', 'DESC')
                ->get(),
            'table' => $this->table,
            'data' => [
                'tipo_mantenimientos' => tipo_mantenimientos::orderBy('id', 'DESC')->get(),
                'habitaciones' => habitaciones::orderBy('id', 'DESC')->get(),
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoremantenimientosRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoremantenimientosRequest $r)
    {
        try {
            $mantenimientoExistente = mantenimientos::where('tipo_mantenimientos_id', $r->tipo_mantenimientos_id)
                ->where('habitaciones_id', $r->habitaciones_id)
                ->whereNull('finalizacion')
                ->exists();
            if ($mantenimientoExistente) {
                return redirect()
                    ->route($this->table . '.index')
                    ->with('message', 'Ya existe un mantenimiento para esta habitación con este tipo de mantenimiento pero no se ha completado hasta que se complete se podra crear otro.')
                    ->with('type', 'danger');
            }

            $p = new mantenimientos();
            $p->fecha = now();
            $p->asignacion = $r->asignacion ?? null;
            $p->finalizacion = $r->finalizacion ?? null;
            $p->estado = $r->estado ?? 'sin asignar';
            $p->asignado_users_id = $r->asignado_users_id ?? null;
            $p->supervisor_users_id = $r->supervisor_users_id ?? null;
            $p->creacion_users_id = Auth::user()->id;
            $p->confirmacion_asignacion = $r->confirmacion_asignacion ?? null;
            $p->tipo_mantenimientos_id = $r->tipo_mantenimientos_id;
            $p->habitaciones_id = $r->habitaciones_id;
            $p->observacion = $r->observacion ?? null;
            $p->bitacora_asignado = $r->bitacora_asignado ?? null;
            $p->save();
            // Obtener el tipo de mantenimiento asociado al mantenimiento
            $tipoMantenimiento = tipo_mantenimientos::find($r->tipo_mantenimientos_id);

            // Verificar si estado_habitacion_inicio_id no es nulo en el tipo de mantenimiento
            if ($tipoMantenimiento->estado_habitacion_inicio_id !== null) {
                $habitacion = habitaciones::find($r->habitaciones_id);
                $habitacion->estado_habitaciones_id = $tipoMantenimiento->estado_habitacion_inicio_id;
                $habitacion->save();
            }
            $user = Auth::user();
            $message = 'Hola, se  ha creado la siguiente tarea debe asignarla o auto-asignarse:';
            $message .= "\n \xE2\x9C\x94 *" . $tipoMantenimiento->mantenimiento . '*: en habitación #*' . $habitacion->numero_habitacion . '*, Creado por: ' . $user->name;

            if ($tipoMantenimiento->notificacion && $tipoMantenimiento->id_grupo_telegram != null) {
                Telegram::sendMessage([
                    'parse_mode' => 'Markdown',
                    'chat_id' => $tipoMantenimiento->id_grupo_telegram,
                    'text' => $message,
                ]);
            }
            return redirect()
                ->route('mantenimientos.index')
                ->with([
                    'message' => 'Mantenimiento creado exitosamente.',
                    'type' => 'success',
                ]);
        } catch (\Exception $e) {
            return redirect()
                ->route('mantenimientos.index')
                ->with([
                    'message' => 'Ocurrió un error al crear el mantenimiento.',
                    'type' => 'error',
                ]);
        }
    }
    /***function para crear mantenimiento desde el detalle de una habitacion desde check int */
    public function detalleHabitacion(Request $r)
    {
         try {
                $tipo_mantenimientos_id = Crypt::decryptString($r->tipo_mantenimientos_id);
                $habitaciones_id = Crypt::decryptString($r->habitacion_id);

                $mantenimientoExistente = mantenimientos::where('tipo_mantenimientos_id', $tipo_mantenimientos_id)
                    ->where('habitaciones_id', $habitaciones_id)
                    ->whereNull('finalizacion')
                    ->exists();

                if ($mantenimientoExistente) {
                    return redirect()
                        ->route($this->table . '.index')
                        ->with('message', 'Ya existe un mantenimiento para esta habitación con este tipo de mantenimiento pero no se ha completado hasta que se complete se podrá crear otro.')
                        ->with('type', 'danger');
                }

                $user = Auth::user();
                $tipoMantenimiento = tipo_mantenimientos::find($tipo_mantenimientos_id);

                $p = new mantenimientos();
                $p->fecha = now();
                $p->asignacion = $r->asignacion ?? null;
                $p->finalizacion = $r->finalizacion ?? null;
                $p->estado = $r->estado ?? 'sin asignar';
                $p->asignado_users_id = $r->asignado_users_id ?? null;
                $p->supervisor_users_id = $r->supervisor_users_id ?? null;
                $p->creacion_users_id = $user->id;
                $p->confirmacion_asignacion = $r->confirmacion_asignacion ?? null;
                $p->tipo_mantenimientos_id = $tipo_mantenimientos_id;
                $p->habitaciones_id = $habitaciones_id;
                $p->observacion = $r->observacion ?? null;
                $p->bitacora_asignado = $r->bitacora_asignado ?? null;
                $p->save();

                // Verificar si estado_habitacion_inicio_id no es nulo en el tipo de mantenimiento
                if ($tipoMantenimiento->estado_habitacion_inicio_id !== null) {
                    $habitacion = habitaciones::find($habitaciones_id);
                    $habitacion->estado_habitaciones_id = $tipoMantenimiento->estado_habitacion_inicio_id;
                    $habitacion->save();
                }

                $message = 'Hola, se le ha creado la siguiente tarea debe asignarla o auto-asignarse:';
                $message .= "\n \xE2\x9C\x94 *" . $tipoMantenimiento->mantenimiento . '*: en habitación #*' . $habitacion->numero_habitacion . '*, Creado por: ' . $user->name;

                if ($tipoMantenimiento->notificacion && $tipoMantenimiento->id_grupo_telegram != null) {
                    Telegram::sendMessage([
                        'parse_mode' => 'Markdown',
                        'chat_id' => $tipoMantenimiento->id_grupo_telegram,
                        'text' => $message,
                    ]);
                }

                return redirect()
                    ->route('recepciones.index')
                    ->with([
                        'message' => 'Mantenimiento creado exitosamente.',
                        'type' => 'success',
                    ]);
            } catch (\Exception $e) {
                return redirect()
                    ->route('recepciones.index')
                    ->with([
                        'message' => 'Ocurrió un error al crear el mantenimiento.',
                        'type' => 'error',
                    ]);
            }
    }

    /**aqui se realiza las asignaciones de los mantenimientos a los empleados*/
    public function asignacionMantenimiento(Request $r)
    {
        $usuarioLogueado = auth()->user();

        $mantenimientos = mantenimientos::with(['habitaciones', 'tipo_mantenimientos', 'creador'])
            ->whereIn('tipo_mantenimientos_id', function ($query) use ($usuarioLogueado) {
                $query
                    ->select('tipo_mantenimientos_id')
                    ->from('tipo_mantenimiento_users')
                    ->where('users_id', $usuarioLogueado->id);
            })
            ->whereNull('confirmacion_asignacion')
            ->orderBy('id', 'DESC')
            ->get();
        $users = User::whereIn('id', function ($u){
            $u->select('users_id')
            ->from('tipo_mantenimiento_users');
        })->orderBy('name','asc')->get();

        return view('mantenimientos.asignacion-mantenimiento', compact('mantenimientos', 'users'));
    }
    /**aqui se realizan las confirmaciones de los empleados cuando ya tienen asignados mantenimientos a realizar */
    public function confirmacionMantenimiento(Request $r)
    {
        $usuarioLogueado = auth()->user();
        $mantenimientos = mantenimientos::with(['habitaciones', 'tipo_mantenimientos', 'asignado'])
            ->whereNotNull('asignado_users_id')
            ->where('asignado_users_id', $usuarioLogueado->id)
            ->whereNull('finalizacion')
            ->orderBy('id', 'DESC')
            ->get();
        return view('mantenimientos.confirmacion-mantenimiento', compact('mantenimientos'));
    }
    /**historial de asignaciones de el usuario */
    public function historialMantenimiento(Request $r)
    {
        $usuarioLogueado = auth()->user();
        $mantenimientos = mantenimientos::with(['habitaciones', 'tipo_mantenimientos', 'asignado'])
            ->whereNotNull('asignado_users_id')
            ->whereNotNull('finalizacion')
            ->whereNotNull('confirmacion_asignacion')
            ->where('asignado_users_id', $usuarioLogueado->id)
            ->orderBy('finalizacion', 'DESC')
            ->paginate(35);


        return view('mantenimientos.historial-mantenimiento', compact('mantenimientos'));
    }
    /**historial de asignaciones de el usuario */
    public function mantenimientosPendientes(Request $r)
    {
        $usuarioLogueado = auth()->user();
        $mantenimientos = mantenimientos::with(['habitaciones', 'tipo_mantenimientos', 'asignado'])
            ->whereNotNull('asignado_users_id')
            ->whereNotNull('inicio')
            ->whereNotNull('confirmacion_asignacion')
            ->whereNull('finalizacion')
            ->where('asignado_users_id', $usuarioLogueado->id)
            ->whereNull('bitacora_asignado')
            ->orderBy('inicio', 'DESC')
            ->paginate(15);
        return view('mantenimientos.mantenimientos-pendientes', compact('mantenimientos'));
    }

    /**aqui se realizara las supervicion de los mantenimientos finalizados */
    public function supervisionMantenimiento(Request $r)
    {

        $usuarioLogueado = auth()->user();

        $mantenimientos = mantenimientos::with(['habitaciones', 'tipo_mantenimientos', 'supervisor', 'asignado'])
        ->whereIn('tipo_mantenimientos_id', function ($query) use ($usuarioLogueado) {
            $query
                ->select('tipo_mantenimientos_id')
                ->from('tipo_mantenimiento_users')
                ->where('users_id', $usuarioLogueado->id);
        })
            ->orderBy('id', 'DESC')
            ->get();;
        $usuarios = User::whereIn('id', function($u) {
                $u->select('users_id')
                ->from('tipo_mantenimiento_users');
        })->orderBy('name','asc')->get();

        $mantenimientosSupervisados = mantenimientos::with(['habitaciones', 'tipo_mantenimientos', 'supervisor', 'asignado'])
            ->whereIn('tipo_mantenimientos_id', function ($query) use ($usuarioLogueado) {
                $query
                    ->select('tipo_mantenimientos_id')
                    ->from('tipo_mantenimiento_users')
                    ->where('users_id', $usuarioLogueado->id);
            })
            ->where('supervisor_users_id', $usuarioLogueado->id)
            ->orderBy('id', 'DESC')
            ->get()
            ->unique('tipo_mantenimientos_id');


        return view('mantenimientos.supervision-mantenimientos', compact('mantenimientos',  'mantenimientosSupervisados','usuarios'));
    }

    public function asignarMantenimientos(Request $r) //se asigna el mantenimiento de una forma mas eficiente
    {
        $r->validate([
            'userId' => 'required|integer|exists:users,id',
            'mantenimientoId' => 'required|integer|exists:mantenimientos,id',
        ]);

        $supervisor = auth()->id();
        $userId = $r->input('userId');
        $mantenimientoId = $r->input('mantenimientoId');
        try {
            $mantenimiento = mantenimientos::with('tipo_mantenimientos', 'habitaciones')
                ->whereIn('tipo_mantenimientos_id', function ($query) use ($userId) {
                    $query
                        ->select('tipo_mantenimientos_id')
                        ->from('tipo_mantenimiento_users')
                        ->where('users_id', $userId);
                })
            ->findOrFail($mantenimientoId);

            if ($mantenimiento->asignado_users_id != null) {
                return response()->json(['success' => false, 'message' => 'El mantenimiento ya está asignado'], 400);
            }

            $mantenimiento->asignacion = now();
            $mantenimiento->estado = 'asignado';
            $mantenimiento->asignado_users_id = $userId;
            $mantenimiento->supervisor_users_id = $supervisor;
            $mantenimiento->save();
             $this->sendTelegramNotification($mantenimiento,$userId);
            return response()->json(['success' => true, 'message' => 'Mantenimiento asignado correctamente'], 200);
        } catch (\Exception $th) {
            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error al asignar el mantenimiento: ' . $th->getMessage()
            ], 500);

        }

    }
    private function sendTelegramNotification($mantenimiento, $user)
    {
        $grupoTelegram = $mantenimiento->tipo_mantenimientos->id_grupo_telegram;
        $notificacion = $mantenimiento->tipo_mantenimientos->notificacion;


        if (isset($grupoTelegram) && $grupoTelegram !== null && isset($notificacion) && $notificacion) {
            $userAsignado = User::find($user);
            $message = 'Hola, se le ha asignado la siguiente tarea debe confirmarla:';
            $message .= "\n \xE2\x9C\x94 *" . $mantenimiento->tipo_mantenimientos->mantenimiento . "*";
            $message .= ": en habitación #*" . $mantenimiento->habitaciones->numero_habitacion . "*";
            $message .= ", asignado a: *" . strtoupper($userAsignado->name) . "*";
            $message .= ", asignado por: *" . strtoupper(Auth::user()->name) . "*";

            Telegram::sendMessage([
                'parse_mode' => 'Markdown',
                'chat_id' => $grupoTelegram,
                'text' => $message,
            ]);
        }
    }


    public function confirmarMantenimientos(Request $r)
    {
        $r->validate( [
                'mantenimientoId' => 'required|integer',
            ]);
            $mantenimientoId = $r->input('mantenimientoId');
        try {
            $mantenimiento = mantenimientos::findOrFail($mantenimientoId);
            if ($mantenimiento->confirmacion_asignacion != null) {
                return response()->json(['success' => false, 'message' => 'El mantenimiento ya está esta confirmado'], 400);
            }
            $mantenimiento->confirmacion_asignacion = now();
            $mantenimiento->save();

            return response()->json(['success' => true, 'message' => 'Mantenimiento confirmado correctamente', 'confirmacion' => $mantenimiento->confirmacion_asignacion], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error al confirmar el mantenimiento'], 500);
        }
    }
    public function iniciarMantenimientos(Request $r)
    {
        $mantenimiento_id = $r->input('mantenimiento_id');
        $estado = $r->input('estado');
        $usuarioActual = Auth::user();
        $mantenimientoIniciado = $this->validarMantenimientoIniciado($usuarioActual);

        // Validar si ya ha completado o incompleto el mantenimiento actual
        if ($mantenimientoIniciado && $mantenimientoIniciado->id !== $usuarioActual->id) {
            return response()->json(['message' => 'Ya tienes un mantenimiento en curso. Completa o incompleta el actual antes de iniciar otro.', 'type' => 'danger'], 200);
        }
        try {
            $mantenimiento = mantenimientos::find($mantenimiento_id);

            if (!$mantenimiento) {
                return response()->json(['message' => 'Mantenimiento no encontrado', 'type' => 'danger'], 404);
            }

            $mantenimiento->estado = $estado;
            $mantenimiento->inicio = now();
            $mantenimiento->save();

            return response()->json(['message' => 'Se inició el mantenimiento con éxito', 'type' => 'success'], 200);
        } catch (\Exception $e) {
            // Maneja cualquier error que ocurra durante el proceso
            return response()->json(['message' => 'Error al iniciar el mantenimiento', 'error' => $e->getMessage()], 500);
        }
    }
    /**funcion para validar si ya hay un mantenimiento iniciado  */
    private function validarMantenimientoIniciado($usuario)
    {
        return mantenimientos::where('asignado_users_id', $usuario->id)
            ->where('estado', 'iniciado')
            ->first();
    }
    public function finalizarEstado(Request $r)
    {
        $mantenimiento_id = $r->input('mantenimiento_id');
        $estado = $r->input('estado');
        $bitacora_asignado = $r->input('bitacora_asignado');

        try {
            $mantenimiento = Mantenimientos::find($mantenimiento_id);

            if (!$mantenimiento) {
                return response()->json(['message' => 'Mantenimiento no encontrado'], 404);
            }
            // Actualizar el estado del mantenimiento
            $mantenimiento->estado = $estado;
            $mantenimiento->finalizacion = now();
            $mantenimiento->bitacora_asignado = $bitacora_asignado;
            $mantenimiento->save();
            // Obtener el tipo de mantenimiento asociado al mantenimiento
            $tipoMantenimiento = tipo_mantenimientos::find($mantenimiento->tipo_mantenimientos_id);

            if (!$tipoMantenimiento) {
                return response()->json(['message' => 'Tipo de mantenimiento no encontrado'], 404);
            }

            if ($tipoMantenimiento->estado_habitacion_completado_id !== null) {
                //Refactorizacion por ocupacion de habitaciones
                $this->cambiarEstadoHabitacion($mantenimiento->habitaciones_id, $tipoMantenimiento->estado_habitacion_completado_id);
            }

            return response()->json(['message' => 'Se finalizó el mantenimiento con éxito'], 200);
        } catch (\Exception $e) {
            // Maneja cualquier error que ocurra durante el proceso
            return response()->json(['message' => 'Error al finalizar el mantenimiento', 'error' => $e->getMessage()], 500);
        }
    }
    public function cambiarEstadoHabitacion($habitacion, $estado)
    {
        try {
            $estado = estado_habitaciones::find($estado);
            $habitacion = Habitaciones::find($habitacion);
            if ($habitacion->recepcion->count() > 0) {
                $estado = estado_habitaciones::where('token', env('estado_entrada', 1404))->first();
            }

            $habitacion->estado_habitaciones_id = $estado->id;
            return $habitacion->save();
        } catch (\Throwable $th) {
            return throw $th;
        }
    }
    /**esta funcion se usara cuando se incompleten los mantenimientos con su observacion obligatoria  */
    public function mantenimientoIncompletado(Request $r)
    {
        $validator = Validator::make($r->all(), [
            'estado' => 'required',
            'bitacora_asignado' => 'required',
            'mantenimiento_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()], 400);
        }
        $mantenimiento_id = $r->input('mantenimiento_id');
        $estado = $r->input('estado');
        $bitacora_asignado = $r->input('bitacora_asignado');

        try {
            $mantenimiento = Mantenimientos::find($mantenimiento_id);

            if (!$mantenimiento) {
                return response()->json(['message' => 'Mantenimiento no encontrado'], 404);
            }
            // Actualizar el estado del mantenimiento
            $mantenimiento->estado = $estado;
            $mantenimiento->finalizacion = now();
            $mantenimiento->bitacora_asignado = $bitacora_asignado;
            $mantenimiento->save();
            // Obtener el tipo de mantenimiento asociado al mantenimiento
            $tipoMantenimiento = tipo_mantenimientos::find($mantenimiento->tipo_mantenimientos_id);

            if (!$tipoMantenimiento) {
                return response()->json(['message' => 'Tipo de mantenimiento no encontrado'], 404);
            }

            if ($tipoMantenimiento->estado_habitacion_completado_id !== null) {
                // Obtener la habitación asociada al mantenimiento
                $habitacion = Habitaciones::find($mantenimiento->habitaciones_id);
                // Actualizar el estado de la habitación
                $habitacion->estado_habitaciones_id = $tipoMantenimiento->estado_habitacion_completado_id;
                $habitacion->save();
            }

            return response()->json(['message' => 'Se incompleto  el mantenimiento con éxito'], 200);
        } catch (\Exception $e) {
            // Maneja cualquier error que ocurra durante el proceso
            return response()->json(['message' => 'Error al incompletar el mantenimiento', 'error' => $e->getMessage()], 500);
        }
    }
    public function desasignarMantenimiento(Request $request)
    {
        try {
            $mantenimientoId = $request->input('mantenimientoId');
            $userId = $request->input('userId'); // Obtiene el ID del usuario desde la solicitud

            // Busca el mantenimiento por su ID
            $mantenimiento = mantenimientos::findOrFail($mantenimientoId);

            // Verifica si el mantenimiento está asignado a algún usuario
            if ($mantenimiento->asignado_users_id !== null) {
                // Verifica si el mantenimiento está asignado al usuario especificado
                if ($mantenimiento->asignado_users_id == $userId) {
                    // Realiza la desasignación estableciendo asignado_users_id a null
                    $mantenimiento->estado = 'sin asignar';
                    $mantenimiento->asignado_users_id = null;
                    $mantenimiento->asignacion = null;
                    $mantenimiento->save();

                    return response()->json(['success' => true, 'message' => 'Mantenimiento desasignado correctamente']);
                } else {
                    return response()->json(['success' => false, 'message' => 'El mantenimiento no está asignado a ese usuario']);
                }
            } else {
                return response()->json(['success' => false, 'message' => 'El mantenimiento no está asignado a ningún usuario']);
            }
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error al desasignar el mantenimiento']);
        }
    }

    public function desconfirmarMantenimientos(Request $request)
    {
        try {
            $mantenimientoId = $request->input('mantenimientoId');

            // Busca el mantenimiento por su ID
            $mantenimiento = mantenimientos::findOrFail($mantenimientoId);

            // Realiza la desconfirmación estableciendo confirmacion_asignacion a la fecha y hora actual
            $mantenimiento->estado = 'asignado';
            $mantenimiento->confirmacion_asignacion = null;
            $mantenimiento->save();

            return response()->json(['success' => true, 'message' => 'El mantenimiento se confirmó correctamente']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error al confirmar el mantenimiento']);
        }
    }
    public function obtenerMantenimientosByUser(Request $request)
    {
        $userId = $request->input('userId');

        // Aquí realiza la lógica para obtener los mantenimientos asignados al usuario con ID $userId

        $mantenimientosAsignados = mantenimientos::with('tipo_mantenimientos')
            ->where('asignado_users_id', '=', $userId)
            ->whereNull('finalizacion')
            ->get();

        return response()->json(['mantenimientosAsignados' => $mantenimientosAsignados]);
    }
    public function obtenerSupervisionByUser(Request $request)
    {
        $userId = auth()->user();

        // Aquí realiza la lógica para obtener los mantenimientos supervisados por usuario con ID $userId
        $mantenimientosSupervisados = mantenimientos::where('supervisor_users_id', '=', $userId)->get();

        return response()->json(['mantenimientosSupervisados' => $mantenimientosSupervisados]);
    }
    public function obtenerMantenimientosNoAsignados(Request $request)
    {
        // Consulta los mantenimientos que no tienen asignado_users_id (es decir, no asignados)
        $mantenimientosNoAsignados = mantenimientos::whereNull('asignado_users_id')->get();

        // Devuelve los mantenimientos no asignados en formato JSON
        return response()->json(['mantenimientosNoAsignados' => $mantenimientosNoAsignados]);
    }
    public function obtenerMantenimientosNoSupervisados(Request $request)
    {
        // Consulta los mantenimientos que no tienen supervisor_users_id (es decir, no supervisados)
        $mantenimientosNoSupervisados = mantenimientos::whereNull('supervisor_users_id')
            ->where('fecha', $request->input('fecha'))
            ->get();

        // Devuelve los mantenimientos no asignados en formato JSON
        return response()->json(['mantenimientosNoSupervisados' => $mantenimientosNoSupervisados]);
    }
    /**finalizar mantenimientos */
    public function finalizarMantenimientos(Request $request)
    {
        // Valida los datos recibidos desde el frontend
        $request->validate([
            'bitacora_asignado' => 'required',
            'mantenimiento_id' => 'required|exists:mantenimientos,id', // Asegura que el ID del mantenimiento exista en la tabla 'mantenimientos'
        ]);

        // Recupera los datos del formulario
        $bitacora_asignado = $request->input('bitacora_asignado');
        $mantenimiento_id = $request->input('mantenimiento_id');

        try {
            // Consulta el estado actual del mantenimiento en la base de datos
            $mantenimiento = mantenimientos::find($mantenimiento_id);

            // Verifica si el estado actual es igual a "finalizado"
            if ($mantenimiento->estado === 'finalizado') {
                return response()->json(['message' => 'El mantenimiento ya ha sido finalizado previamente'], 400);
            }

            // Actualiza el estado del mantenimiento en la base de datos
            $mantenimiento->estado = 'finalizado';
            $mantenimiento->bitacora_asignado = $bitacora_asignado;
            $mantenimiento->finalizacion = now();
            $mantenimiento->save();

            // Aquí puedes realizar otras acciones relacionadas con el mantenimiento si es necesario

            // Devuelve una respuesta de éxito al frontend
            return response()->json(['message' => 'Mantenimiento finalizado con éxito'], 200);
        } catch (\Exception $e) {
            // Maneja cualquier error que ocurra durante el proceso
            return response()->json(['message' => 'Error al finalizar el mantenimiento', 'error' => $e->getMessage()], 500);
        }
    }
    /**esta funcion eliminara los mantenimientos finalizados por si llega a suceder un detalle en al finalizar un mantenimiento */
    /**finalizar mantenimientos */
    public function eliminarFinalizarMantenimientos(Request $request)
    {
        // Valida los datos recibidos desde el frontend
        $request->validate([
            'mantenimiento_id' => 'required|exists:mantenimientos,id', // Asegura que el ID del mantenimiento exista en la tabla 'mantenimientos'
        ]);

        $mantenimiento_id = $request->input('mantenimiento_id');

        try {
            // Consulta el estado actual del mantenimiento en la base de datos
            $mantenimiento = mantenimientos::find($mantenimiento_id);

            // Actualiza el estado del mantenimiento en la base de datos
            $mantenimiento->estado = 'iniciado';
            $mantenimiento->bitacora_asignado = null;
            $mantenimiento->finalizacion = null;
            $mantenimiento->save();

            return response()->json(['message' => 'Se elemino el mantenimiento finalizado con éxito'], 200);
        } catch (\Exception $e) {
            // Maneja cualquier error que ocurra durante el proceso
            return response()->json(['message' => 'Error al eliminar el mantenimiento finalizado', 'error' => $e->getMessage()], 500);
        }
    }
    public function confirmarEstados(Request $request)
    {
        $mantenimiento_id = $request->input('mantenimiento_id');
        $estado = $request->input('estado');

        try {
            // Consulta el estado actual del mantenimiento en la base de datos
            $mantenimiento = mantenimientos::find($mantenimiento_id);
            $mantenimiento->confirmacion_asignacion = now();
            $mantenimiento->save();

            return response()->json(['message' => 'Se confirmo el mantenimiento con éxito'], 200);
        } catch (\Exception $e) {
            // Maneja cualquier error que ocurra durante el proceso
            return response()->json(['message' => 'Error al confirmar el mantenimiento', 'error' => $e->getMessage()], 500);
        }
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\mantenimientos  $mantenimientos
     * @return \Illuminate\Http\Response
     */

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatemantenimientosRequest  $request
     * @param  \App\Models\mantenimientos  $mantenimientos
     * @return \Illuminate\Http\Response
     */

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\mantenimientos  $mantenimientos
     * @return \Illuminate\Http\Response
     */

    public function impresionMantenimiento(Request $r)
    {
        return view('mantenimientos.container_mantenimiento', ['url' => route('mantenimientos.mantenimiento_print', ['id' => Crypt::encryptString($r->id)])]);
    }

    public function mantenimientos_reporte(Request $r)
    {
        if (isset($r->fecha_inicio) || isset($r->fecha_fin)) {
            $v = $r->validate([
                'fecha_inicio' => ['required', 'date'],
                'fecha_fin' => ['required', 'date'],
            ]);
            if (!$v) {
                return redirect()
                    ->back()
                    ->with('message', 'Las fechas no son validas')
                    ->with('type', 'danger');
            }
        }
        if (isset($r->accion)) {
            $accion = Crypt::decryptString($r->accion);

            if ($accion == 2 && isset($r->fecha_inicio) && isset($r->fecha_fin)) {
                return $this->getReporte($r->fecha_inicio, $r->fecha_fin);
            }
        }
        $fecha_inicio = $r->fecha_inicio ?? date('Y-m-d');
        $fecha_fin = $r->fecha_fin ?? date('Y-m-d');

        $usuarioLogueado = auth()->user();

        $mantenimientos = mantenimientos::with(['habitaciones', 'tipo_mantenimientos', 'supervisor', 'asignado'])
            ->whereIn('tipo_mantenimientos_id', function ($query) use ($usuarioLogueado) {
                $query
                    ->select('tipo_mantenimientos_id')
                    ->from('tipo_mantenimiento_users')
                    ->where('users_id', $usuarioLogueado->id);
            })
            ->whereBetween('fecha', [$fecha_inicio, $fecha_fin])
            ->orderBy('id', 'DESC')
            ->get();
        return view('mantenimientos.reporte_mantenimientos', compact('fecha_inicio', 'fecha_fin', 'mantenimientos'));
    }

    public function getReporte($fecha_inicio, $fecha_fin)
    {
        return view('mantenimientos.container_mantenimiento', ['url' => route('mantenimientos.mantenimientos_reporte_pdf', ['fecha_inicio' => Crypt::encryptString($fecha_inicio), 'fecha_fin' => Crypt::encryptString($fecha_fin)])]);
    }
    public function getReportePDF(Request $r)
    {
        $fecha_inicio = Crypt::decryptString($r->fecha_inicio);
        $fecha_fin = Crypt::decryptString($r->fecha_fin);
        $usuarioLogueado = auth()->user();

        $mantenimientos = mantenimientos::with(['habitaciones', 'tipo_mantenimientos', 'supervisor', 'asignado', 'creador'])
            ->whereIn('tipo_mantenimientos_id', function ($query) use ($usuarioLogueado) {
                $query
                    ->select('tipo_mantenimientos_id')
                    ->from('tipo_mantenimiento_users')
                    ->where('users_id', $usuarioLogueado->id);
            })
            ->whereBetween('fecha', [$fecha_inicio, $fecha_fin])
            ->orderBy('id', 'DESC')
            ->get();


        //me carga la vista de el reporte pdf
        return Pdf::loadView('mantenimientos.mantenimiento_print', compact('mantenimientos', 'fecha_inicio', 'fecha_fin'))
            ->setPaper('letter', 'landscape')
            ->stream();
    }
    /**function para asignacion de tareas de habitaciones */
    public function habitaciones(Request $r)
    {
        $this->revisarEstadoHabitaciones();
        $p = habitaciones::whereIn('estado_habitaciones_id', function ($q) {
            $q->from('estado_habitaciones')
                ->whereNotIn('token', [1401, 1404])
                ->select('id');
        })
            ->whereNotIn('id', function ($q) {
                $q->from('mantenimientos')
                    ->whereIn('estado', ['sin asignar', 'asignado', 'iniciado'])
                    ->select('habitaciones_id');
            })
            ->with(['mantenimientos', 'recepcion', 'relacionEstadoHabitaciones'])
            ->get();

        $tipo_mantenimientos = tipo_mantenimientos::whereIn('id', function ($q) {
            $q->select('tipo_mantenimientos_id')
                ->from('tipo_mantenimiento_users')
                ->where('users_id', Auth::user()->id);
        })->get();
        $usuarios = User::whereIn('id', function ($q) use ($tipo_mantenimientos) {
            $q->from('tipo_mantenimiento_users')
                ->whereIn('tipo_mantenimientos_id', $tipo_mantenimientos->pluck('id')->toArray())
                ->select('users_id');
        })->get();
        return view('mantenimientos.habitaciones', [
            'p' => $p,
            'tipo_mantenimientos' => $tipo_mantenimientos,
            'usuarios' => $usuarios,
        ]);
    }
    public function revisarEstadoHabitaciones()
    {
        $r = recepciones::where('estado', true)->get();
        foreach ($r as $h) {
            $dias = (new DateTime($h->fecha_ingreso))->diff(new DateTime(date('Y-m-d')))->days;

            if ($dias > 0) {
                $cm = mantenimientos::where('fecha', date('Y-m-d'))
                    ->where('habitaciones_id', $h->habitaciones_id)
                    ->count();
                if ($cm == 0) {
                    $habitacion = habitaciones::find($h->habitaciones_id);
                    if ($habitacion->relacionEstadoHabitaciones->token == 1404) {
                        $eh = estado_habitaciones::where('token', 1403)->first();
                        if (isset($eh->id)) {
                            $habitacion->estado_habitaciones_id = $eh->id;
                            $habitacion->save();
                        }
                    }
                }
            }
        }
    }

    public function habitacionesMantenimientos(Request $r)
    {
        if (count($r->habitaciones) == 0) {
            return redirect()
                ->back()
                ->with('message', 'Debe seleccionar una habitacion para asignar al empleado.')
                ->with('type', 'danger');
        }
        try {
            $user = User::find(Crypt::decryptString($r->users_id));
            $tm = tipo_mantenimientos::find(Crypt::decryptString($r->tipo_mantenimientos_id));
            $message = 'Hola *' . $user->name . '*, se le han asignado las siguientes tareas:';
            foreach ($r->habitaciones as $h) {
                $hab = habitaciones::find(Crypt::decryptString($h));
                $m = new mantenimientos();
                $m->fecha = date('Y-m-d');
                $m->asignacion = date('Y-m-d H:i:s');
                $m->asignado_users_id = $user->id;
                $m->creacion_users_id = Auth::user()->id;
                $m->supervisor_users_id = Auth::user()->id;
                $m->tipo_mantenimientos_id = $tm->id;
                $m->habitaciones_id = $hab->id;
                $m->estado = 'asignado';
                if ($m->save()) {
                    $message = $message . "\n \xE2\x9C\x94 *" . $tm->mantenimiento . '*: en habitación *' . $hab->numero_habitacion . '*, Creado: ' . $m->asignacion;
                }
            }

            if ($tm->notificacion && $tm->id_grupo_telegram != null) {
                Telegram::sendMessage([
                    'parse_mode' => 'Markdown',
                    'chat_id' => $tm->id_grupo_telegram, //Aqui se imprime campo $tipo_mantenimiento->id_grupo_telegram
                    'text' => $message,
                ]);
            }
            return redirect()
                ->back()
                ->with('message', 'Se asignaron los mantenimientos con exito.' . ($tm->notificacion && $tm->id_grupo_telegram != null ? ' Se envio el mensaje al grupo.' : ''));
        } catch (\Throwable $th) {
            return redirect()
                ->back()
                ->with('message', 'Ocurrio un error: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }
    /**funcion para crear mantenimientos desde el detalle desde habitacion resevada */
}
