<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StoresonidosRequest;
use App\Http\Requests\UpdatesonidosRequest;
use App\Models\sonidos;
use Illuminate\Support\Facades\Crypt;

class SonidosController extends Controller
{
    private $table = 'sonidos';

    public function __construct()
    {
        $this->getTh($this->table, 'Sonidos');
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
            'p' => sonidos::orderBy('id', 'DESC')->paginate(15),
        ]);
    }
    public function search(Request $r)
    {
        return view($this->table . '.index', [
            'th' => $this->th['index'],
            'p' => sonidos::where('sonido', 'ilike', '%' . $r->txtBusqueda . '%')->paginate(),
            'txtBusqueda' => $r->txtBusqueda,
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
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoresonidosRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoresonidosRequest $request)
    {
        try {
            $data = new sonidos();
            $data->sonido = $request->sonido;
            $data->descripcion = $request->descripcion;
            $data->save();

            return to_route($this->table . '.index')
                ->with('message', 'Registro guardado correctamente: ' . $data->sonido)
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
     * @param  \App\Models\sonidos  $sonidos
     * @return \Illuminate\Http\Response
     */
    public function show(sonidos $sonidos)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\sonidos  $sonidos
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            return view($this->table . '.edit', [
                'th' => $this->th['edit'],
                'p' => sonidos::findOrFail(Crypt::decryptString($id)),
            ]);
        } catch (\Throwable $th) {
            return redirect()
                ->route($this->table . '.index')
                ->with('message', 'Error al encontrar el registro: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatesonidosRequest  $request
     * @param  \App\Models\sonidos  $sonidos
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatesonidosRequest $request)
    {
        try {
            $p = sonidos::findOrFail($request->id);
            $p->sonido = $request->sonido;
            $p->descripcion = $request->descripcion;
            $p->save();

            return redirect()
                ->route($this->table . '.index')
                ->with('message', 'Registro editado correctamente: ' . $p->sonido)
                ->with('type', 'info');
        } catch (\Throwable $th) {
            return redirect()
                ->route($this->table . '.index')
                ->with('message', 'Error al editar el registro: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }
    public function confirm($id)
    {
        try {
            return view('confirm', [
                'th' => $this->th['confirm'],
                'p' => sonidos::findOrFail(Crypt::decryptString($id)),
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
     * @param  \App\Models\sonidos  $sonidos
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

            sonidos::destroy(Crypt::decryptString($r->id));

            return redirect()
                ->route($this->table . '.index')
                ->with('message', 'Registro eliminado con exito');
        } catch (\Throwable $th) {
            return redirect()
                ->back()
                ->with('message', 'Ocurrio un error (' . $th->getMessage() . ')')
                ->with('type', 'danger');
        }
    }
    public function status($id)
    {
        try {
            $p = sonidos::findOrFail(Crypt::decryptString($id));
            $p->estado = !$p->estado;
            $p->save();

            return to_route($this->table . '.index')
                ->with('message', 'Estado modificado correctamente.')
                ->with('type', 'success');
        } catch (\Throwable $th) {
            return redirect()
                ->route($this->table . '.index')
                ->with('message', 'Error al cambiar estado: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }
}
