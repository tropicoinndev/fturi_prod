<?php

namespace App\Http\Controllers;

use App\Http\Requests\Storeclientes_contactosRequest;
use App\Http\Requests\Updateclientes_contactosRequest;
use App\Models\clientes;

#Agregar
use App\Models\clientes_contactos;
use App\Models\contactos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class ClientesContactosController extends Controller
{
    private $table = 'clientes_contactos';

    public function __construct()
    {
        $this->getTh($this->table, 'Clientes contactos');
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
            'p' => clientes_contactos::orderBy('id', 'DESC')->paginate(15),
            'table' => $this->table,
            'data' => [
                'contactos' => contactos::orderBy('contacto', 'ASC')->get(),
                'clientes' => clientes::orderBy('nombre', 'ASC')->get()
            ],
        ]);
    }

    public function search(Request $request)
    {
        if (isset($request->txtBusqueda)) {
            $p = clientes_contactos::where('valor', 'like', '%' . $request->txtBusqueda . '%')
                ->paginate();

            return view($this->table . '.index', [
                'th' => $this->th['index'],
                'p' => $p,
                'txtBusqueda' => $request->txtBusqueda,
                'data' => [
                    'contactos' => contactos::orderBy('contacto', 'ASC')->get(),
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
                'contactos' => contactos::orderBy('contacto', 'ASC')->get(),
                'clientes' => clientes::orderBy('nombre', 'ASC')->get()
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Storeclientes_contactosRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Storeclientes_contactosRequest $request)
    {
        try {

            $p = new clientes_contactos;
            $p->valor = $request->valor;
            $p->contactos_id = $request->contactos_id;
            $p->clientes_id = $request->clientes_id;
            $p->observaciones = $request->observacion;
            $p->save();

            return redirect()->back()
                ->with('message', 'Registro guardado correctamente: ' . $p->valor)
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
     * @param  \App\Models\clientes_contactos  $clientes_contactos
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            return view($this->table . '.show', [
                'th' => $this->th['show'],
                'p' => clientes_contactos::findOrFail(Crypt::decryptString($id)),
                'table' => $this->table,
                'data' => [
                    'contactos' => contactos::orderBy('contacto', 'ASC')->get(),
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
     * @param  \App\Models\clientes_contactos  $clientes_contactos
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            return view($this->table . '.edit', [
                'th' => $this->th['edit'],
                'p' => clientes_contactos::findOrFail(Crypt::decryptString($id)),
                'table' => $this->table,
                'data' => [
                    'contactos' => contactos::orderBy('contacto', 'ASC')->get(),
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
     * @param  \App\Http\Requests\Updateclientes_contactosRequest  $request
     * @param  \App\Models\clientes_contactos  $clientes_contactos
     * @return \Illuminate\Http\Response
     */
    public function update(Updateclientes_contactosRequest $request)
    {
        try {
            $p = clientes_contactos::findOrFail($request->id);

            $p->valor = $request->valor;
            $p->contactos_id = $request->contactos_id;
            $p->clientes_id = $request->clientes_id;
            $p->observaciones = $request->observaciones;
            $p->save();

            return to_route($this->table . '.index')
                ->with('message', 'Registro actualizado correctamente: ' . $p->valor)
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
                'p' => clientes_contactos::findOrFail(Crypt::decryptString($id))
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
     * @param  \App\Models\clientes_contactos  $clientes_contactos
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $r)
    {
        try {
            if (!isset($r->id) || empty(trim($r->id)))
                return redirect()->route($this->table . '.index')
                    ->with('message', 'Ocurrio un error, el identificador del registro no cumple los requerimientos necesarios.')
                    ->with('type', 'danger');

            #clientes_contactos::destroy(Crypt::decryptString($r->id));
            $p = clientes_contactos::findOrFail(Crypt::decryptString($r->id));
            $p->delete();

            return redirect()->route('clientes.show', ['id' => Crypt::encryptString($p->clientes_id)])
                ->with('message', 'Registro eliminado con exito: ' . $p->valor);
        } catch (\Throwable $t) {
            return redirect()->back()
                ->with('message', 'Ocurrio un error (' . $t->getMessage() . ')')
                ->with('type', 'danger');
        }
    }
}
