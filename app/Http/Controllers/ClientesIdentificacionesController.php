<?php

namespace App\Http\Controllers;

use App\Http\Requests\Storeclientes_identificacionesRequest;
use App\Http\Requests\Updateclientes_identificacionesRequest;
use App\Models\clientes;

#Agregar
use App\Models\clientes_identificaciones;
use App\Models\identificaciones;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class ClientesIdentificacionesController extends Controller
{
    private $table = 'clientes_identificaciones';

    public function __construct()
    {
        $this->getTh($this->table, 'Clientes identificaciones');
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
            'p' => clientes_identificaciones::orderBy('id', 'DESC')->paginate(15),
            'table' => $this->table,
            'data' => [
                'identificaciones' => identificaciones::orderBy('identificacion', 'ASC')->get(),
                'clientes' => clientes::orderBy('nombre', 'ASC')->get()
            ],
        ]);
    }

    public function search(Request $request)
    {
        if (isset($request->txtBusqueda)) {
            $p = clientes_identificaciones::where('numero', 'like', '%' . $request->txtBusqueda . '%')
                ->paginate();

            return view($this->table . '.index', [
                'th' => $this->th['index'],
                'p' => $p,
                'txtBusqueda' => $request->txtBusqueda,
                'data' => [
                    'identificaciones' => identificaciones::orderBy('identificacion', 'ASC')->get(),
                    'clientes' => clientes::orderBy('nombre', 'ASC')->get()
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
        return view($this->table . '.create', [
            'th' => $this->th['create'],
            'table' => $this->table,
            'data' => [
                'identificaciones' => identificaciones::orderBy('identificacion', 'ASC')->get(),
                'clientes' => clientes::orderBy('nombre', 'ASC')->get()
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Storeclientes_identificacionesRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Storeclientes_identificacionesRequest $request)
    {
        try {
            $p = new clientes_identificaciones;
            $p->numero = $request->numero;
            $p->identificaciones_id = $request->identificaciones_id;
            $p->clientes_id = $request->clientes_id;
            $p->save();

            return redirect()->back()
                ->with('message', 'Registro guardado correctamente: ' . $p->numero)
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
     * @param  \App\Models\clientes_identificaciones  $clientes_identificaciones
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            return view($this->table . '.show', [
                'th' => $this->th['show'],
                'p' => clientes_identificaciones::findOrFail(Crypt::decryptString($id)),
                'table' => $this->table,
                'data' => [
                    'identificaciones' => identificaciones::orderBy('identificacion', 'ASC')->get(),
                    'clientes' => clientes::orderBy('nombre', 'ASC')->get()
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
     * @param  \App\Models\clientes_identificaciones  $clientes_identificaciones
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            return view($this->table . '.edit', [
                'th' => $this->th['edit'],
                'p' => clientes_identificaciones::findOrFail(Crypt::decryptString($id)),
                'table' => $this->table,
                'data' => [
                    'identificaciones' => identificaciones::orderBy('identificacion', 'ASC')->get(),
                    'clientes' => clientes::orderBy('nombre', 'ASC')->get()
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
     * @param  \App\Http\Requests\Updateclientes_identificacionesRequest  $request
     * @param  \App\Models\clientes_identificaciones  $clientes_identificaciones
     * @return \Illuminate\Http\Response
     */
    public function update(Updateclientes_identificacionesRequest $request)
    {
        try {
            $p = clientes_identificaciones::findOrFail($request->id);

            $p->numero = $request->numero;
            $p->identificaciones_id = $request->identificaciones_id;
            $p->clientes_id = $request->clientes_id;
            $p->save();

            return to_route($this->table . '.index')
                ->with('message', 'Registro actualizado correctamente: ' . $p->numero)
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
                'p' => clientes_identificaciones::findOrFail(Crypt::decryptString($id))
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
     * @param  \App\Models\clientes_identificaciones  $clientes_identificaciones
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $r)
    {
        try {
            if (!isset($r->id) || empty(trim($r->id)))
                return redirect()->route($this->table . '.index')
                    ->with('message', 'Ocurrio un error, el identificador del registro no cumple los requerimientos necesarios.')
                    ->with('type', 'danger');

            #clientes_identificaciones::destroy(Crypt::decryptString($r->id));
            $p = clientes_identificaciones::findOrFail(Crypt::decryptString($r->id));
            $p->delete();

            return redirect()->route('clientes.show', ['id' => Crypt::encryptString($p->clientes_id)])
                ->with('message', 'Registro eliminado con exito: ' . $p->numero);
        } catch (\Throwable $t) {
            return redirect()->back()
                ->with('message', 'Ocurrio un error (' . $t->getMessage() . ')')
                ->with('type', 'danger');
        }
    }
}
