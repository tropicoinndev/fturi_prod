<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Http\Requests\StorehabitacionesRequest;
use App\Http\Requests\UpdatehabitacionesRequest;
use App\Models\detalle_reservas;
use App\Models\estado_habitaciones;
use App\Models\forma_habitaciones;
use App\Models\habitaciones;
use App\Models\recepciones;
use App\Models\tipo_habitaciones;
use App\Models\ubicacion_habitaciones;
use DateTime;
use Illuminate\Console\Scheduling\CacheAware;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\DatabaseManager;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;
use Throwable;

class HabitacionesController extends Controller
{
    private $table = 'habitaciones';

    public function __construct()
    {
        $this->getTh($this->table, 'Habitaciones');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        #Como los datos a mostrar para esta vista son muchos, y estan sobrecargadas de relaciones con otras tablas,
        #Y tambien su contenido no cambia con frecuencia, se almacenara en cache por una hora para que la carga sea mas rapida.

        #Verifica si hay datos previamente guardados en cache.
        $habitaciones          = Cache::get('habitaciones_data');
        $tipoHabitaciones      = Cache::get('tipo_hab_data');
        $formaHabitaciones     = Cache::get('forma_hab_data');
        $estadoHabitaciones    = Cache::get('estado_hab_data');
        $ubicacionHabitaciones = Cache::get('ubicacion_hab_data');

        #Si no encuentra datos en cache, realiza la consulta la BD y los pone en cache.
        if (!$habitaciones) {
            $habitaciones = habitaciones::with([
                'relacionTipoHabitaciones',
                'relacionFormaHabitaciones',
                'relacionEstadoHabitaciones',
                'relacionUbicacionHabitaciones'
            ])->orderBy('numero_habitacion', 'ASC')->get();

            Cache::put('habitaciones_data', $habitaciones, now()->addHour());
        }
        if (!$tipoHabitaciones) {
            $tipoHabitaciones = tipo_habitaciones::orderBy('tipo_habitacion', 'ASC')->get();
            Cache::put('tipo_hab_data', $tipoHabitaciones, now()->addHour());
        }
        if (!$formaHabitaciones) {
            $formaHabitaciones = forma_habitaciones::orderBy('forma_habitacion', 'ASC')->get();
            Cache::put('forma_hab_data', $formaHabitaciones, now()->addHour());
        }
        if (!$estadoHabitaciones) {
            $estadoHabitaciones = estado_habitaciones::orderBy('estado_habitacion', 'ASC')->get();
            Cache::put('estado_hab_data', $estadoHabitaciones, now()->addHour());
        }
        if (!$ubicacionHabitaciones) {
            $ubicacionHabitaciones = ubicacion_habitaciones::orderBy('ubicacion_habitacion', 'ASC')->get();
            Cache::put('ubicacion_hab_data', $ubicacionHabitaciones, now()->addHour());
        }

        return view($this->table . '.index', [
            'p' => $habitaciones,
            $tipoHabitaciones,
            $formaHabitaciones,
            $estadoHabitaciones,
            $ubicacionHabitaciones
        ]);
    }

    public function getDetalleTarifaByHabitacion(Request $r)
    {
        return habitaciones::where('tarifas_id', $r->id)->first();
    }

    public function getHabDisponibles(Request $r)
    {
        $disponibilidad = [];

        $fechaEntrada = $r->fecha_entrada;
        $fechaSalida  = $r->fecha_salida;

        $disponibilidad = $this->getDataHabitacionesDisponibles($fechaEntrada, $fechaSalida);

        return response([
            'habitaciones' => $disponibilidad,
        ]);
    }
    public function getSalidaValid(Request $r)
    {
        try {
            return response([
                'valid' => $this->isValidSalida(Crypt::decryptString($r->id), $r->fecha_salida)
            ]);
        } catch (\Throwable $th) {
            return response([
                'valid' => false,
                'message' => $th->getMessage()
            ]);
        }
    }
    public function isValidSalida($recepcion_id, $fecha_salida)
    {
        $recepcion = recepciones::find($recepcion_id);
        $fechaSalida  = $fecha_salida;
        $fm = (new DateTime($fechaSalida))->modify('-1 day')->format('Y-m-d');

        return DB::table('getreservacionesvalidas')
            ->where(function ($q) use ($recepcion, $fechaSalida, $fm) {
                $q->whereRaw('(? BETWEEN fecha_ingreso and fecha_salida - 1) or (? BETWEEN fecha_ingreso and fecha_salida - 1)', ['ingreso' => $recepcion->fecha_ingreso, 'salida' => $fm])
                    ->orWhereBetween('fecha_ingreso', [$recepcion->fecha_ingreso, $fm])
                    ->orWhereBetween('fecha_salida', [$recepcion->fecha_ingreso, $fechaSalida]);
            })
            ->where('habitaciones_id', $recepcion->habitaciones_id)->count() == 0;
    }

