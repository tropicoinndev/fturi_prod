<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreempleadosRequest;
use App\Http\Requests\UpdateempleadosRequest;
use App\Models\empleados;
use App\Models\identificaciones;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;


class EmpleadosController extends Controller
{
    private $table = 'empleados';

    public function __construct()
    {
        $this->getTh($this->table, 'Empleados');
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
            'p' => empleados::orderBy('nombre_completo', 'ASC')->paginate(10),
            'data' => [
                'identificaciones' => identificaciones::where('estado', true)->orderBy('id', 'DESC')->get(),
                'usuarios' => User::orderBy('id', 'DESC')->get(),
            ],
        ]);
    }
    public function search(Request $r)
    {
        return view($this->table . '.index', [
            'th'         => $this->th['index'],
            'p'       => empleados::where('nombre_completo', 'ilike', '%' . $r->txtBusqueda . '%')->paginate(),
            'txtBusqueda' => $r->txtBusqueda,
            'table' => $this->table,
            'data' => [
                'identificaciones' => identificaciones::where('estado', true)->orderBy('id', 'DESC')->get(),
                'usuarios' => User::orderBy('id', 'DESC')->get(),
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
                'identificaciones' => identificaciones::where('estado', true)->orderBy('id', 'DESC')->get(),
                'usuarios' => User::orderBy('id', 'DESC')->get(),
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreempleadosRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreempleadosRequest $r)
    {
        try {
            $e = new empleados;
            $e->nombre_completo = $r->nombre_completo;
            $e->identificaciones_id = $r->identificaciones_id;
            $e->numero_documento = $r->numero_documento;
            $e->users_id = $r->users_id ?? null;
            $e->save();

            return to_route($this->table . '.index')
                ->with('message', 'Registro guardado correctamente: ' . $e->nombre_completo)
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
     * @param  \App\Models\empleados  $empleados
     * @return \Illuminate\Http\Response
     */
    public function show(empleados $empleados)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\empleados  $empleados
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            return view($this->table . ".edit", [
                'th' => $this->th['edit'],
                'p' => empleados::findOrFail(Crypt::decryptString($id)),
                'table' => $this->table,
                'data' => [
                    'identificaciones' => identificaciones::where('estado',true)->orderBy('id', 'DESC')->get(),
                    'usuarios' => User::orderBy('id', 'DESC')->get(),
                ],
            ]);
        } catch (\Throwable $th) {
            return redirect()->route($this->table . '.index')
                ->with('message', 'Error al encontrar el registro: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateempleadosRequest  $request
     * @param  \App\Models\empleados  $empleados
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateempleadosRequest $r)
    {
        try {

            $e = empleados::findOrFail($r->id);
            $e->nombre_completo = $r->nombre_completo;
            $e->identificaciones_id = $r->identificaciones_id;
            $e->numero_documento = $r->numero_documento;
            $e->users_id = $r->users_id ?? null;
            $e->save();

            return to_route($this->table . '.index')
                ->with('message', 'Registro editado correctamente: ' . $e->nombre_completo)
                ->with('type', 'success');
        } catch (\Throwable $th) {
            return redirect()->route($this->table . '.index')
                ->with('message', 'Error al editar el registro: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }
    public function confirm($id)
    {
        try {
            return view("confirm", [
                'th' => $this->th['confirm'],
                'p' => empleados::findOrFail(Crypt::decryptString($id))
            ]);
        } catch (\Throwable $t) {
            return redirect()->back()
                ->with('message', 'Ocurrio un error (' . $t->getMessage() . ')')
                ->with('type', 'danger');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\empleados  $empleados
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $r)
    {
        try {
            if (!isset($r->id) || empty(trim($r->id)))
                return redirect()->route($this->table . '.index')
                    ->with('message', 'Ocurrio un error, el identificador del registro no cumple los requerimientos necesarios.')
                    ->with('type', 'danger');

            empleados::destroy(Crypt::decryptString($r->id));

            return redirect()->route($this->table . '.index')
                ->with('message', 'Registro eliminado con exito');
        } catch (\Throwable $th) {
            return redirect()->back()
                ->with('message', 'Ocurrio un error (' . $th->getMessage() . ')')
                ->with('type', 'danger');
        }
    }
    public function status($id)
    {
        try {

            $e = empleados::findOrFail(Crypt::decryptString($id));
            $e->estado = !$e->estado;

            $e->save();
            $message = $e->estado
                ? 'Se activo el empleado' . $e->nombre_completo
                : 'Este empleado esta inactivo ' . $e->nombre_completo;
            $type = $e->estado ? 'success' : 'danger';

            return redirect()->back()
                ->with('message', $message)
                ->with('type', $type);
        } catch (\Throwable $th) {
            return redirect()->back()->with('message', 'Ocurrió un error al desactivar cliente. ' . $th->getMessage());
        }

    }
}
