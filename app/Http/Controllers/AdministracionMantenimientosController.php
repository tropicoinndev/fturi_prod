<?php

namespace App\Http\Controllers;

use App\Http\Requests\Storeadministracion_mantenimientosRequest;
use App\Http\Requests\Updateadministracion_mantenimientosRequest;
use App\Models\administracion_mantenimientos;
use App\Models\mantenimientos;
use App\Models\tipo_mantenimientos;
use App\Models\habitaciones;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;

use Throwable;

class AdministracionMantenimientosController extends Controller
{
    private $table = 'administracion_mantenimientos';

    public function __construct()
    {
        $this->getTh($this->table,'Mantenimientos');
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
            'p' => mantenimientos::with('asignado', 'supervisor', 'tipo_mantenimientos', 'habitaciones', 'creador')->orderBy('id', 'DESC')->paginate(15),
            'table' => $this->table,
            'data' => [
                'tipo_mantenimientos' => tipo_mantenimientos::orderBy('id', 'DESC')->get(),
                'habitaciones' => habitaciones::orderBy('id', 'DESC')->get(),
                'users' => User::orderBy('id', 'DESC')->get(),
            ],
        ]);
    }

    public function search(Request $request)
    {
        if(isset($request->txtBusqueda)){
            $p =mantenimientos::where('observacion','like','%'.$request->txtBusqueda.'%')->paginate();
            
            return view($this->table.'.index',[
                'th'=>$this->th['index'],
                'p' =>$p,
                'txtBusqueda'=>$request->txtBusqueda,
                'data'=>[
                    'habitaciones'     =>habitaciones::orderBy('id','ASC')->get(),
                    'tipo_mantenimientos'    =>tipo_mantenimientos::orderBy('id','ASC')->get(),
                    'users'   =>User::orderBy('id','ASC')->get(),
                    
                ]
            ]);
        }
        else{
            return to_route($this->table.'.index');
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
                'users' => User::orderBy('id', 'DESC')->get(),
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Storeadministracion_mantenimientosRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Storeadministracion_mantenimientosRequest $r)
    {
        try {
                // Validación para evitar duplicados
            $mantenimientoExistente = mantenimientos::where('tipo_mantenimientos_id', $r->tipo_mantenimientos_id)
                ->where('habitaciones_id', $r->habitaciones_id)
                ->whereNull('finalizacion')
                ->exists();
            if ($mantenimientoExistente ) {
                return redirect()
                    ->route($this->table . '.index')
                    ->with('message', 'Ya existe un mantenimiento para esta habitación con este tipo de mantenimiento pero no se ha completado hasta que se complete se podra crear otro.')
                    ->with('type', 'danger');
            }
            $p = new mantenimientos();
            $p->fecha = date('Y-m-d h:i:s');
            $p->asignacion = isset($r->asignacion) ? $r->asignacion :null; //pendiente hacer modificaciones cambiara la forma en la que se guardara el mantenimiento
            $p->finalizacion = isset($r->finalizacion) ? $r->finalizacion : null;
            $p->estado = isset($r->estado) ? $r->estado : 'sin asignar';
            $p->asignado_users_id =isset($r->asignado_users_id) ? $r->asiganado_users_id : null;
            $p->supervisor_users_id = isset($r->supervisor_users_id) ? $r->supervisor_users_id : null;
            $p->creacion_users_id = Auth::user()->id;
            $p->confirmacion_asignacion =isset($r->confirmacion_asignacion) ? $r->confirmacion_asignacion :null ;
            $p->tipo_mantenimientos_id = $r->tipo_mantenimientos_id;
            $p->habitaciones_id = $r->habitaciones_id;
            $p->observacion = isset($r->observacion) ? $r->observacion :null;
            $p->bitacora_asignado = isset($r->bitacora_asignado) ? $r->bitacora_asignado : null;
            $p->save();
            return to_route($this->table.'.index')
                ->with('message','Registro guardado correctamente: '.$p->observacion)
                ->with('type','success');
        } catch (\Throwable $th) {
            return redirect()->route($this->table.'.index')
                ->with('message','Error al guardar el registro: '.$th->getMessage())
                ->with('type','danger');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\administracion_mantenimientos  $administracion_mantenimientos
     * @return \Illuminate\Http\Response
     */
    public function show(administracion_mantenimientos $administracion_mantenimientos)
    {
        //
    }
        /**asignacion de mantenimientos */
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\administracion_mantenimientos  $administracion_mantenimientos
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            return view($this->table . '.edit', [
                'th' => $this->th['edit'],
                'p' => mantenimientos::with('tipo_mantenimientos', 'habitaciones', 'users')->findOrFail(Crypt::decryptString($id)),
                'table' => $this->table,
                'data' => [
                    'tipo_mantenimientos' => tipo_mantenimientos::orderBy('id', 'DESC')->get(),
                    'habitaciones' => habitaciones::orderBy('id', 'DESC')->get(),
                    'users' => User::orderBy('id', 'DESC')->get(),
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
     * @param  \App\Http\Requests\Updateadministracion_mantenimientosRequest  $request
     * @param  \App\Models\administracion_mantenimientos  $administracion_mantenimientos
     * @return \Illuminate\Http\Response
     */
    public function update(Updateadministracion_mantenimientosRequest $r)
    {
            try {
            $p = mantenimientos::findOrFail($r->id);
            $p->fecha = date('Y-m-d h:i:s');
            $p->asignacion = isset($r->asignacion) ? $r->asignacion :null;
            $p->finalizacion = isset($r->finalizacion) ? $r->finalizacion : null;
            $p->estado = isset($r->estado) ? $r->estado : 'sin asignar';
            $p->asignado_users_id =isset($r->asignado_users_id) ? $r->asiganado_users_id : null;
            $p->supervisor_users_id = isset($r->supervisor_users_id) ? $r->supervisor_users_id : null;
            $p->confirmacion_asignacion =isset($r->confirmacion_asignacion) ? $r->confirmacion_asignacion :null ;
            $p->tipo_mantenimientos_id = $r->tipo_mantenimientos_id;
            $p->habitaciones_id = $r->habitaciones_id;
            $p->observacion = isset($r->observacion) ? $r->observacion :null;
            $p->bitacora_asignado = isset($r->bitacora_asignado) ? $r->bitacora_asignado : null;
            $p->save();
            return to_route($this->table.'.index')
                ->with('message','Registro guardado correctamente: '.$p->observacion)
                ->with('type','success');
        } catch (\Throwable $th) {
            return to_route($this->table.'.index')
                ->with('message','Error al actualizar el registro: '.$th->getMessage())
                ->with('type','danger');
        }
    }
        public function confirm($id)
    {
        try {
            return view('confirm', [
                'th' => $this->th['confirm'],
                'p' => mantenimientos::findOrFail(Crypt::decryptString($id)),
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
     * @param  \App\Models\administracion_mantenimientos  $administracion_mantenimientos
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

            $p = mantenimientos::findOrFail(Crypt::decryptString($r->id));
            $p->delete();

            return to_route($this->table . '.index')->with('message', 'Registro eliminado con exito: ');
        } catch (\Throwable $t) {
            return redirect()
                ->back()
                ->with('message', 'Ocurrio un error (' . $t->getMessage() . ')')
                ->with('type', 'danger');
        }
    }
}