    public function getDataHabitacionesDisponibles($fechaEntrada, $fechaSalida)
    {
        $fm = (new DateTime($fechaSalida))->modify('-1 day')->format('Y-m-d');
        $habitaciones = habitaciones::with(
            [
                'relacionTipoHabitaciones',
                'relacionFormaHabitaciones',
                'relacionEstadoHabitaciones',
                'habitacionCamas',
                'getTarifas' => function ($q) use ($fechaEntrada, $fechaSalida) {
                    return $q->where('fecha_inicio', "<=", $fechaEntrada)->where('fecha_finalizacion', '>=', $fechaSalida);
                }
            ]
        )
            ->whereNotIn('id', function ($q) use ($fechaEntrada, $fechaSalida, $fm) {
                $q->from('detalle_reservas as dr')
                    ->join('reservaciones as r', 'r.id', '=', 'dr.reservaciones_id')
                    ->select('dr.habitaciones_id')
                    ->where(function ($q) use ($fechaEntrada, $fm,  $fechaSalida) {
                        $q->orWhereRaw('? BETWEEN dr.fecha_ingreso and dr.fecha_salida - 1', [$fechaEntrada])
                            ->orWhereRaw('? BETWEEN dr.fecha_ingreso and dr.fecha_salida - 1', [$fm])
                            ->orWhereBetween('dr.fecha_ingreso', [$fechaEntrada, $fm])
                            ->orWhereBetween(DB::raw('dr.fecha_salida -1'), [$fechaEntrada, $fm]);
                    })
                    ->where('dr.estado', true)
                    ->where('r.eliminado', false)
                    ->get();
            })
            ->whereNotIn('id', function ($q) use ($fechaEntrada, $fechaSalida) {
                $q->from('recepciones as rp')
                    ->select('rp.habitaciones_id')
                    ->whereRaw('((? BETWEEN rp.fecha_ingreso and rp.fecha_salida -1) or (? BETWEEN rp.fecha_ingreso and rp.fecha_salida - 1))', ['fechaEntrada' => $fechaEntrada, 'fechaSalida' => $fechaSalida])
                    ->where('rp.eliminado', false)
                    ->where('rp.estado', true)
                    ->get();
            })
            ->where('estado_habitaciones_id', '!=', 6);

        if (session('sucursal') && session('sucursal')->id)
            $habitaciones = $habitaciones->where('sucursales_id', session('sucursal')->id);

        $habitaciones = $habitaciones->get();
        return $habitaciones;
    }

    public function isValidHabitacion($fechaEntrada, $fechaSalida, $habitaciones_id)
    {


        $fm = (new DateTime($fechaSalida))->modify('-1 day')->format('Y-m-d');
        $count = DB::table('getreservacionesvalidas')
            ->where(function ($q) use ($fechaEntrada, $fechaSalida, $fm) {
                $q->whereRaw(
                    '(? BETWEEN fecha_ingreso and fecha_salida - 1) or (? BETWEEN fecha_ingreso and fecha_salida - 1)',
                    [
                        'ingreso' => $fechaEntrada,
                        'salida' => $fm
                    ]
                )
                    ->orWhereBetween('fecha_ingreso', [$fechaEntrada, $fm])
                    ->orWhereBetween(DB::raw('fecha_salida -1'), [$fechaEntrada, $fm]);
            })
            ->where('habitaciones_id', $habitaciones_id)->count();
        return $count == null || $count  == 0;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $habitaciones = habitaciones::orderBy('numero_habitacion', 'ASC')->get();
        $tipoHabitaciones = tipo_habitaciones::orderBy('tipo_habitacion', 'ASC')->get();
        $formaHabitaciones = forma_habitaciones::orderBy('forma_habitacion', 'ASC')->get();
        $estadoHabitaciones = estado_habitaciones::orderBy('estado_habitacion', 'ASC')->get();
        $ubicacionHabitaciones = ubicacion_habitaciones::orderBy('ubicacion_habitacion', 'ASC')->get();

        return view($this->table . '.create', [
            'th' => $this->th['create'],
            'p' => $habitaciones,
            'data' => [
                'tipoHabitaciones' => $tipoHabitaciones,
                'formaHabitaciones' => $formaHabitaciones,
                'estadoHabitaciones' => $estadoHabitaciones,
                'ubicacionHabitaciones' => $ubicacionHabitaciones,
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorehabitacionesRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorehabitacionesRequest $r)
    {
        try {
            $p = new habitaciones;
            $p->numero_habitacion         = $r->numero_habitacion;
            $p->tipo_habitaciones_id      = $r->tipo_habitaciones_id;
            $p->forma_habitaciones_id     = $r->forma_habitaciones_id;
            $p->estado_habitaciones_id    = $r->estado_habitaciones_id;
            $p->ubicacion_habitaciones_id = $r->ubicacion_habitaciones_id;
            $p->telefono                  = $r->telefono;
            $p->extension                 = $r->extension;
            $p->descripcion               = $r->descripcion;
            $p->save();

            return response([
                'msj' => 'Registro guardado correctamente.',
                'type' => 'success'
            ]);
        } catch (Throwable $th) {
            return response([
                'msj' => 'Error al guardar el registro: ' . $th->getMessage(),
                'type' => 'danger'
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\habitaciones  $habitaciones
     * @return \Illuminate\Http\Response
     */
    public function show(habitaciones $habitaciones)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\habitaciones  $habitaciones
     * @return \Illuminate\Http\Response
     */
    public function edit(habitaciones $habitaciones)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatehabitacionesRequest  $request
     * @param  \App\Models\habitaciones  $habitaciones
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatehabitacionesRequest $request, habitaciones $habitaciones)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\habitaciones  $habitaciones
     * @return \Illuminate\Http\Response
     */
    public function destroy(habitaciones $habitaciones)
    {
        //
    }

    public function glorieta(Request $r)
    {
        try {
            $p = habitaciones::find(Crypt::decryptString($r->id));
            $p->glorieta = !$p->glorieta;
            $p->save();
            return redirect()->back();
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
