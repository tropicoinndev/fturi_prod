<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorecontactosRequest;
use App\Http\Requests\UpdatecontactosRequest;
use App\Models\contactos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class ContactosController extends Controller
{
    private $table = 'contactos';

    public function __construct()
    {
        $this->getTh($this->table, 'Contacto');
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
            'p' => contactos::orderBy('id', 'DESC')->paginate(10),
            'table' => $this->table,

        ]);
    }

    public function search(Request $request)
    {
        try {
            if (isset($request->txtBusqueda)) {
                $data = contactos::where(DB::raw('UPPER(contacto)'), 'like', '%' . strtoupper($request->txtBusqueda) . '%')
                    ->orWhere('regex', 'like', '%' . $request->txtBusqueda . '%')
                    ->paginate();
                return view($this->table . '.index', [
                    'th' => $this->th['index'],
                    'p' => $data,
                    'txtBusqueda' => $request->txtBusqueda,
                ]);
            } else {
                return to_route($this->table . '.index');
            }
        } catch (\Throwable $th) {
            return to_route($this->table . '.index')
                ->with('message', 'Error al encontrar el registro: ' . $th->getMessage())
                ->with('type', 'danger');
        } catch (\Throwable $th) {
            return to_route($this->table . '.index')
                ->with('message', 'Error al encontrar el registro: ' . $th->getMessage())
                ->with('type', 'danger');
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
            'p' => contactos::orderBy('id', 'DESC')->paginate(5),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorecontactosRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorecontactosRequest $request)
    {
        try {
            $p = new contactos;
            $p->contacto = $request->contacto;
            $p->regex = $request->regex;
            $p->info = $request->info;
            $p->save();

            return to_route($this->table . '.index')
                ->with('message', 'Registro guardado correctamente: ' . $p->contacto)
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
     * @param  \App\Models\contactos  $contactos
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\contactos  $contactos
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            return view($this->table . '.edit', [
                'th' => $this->th['edit'],
                'p' => contactos::findOrFail(Crypt::decryptString($id)),
                'data' => [],
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
     * @param  \App\Http\Requests\UpdatecontactosRequest  $request
     * @param  \App\Models\contactos  $contactos
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatecontactosRequest $request)
    {
        try {
            $p = contactos::findOrFail($request->id);
            $p->contacto = $request->contacto;
            $p->regex = $request->regex;
            $p->info = $request->info;
            $p->save();

            return redirect()->route($this->table . '.index')
                ->with('message', 'Registro editado correctamente: ' . $p->contacto)
                ->with('type', 'info');
        } catch (\Throwable $th) {
            return redirect()->route($this->table . '.index')
                ->with('message', 'Error al editar el registro: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }

    public function confirm($id)
    {
        try {
            return view('confirm', [
                'th' => $this->th['confirm'],
                'p' => contactos::findOrFail(Crypt::decryptString($id))
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
     * @param  \App\Models\contactos  $contactos
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $r)
    {
        try {
            #contactos::destroy(Crypt::decryptString($r->id));
            $p = contactos::findOrFail(Crypt::decryptString($r->id));
            $p->delete();

            contactos::destroy(Crypt::decryptString($r->id));
            return to_route($this->table . '.index')
                ->with('message', 'Registro eliminado con exito');
        } catch (\Throwable $t) {
            return redirect()->back()
                ->with('message', 'Ocurrio un error (' . $t->getMessage() . ')')
                ->with('type', 'danger');
        }
    }

    public function status($id)
    {
        try {
            $data = contactos::findOrFail(Crypt::decryptString($id));
            $data->estado = !$data->estado;
            $data->save();

            return to_route($this->table . '.index')
                ->with('message', 'Estado modificado correctamente: ' . $data->contacto)
                ->with('type', 'success');
        } catch (\Throwable $th) {
            return redirect()->route($this->table . '.index')
                ->with('message', 'Error al cambiar estado: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }
}
