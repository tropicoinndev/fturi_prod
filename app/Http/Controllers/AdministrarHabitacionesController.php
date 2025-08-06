<?php

namespace App\Http\Controllers;

use App\Http\Requests\Storeadministrar_habitacionesRequest;
use App\Http\Requests\Updateadministrar_habitacionesRequest;

use App\Models\estado_habitaciones;
use App\Models\forma_habitaciones;
use App\Models\tipo_habitaciones;
use App\Models\ubicacion_habitaciones;
use App\Models\habitaciones;
use App\Models\sucursales;
use App\Models\administrar_habitaciones;
use App\Models\habitacion_camas;
use App\Models\tipo_camas;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Throwable;

class AdministrarHabitacionesController extends Controller
{
    private $table = 'administrar_habitaciones';

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
        $habitaciones = habitaciones::orderBy('numero_habitacion', 'ASC')->get();
        $sucursales = sucursales::orderBy('id', 'ASC')->get();
        $tipoHabitaciones = tipo_habitaciones::orderBy('tipo_habitacion', 'ASC')->get();
        $formaHabitaciones = forma_habitaciones::orderBy('forma_habitacion', 'ASC')->get();
        $estadoHabitaciones = estado_habitaciones::orderBy('estado_habitacion', 'ASC')->get();
        $ubicacionHabitaciones = ubicacion_habitaciones::orderBy('ubicacion_habitacion', 'ASC')->get();

