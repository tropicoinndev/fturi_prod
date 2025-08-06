<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreturnosRequest;
use App\Http\Requests\UpdateturnosRequest;

use App\Models\cajas;
use App\Models\opcion_turnos;
use App\Models\turnos;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class TurnosController extends Controller
{
    private $table = 'turnos';

    public function __construct()
    {
        $this->getTh($this->table, 'Turnos');
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
            'p' => turnos::where('estado', true)->with('cajas', 'uapertura', 'ucierre', 'opcion', 'cajasSucursales')->orderBy('id', 'DESC')->paginate(15),
            'table' => $this->table,
            'data' => [
                'cajas' => cajas::orderBy('caja', 'ASC')->get(),
                'users' => User::orderBy('name', 'ASC')->get(),
                'opcion_turnos' => opcion_turnos::orderBy('turno', 'ASC')->get(),

            ],
        ]);
    }

    public function search(Request $r)
    {
        return view($this->table . '.index', [
            'th'         => $this->th['index'],
            'p'       => turnos::where('apertura', 'like', '%' . $r->txtBusqueda . '%')
                ->orWhere('cierre', 'like', '%' . $r->txtBusqueda . '%')
                ->orWhere('apertura_users_id', 'like', '%' . $r->txtBusqueda . '%')
                ->orWhere('cierre_users_id', 'like', '%' . $r->txtBusqueda . '%')
                ->orwhere('cajas_id', 'like', '%' . $r->txtBusqueda . '%')->paginate(),
            'txtBusqueda' => $r->txtBusqueda,
            'table' => $this->table,
            'data' => [
                'cajas' => cajas::orderBy('caja', 'ASC')->get(),
                'users' => User::orderBy('name', 'ASC')->get(),
                'opcion_turnos' => opcion_turnos::orderBy('turno', 'ASC')->get(),
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
            'table' => $this->table,
            'data' => [
                'cajas' => cajas::orderBy('caja', 'ASC')->get(),
                'users' => User::orderBy('name', 'ASC')->get(),
                'opcion_turnos' => opcion_turnos::orderBy('turno', 'ASC')->get(),
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreturnosRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreturnosRequest $request)
    {
        try {
            $data = new Turnos;
            $data->fecha = $request->fecha;
            $data->apertura = $request->apertura;
            $data->cierre = $request->cierre;
            $data->apertura_users_id = $request->users_id;
            $data->cierre_users_id = $request->users_id;
            $data->opcion_turnos_id = $request->opcion_turnos_id;
            $data->cajas_id = $request->cajas_id;
            $data->save();

            return to_route($this->table . '.index')
                ->with('message', 'Registro guardado correctamente: ' . $data->apertura)
                ->with('type', 'success');
        } catch (\Throwable $th) {
            return redirect()->route($this->table . '.index')
                ->with('message', 'Error al guardar el registro: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\turnos  $turnos
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            return view($this->table . '.show', [
                'th' => $this->th['show'],
                'p' => turnos::findOrFail(Crypt::decryptString($id)),
                'table' => $this->table,
                'data' => [
                    'cajas' => cajas::orderBy('caja', 'ASC')->get(),
                    'users' => User::orderBy('name', 'ASC')->get(),
                    'opcion_turnos' => opcion_turnos::orderBy('turno', 'ASC')->get(),
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
     * @param  \App\Models\turnos  $turnos
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            return view($this->table . '.edit', [
                'th' => $this->th['edit'],
                'p' => turnos::findOrFail(Crypt::decryptString($id)),
                'table' => $this->table,
                'data' => [
                    'cajas' => cajas::orderBy('caja', 'ASC')->get(),
                    'users' => User::orderBy('name', 'ASC')->get(),
                    'opcion_turnos' => opcion_turnos::orderBy('turno', 'ASC')->get(),
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
     * @param  \App\Http\Requests\UpdateturnosRequest  $request
     * @param  \App\Models\turnos  $turnos
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateturnosRequest $request)
    {

        try {
            $p = turnos::findOrFail($request->id);
            $p->fecha = $request->fecha;
            $p->apertura = $request->apertura;
            $p->cierre = $request->cierre;
            $p->apertura_users_id = $request->users_id;
            $p->cierre_users_id = $request->users_id;
            $p->cajas_id = $request->cajas_id;
            $p->opcion_turnos_id = $request->opcion_turnos_id;
            $p->save();

            return redirect()->route($this->table . '.index')
                ->with('message', 'Registro editado correctamente: ' . $p->apertura)
                ->with('type', 'info');
        } catch (\Throwable $th) {
            return redirect()->route($this->table . '.index')
                ->with('message', 'Error al editar el registro: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\turnos  $turnos
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $r)
    {

        try {
            if (!isset($r->id) || empty(trim($r->id)))
                return redirect()->route($this->table . '.index')
                    ->with('message', 'Ocurrio un error, el identificador del registro no cumple los requerimientos necesarios.')
                    ->with('type', 'danger');

            turnos::destroy(Crypt::decryptString($r->id));

            return redirect()->route($this->table . '.index')
                ->with('message', 'Registro eliminado con exito');
        } catch (\Throwable $t) {
            return redirect()->back()
                ->with('message', 'Ocurrio un error (' . $t->getMessage() . ')')
                ->with('type', 'danger');
        }
    }
    public function confirm($id)
    {
        try {
            return view("confirm", [
                'th' => $this->th['confirm'],
                'p' => turnos::findOrFail(Crypt::decryptString($id))
            ]);
        } catch (\Throwable $t) {
            return redirect()->back()
                ->with('message', 'Ocurrio un error (' . $t->getMessage() . ')')
                ->with('type', 'danger');
        }
    }
    public function status($id)
    {
        try {
            $p = turnos::findOrFail(Crypt::decryptString($id));
            $p->estado = !$p->estado;
            $p->save();

            return to_route($this->table . '.index')
                ->with('message', 'Estado modificado correctamente: ' . $p->apertura)
                ->with('type', 'success');
        } catch (\Throwable $th) {
            return redirect()->route($this->table . '.index')
                ->with('message', 'Error al cambiar estado: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }
}
