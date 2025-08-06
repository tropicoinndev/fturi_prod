<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\Encrypter;
use App\Http\Requests\Storetipo_mantenimientosRequest;
use App\Http\Requests\Updatetipo_mantenimientosRequest;
use App\Models\tipo_mantenimientos;
use App\Models\tipo_mantenimiento_users;
use App\Models\estado_habitaciones;
use App\Models\User;

class TipoMantenimientosController extends Controller
{
    private $table = 'tipo_mantenimientos';

    public function __construct()
    {
        $this->getTh($this->table, 'Tipo mantenimientos');
    }
    /**listar tipo de mantenimientos */
    public function index_api()
    {
        return response()->json(['listm' => $this->getList()]);
    }

    private function getList()
    {
        return tipo_mantenimientos::orderBy('mantenimiento', 'ASC')->get();
    }
    /**funciones para gragar usuarios a tipo de mantenimientos */
    /***funcione para gregar usuarios a tipo de mantenimientos */
    /***api listar usuarios disponibles */
    public function list_usuarios(Request $r)
    {
        $mantenimiento = Crypt::decryptString($r->mantenimiento);
        return response()->json(['users' => $this->getUsuarioMantenimiento($mantenimiento)]);
    }
    /**funcion que trae Usuario para mantenimiento */
    private function getUsuarioMantenimiento($mantenimiento)
    {
        return tipo_mantenimiento_users::where('tipo_mantenimientos_id', '=', $mantenimiento)
            ->with('users')
            ->get();
    }
    /** buscar usuarios*/
    public function apiSearchUsuario(Request $r)
    {
        return response()->json([
            'list' => $this->getUsuarioMantenimiento(Crypt::decryptString($r->id)),
        ]);
    }
    /**esta funcion es donde se agrega el tipo de mantenimiento a usuario  */
    public function store_apiUsuariosMantenimientos(Request $r)
    {
        $messege = '';
        $type = true;
        try {
            $mantenimiento = Crypt::decryptString($r->mantenimiento);
            $type = $this->setUsuarioTipoMantenimientos($mantenimiento, $r->user);
            $messege = $type ? 'Usuario agregado a este tipo de mantenimiento' : 'Error al guardar';
        } catch (\Throwable $th) {
            $messege = 'Error: accion no realizada, actualice e intente de nuevo';
            $type = false;
        }
        return response()->json([
            'message' => $messege,
            'type' => $type ? 'success' : 'danger',
            'users' => $this->getUsuarioMantenimiento($mantenimiento),
        ]);
    }
    /**esta funcion guarda usario en el tipo de mantenimiento */
    private function setUsuarioTipoMantenimientos($mantenimiento, $user)
    {
        try {
            $inM = tipo_mantenimiento_users::where('tipo_mantenimientos_id', $mantenimiento)
                ->where('users_id', $user)
                ->count();

            if ($inM >= 1) {
                tipo_mantenimiento_users::where('tipo_mantenimientos_id', $mantenimiento)
                    ->where('users_id', $user)
                    ->delete();
            } else {
                $p = new tipo_mantenimiento_users();
                $p->users_id = $user;
                $p->tipo_mantenimientos_id = $mantenimiento;

                $p->save();
            }
            return true;
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }
    /**aqui finalizan las funciones de agregar usuarios a tipos de mantenimientos */
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view($this->table . '.index', [
            'th' => $this->th['index'],
            'p' => tipo_mantenimientos::with(['inicio_habitaciones', 'completado_habitaciones'])->orderBy('id', 'DESC')->paginate(15),
            'data' => [
                'estado_habitaciones' => estado_habitaciones::orderBy('id', 'DESC')->get(),

            ],
        ]);
    }
    public function search(Request $r)
    {
        return view($this->table . '.index', [
            'th' => $this->th['index'],
            'p' => tipo_mantenimientos::where('mantenimiento', 'ilike', '%' . $r->txtBusqueda . '%')->paginate(),
            'txtBusqueda' => $r->txtBusqueda,
            'table' => $this->table,
            'data' => [
                'estado_habitaciones' => estado_habitaciones::orderBy('id', 'DESC')->get(),
            ],
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
            'th' => $this->th['create'],
            'p' => tipo_mantenimientos::orderBy('id', 'DESC')->paginate(15),
            'data' => [
                'estado_habitaciones' => estado_habitaciones::orderBy('id', 'DESC')->get(),
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Storetipo_mantenimientosRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Storetipo_mantenimientosRequest $request)
    {
        try {
            $data = new tipo_mantenimientos();
            $data->mantenimiento = $request->mantenimiento;
            $data->duracion_promedio = $request->duracion_promedio;
            $data->estado_habitacion_inicio_id = $request->estado_habitacion_inicio_id ;
            $data->estado_habitacion_completado_id = $request->estado_habitacion_completado_id ;
            $data->id_grupo_telegram = $request->id_grupo_telegram;
            $data->notificacion = $request->notificacion;
            $data->save();

            return to_route($this->table . '.index')
                ->with('message', 'Registro guardado correctamente: ' . $data->mantenimiento)
                ->with('type', 'success');
        } catch (\Throwable $th) {
            return redirect()
                ->route($this->table . '.index')
                ->with('message', 'Error al guardar el registro: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\tipo_mantenimientos  $tipo_mantenimientos
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $mantenimiento = tipo_mantenimientos::findOrFail(Crypt::decryptString($id));
        return view($this->table . '.show', [
            'th' => ($this->th['show'] = [
                'title' => $mantenimiento->mantenimiento,
                'table' => $this->table,
                'bread' => $this->table . '.show',
            ]),
            'p' => $mantenimiento,
            'table' => $this->table,
            'tipoMantenimientoU' => tipo_mantenimiento_users::with('users', 'tipo_mantenimientos')
                ->orderBy('id', 'ASC')
                ->where('tipo_mantenimientos_id', '=', $mantenimiento->id)
                ->get(),
            'usuarios' => User::where('id', $mantenimiento->id)->get(),
            'tipomantenimientos' => tipo_mantenimientos::where('id', $mantenimiento->id)->get(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\tipo_mantenimientos  $tipo_mantenimientos
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            return view($this->table . '.edit', [
                'th' => $this->th['edit'],
                'p' => tipo_mantenimientos::with(['inicio_habitaciones', 'completado_habitaciones'])->findOrFail(Crypt::decryptString($id)),
                'table' => $this->table,
                'data' => [
                    'estado_habitaciones' => estado_habitaciones::orderBy('id', 'ASC')->get(),
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
     * @param  \App\Http\Requests\Updatetipo_mantenimientosRequest  $request
     * @param  \App\Models\tipo_mantenimientos  $tipo_mantenimientos
     * @return \Illuminate\Http\Response
     */
    public function update(Updatetipo_mantenimientosRequest $request)
    {
        try {
            $p = tipo_mantenimientos::findOrFail($request->id);
            $p->mantenimiento = $request->mantenimiento;
            $p->duracion_promedio = $request->duracion_promedio;
            $p->estado_habitacion_inicio_id = $request->estado_habitacion_inicio_id ;
            $p->estado_habitacion_completado_id = $request->estado_habitacion_completado_id ;
            $p->id_grupo_telegram = $request->id_grupo_telegram;
            $p->notificacion = $request->notificacion;
            $p->save();

            return to_route($this->table . '.index')
                ->with('message', 'Registro guardado correctamente: ' . $p->mantenimiento)
                ->with('type', 'success');
        } catch (\Throwable $th) {
            return redirect()
                ->route($this->table . '.index')
                ->with('message', 'Error al guardar el registro: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }
    public function confirm($id)
    {
        try {
            return view('confirm', [
                'th' => $this->th['confirm'],
                'p' => tipo_mantenimientos::findOrFail(Crypt::decryptString($id)),
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
     * @param  \App\Models\tipo_mantenimientos  $tipo_mantenimientos
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

            $p = tipo_mantenimientos::findOrFail(Crypt::decryptString($r->id));
            $p->delete();

            return to_route($this->table . '.index')->with('message', 'Registro eliminado con exito: ' . $p->mantenimiento);
        } catch (\Throwable $t) {
            return redirect()
                ->back()
                ->with('message', 'Ocurrio un error (' . $t->getMessage() . ')')
                ->with('type', 'danger');
        }
    }
    public function status($id)
    {
        try {
            $p = tipo_mantenimientos::findOrFail(Crypt::decryptString($id));
            $p->estado = !$p->estado;
            $p->save();

            return redirect()
                ->back()
                ->with('message', 'Se ha modificado el estado ' . $p->mantenimiento)
                ->with('type', 'success');
        } catch (\Throwable $th) {
            return redirect()
                ->route($this->table . '.index')
                ->with('message', 'Error al cambiar estado: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }
    public function destroy_api(Request $r)
    {
        $m = 'Se elimino una tipo de mantenimiento';
        $t = true;
        try {
            tipo_mantenimientos::destroy($r->id);
        } catch (\Throwable $th) {
            $t = false;
            $m = 'Error: ' . $th->getMessage();
        }
        return response()->json([
            'listm' => $this->getList(),
            'message' => $m,
            'type' => $t ? 'success' : 'danger',
        ]);
    }
}