        return view($this->table . '.index', [
            'th' => $this->th['create'],
            'p' => $habitaciones,
            'data' => [
                'tipoHabitaciones' => $tipoHabitaciones,
                'formaHabitaciones' => $formaHabitaciones,
                'estadoHabitaciones' => $estadoHabitaciones,
                'ubicacionHabitaciones' => $ubicacionHabitaciones,
                'sucursales' => $sucursales,
            ],
        ]);
    }

    public function search(Request $request)
    {
        if (isset($request->txtBusqueda)) {
            $p = habitaciones::where('numero_habitacion', 'ilike', '%' . $request->txtBusqueda . '%')->paginate();

            return view($this->table . '.index', [
                'th' => $this->th['index'],
                'p' => $p,
                'txtBusqueda' => $request->txtBusqueda,
                'data' => [
                    'tipoHabitaciones' => tipo_habitaciones::orderBy('tipo_habitacion', 'ASC')->get(),
                    'formaHabitaciones' => forma_habitaciones::orderBy('forma_habitacion', 'ASC')->get(),
                    'estadoHabitaciones' => estado_habitaciones::orderBy('estado_habitacion', 'ASC')->get(),
                    'sucursales' => sucursales::orderBy('id', 'ASC')->get(),
                    'ubicacionHabitaciones' => ubicacion_habitaciones::orderBy('ubicacion_habitacion', 'ASC')->get(),
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
        $sucursales = sucursales::orderBy('id', 'ASC')->get();
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
                'sucursales' => $sucursales,
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Storeadministrar_habitacionesRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Storeadministrar_habitacionesRequest $r)
    {
        try {
            $p = new habitaciones();
            $p->numero_habitacion = $r->numero_habitacion;
            $p->tipo_habitaciones_id = $r->tipo_habitaciones_id;
            $p->forma_habitaciones_id = $r->forma_habitaciones_id;
            $p->estado_habitaciones_id = $r->estado_habitaciones_id;
            $p->ubicacion_habitaciones_id = $r->ubicacion_habitaciones_id;
            $p->sucursales_id = $r->sucursales_id;
            $p->telefono = $r->telefono;
            $p->extension = $r->extension;
            $p->descripcion = $r->descripcion;
            $p->save();

            return redirect()
                ->back()
                ->with('message', 'Registro guardado correctamente: ' . $p->numero_habitacion)
                ->with('type', 'success');
        } catch (\Throwable $th) {
            return redirect()
                ->back()
                ->with('message', 'Error al guardar el registro: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\administrar_habitaciones  $administrar_habitaciones
     * @return \Illuminate\Http\Response
     */
    public function show($id){
        $habitacion = habitaciones::with(['relacionTipoHabitaciones','relacionFormaHabitaciones'])
            ->find(Crypt::decryptString($id));

        $habitacionCamas = habitacion_camas::with(['habitaciones','tipo_camas'])
            ->where('habitaciones_id',$habitacion->id)
            ->get();

        return view($this->table.'.show',[
            'th'=>$this->th['show'] = [
                'title'=>'Habitación #'.$habitacion->numero_habitacion.' · '.$habitacion->relacionTipoHabitaciones->tipo_habitacion.' · '.$habitacion->relacionFormaHabitaciones->forma_habitacion,
                'sub'  =>'Detalles',
                /*'table'=>$this->table,
                'bread'=>$this->table.'.show',*/
            ],
            'habitacion'     =>$habitacion,
            'tipoCamas'      =>tipo_camas::orderBy('tipo_cama','asc')->get(),
            'habitacionCamas'=>$habitacionCamas,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\administrar_habitaciones  $administrar_habitaciones
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            return view($this->table . '.edit', [
                'th' => $this->th['edit'],
                'p' => habitaciones::findOrFail(Crypt::decryptString($id)),
                'data' => [
                    'tipoHabitaciones' => tipo_habitaciones::orderBy('tipo_habitacion', 'ASC')->get(),
                    'sucursales' => sucursales::orderBy('id', 'ASC')->get(),
                    'formaHabitaciones' => forma_habitaciones::orderBy('forma_habitacion', 'ASC')->get(),
                    'estadoHabitaciones' => estado_habitaciones::orderBy('estado_habitacion', 'ASC')->get(),
                    'ubicacionHabitaciones' => ubicacion_habitaciones::orderBy('ubicacion_habitacion', 'ASC')->get(),
                ],
            ]);
        } catch (\Throwable $th) {
            return to_route($this->table . '.index')
                ->with('message', 'Error al encontar el registro: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Updateadministrar_habitacionesRequest  $request
     * @param  \App\Models\administrar_habitaciones  $administrar_habitaciones
     * @return \Illuminate\Http\Response
     */
    public function update(Updateadministrar_habitacionesRequest $r)
    {
        try {
            $p = habitaciones::findOrFail($r->id);

            $p->numero_habitacion = $r->numero_habitacion;
            $p->tipo_habitaciones_id = $r->tipo_habitaciones_id;
            $p->forma_habitaciones_id = $r->forma_habitaciones_id;
            $p->estado_habitaciones_id = $r->estado_habitaciones_id;
            $p->ubicacion_habitaciones_id = $r->ubicacion_habitaciones_id;
            $p->sucursales_id = $r->sucursales_id;
            $p->telefono = $r->telefono;
            $p->extension = $r->extension;
            $p->descripcion = $r->descripcion;
            $p->save();

            return to_route($this->table . '.index')
                ->with('message', 'Registro actualizado correctamente: ' . $p->numero_habitacion)
                ->with('type', 'success');
        } catch (\Throwable $th) {
            return to_route($this->table . '.index')
                ->with('message', 'Error al actualizar el registro: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }

    public function confirm($id)
    {
        try {
            return view('confirm', [
                'th' => $this->th['confirm'],
                'p' => habitaciones::findOrFail(Crypt::decryptString($id)),
            ]);
        } catch (\Throwable $t) {
            return redirect()
                ->back()
                ->with('message', 'Ocurrio un error (' . $t->getMessage() . ')')
                ->with('type', 'danger');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\administrar_habitaciones  $administrar_habitaciones
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $r)
    {
        try {
            if (!isset($r->id) || empty(trim($r->id))) {
                return redirect()
                    ->route($this->table . '.index')
                    ->with('message', 'Ocurrio un error, el identificador del registro no cumple los requerimientos necesarios.')
                    ->with('type', 'danger');
            }

            #habitaciones::destroy(Crypt::decryptString($r->id));
            $p = habitaciones::findOrFail(Crypt::decryptString($r->id));
            $p->delete();

            return to_route($this->table . '.index')->with('message', 'Registro eliminado con exito: ' . $p->numero_habitacion);
        } catch (\Throwable $t) {
            return redirect()
                ->back()
                ->with('message', 'Ocurrio un error (' . $t->getMessage() . ')')
                ->with('type', 'danger');
        }
    }

    public function deleteCama($id){
        try{
            habitacion_camas::find(Crypt::decryptString($id))->delete();

            return redirect()->back()
                ->with('message','Cama eliminada correctamente.')
                ->with('type','success');
        }
        catch(Throwable $th){
            return redirect()->back()
                ->with('message','Ocurrio un error: '.$th->getMessage())
                ->with('type','danger');
        }
    }

    public function apiStoreCamaHabitacion(Request $r){
        try{
            #---Asignacion de datos POST a variables---
            $habitacionId = $r->habitacionId;
            $tipoCamaId   = $r->tipoCamaId;

            if($habitacionId <= 0 || $habitacionId === null)
                throw new Exception('No se encontró el parámetro: habitacion id.');
            if($tipoCamaId <= 0 || $tipoCamaId === null)
                throw new Exception('No se encontró el parámetro: tipo cama id.');
            #------------------------------------------

            #---Si se encuentra por lo menos un registro, se procede a eliminarlo, de lo contrario, se crea uno nuevo---
            $hc = habitacion_camas::where('habitaciones_id',$habitacionId)
                ->where('tipo_camas_id',$tipoCamaId);

            if($hc->count() >= 1){
                $hc->delete();
            }
            else{
                $p = new habitacion_camas();
                $p->habitaciones_id = $habitacionId;
                $p->tipo_camas_id = $tipoCamaId;
                $p->cantidad = 0;
                $p->save();
            }
            #------------------------------------------

            return response()->json([
                'status'=>true,
                'message'=>'Cama agregada a esta habitación.',
                'habitacionCamas'=>habitacion_camas::with(['habitaciones','tipo_camas'])
                    ->where('habitaciones_id',$habitacionId)
                    ->get(),
            ]);
        }
        catch(Throwable $th){
            return response()->json([
                'status'=>false,
                'message'=>'Error: '.$th->getMessage(),
            ]);
        }
    }
}
