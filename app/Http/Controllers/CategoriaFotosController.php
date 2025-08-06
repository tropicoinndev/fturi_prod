<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Http\Requests\Storecategoria_fotosRequest;
use App\Http\Requests\Updatecategoria_fotosRequest;
use App\Models\categoria_fotos;

class CategoriaFotosController extends Controller
{
    private $table = 'categoria_fotos';
    public function __construct()
    {
        $this->getTh($this->table, 'Categorias fotos');
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
            'p' => categoria_fotos::orderBy('categoria', 'DESC')->paginate(15),
            'table' => $this->table,
        ]);
    }
    public function search(Request $r)
    {
        return view($this->table . '.index', [
            'th' => $this->th['index'],
            'p' => categoria_fotos::where('categoria', 'ilike', '%' . $r->txtBusqueda . '%')->paginate(),
            'txtBusqueda' => $r->txtBusqueda,
            'table' => $this->table,
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
     * @param  \App\Http\Requests\Storecategoria_fotosRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Storecategoria_fotosRequest $request)
    {
        try {
            $p = new categoria_fotos();
            $p->categoria = $request->categoria;
            $p->save();
            return redirect()
                ->route($this->table . '.index')
                ->with('message', 'Registro guardado correctamente: ' . $p->categoria)
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
     * @param  \App\Models\categoria_fotos  $categoria_fotos
     * @return \Illuminate\Http\Response
     */
    public function show(categoria_fotos $categoria_fotos)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\categoria_fotos  $categoria_fotos
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            return view($this->table . '.edit', [
                'th' => $this->th['edit'],
                'p' => categoria_fotos::findOrFail(Crypt::decryptString($id)),
                'table' => $this->table,
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
     * @param  \App\Http\Requests\Updatecategoria_fotosRequest  $request
     * @param  \App\Models\categoria_fotos  $categoria_fotos
     * @return \Illuminate\Http\Response
     */
    public function update(Updatecategoria_fotosRequest $request)
    {
        try {
            $p = categoria_fotos::findOrFail($request->id);
            $p->categoria = $request->categoria;
            $p->save();

            return redirect()
                ->route($this->table . '.index')
                ->with('message', 'Registro editado correctamente: ' . $p->categoria)
                ->with('type', 'info');
        } catch (\Throwable $th) {
            return redirect()
                ->route($this->table . '.index')
                ->with('message', 'Error al editar el registro: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\categoria_fotos  $categoria_fotos
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

            $p = categoria_fotos::findOrFail(Crypt::decryptString($r->id));
            $p->delete();

            return to_route($this->table . '.index')->with('message', 'Registro eliminado con exito: ' . $p->categoria);
        } catch (\Throwable $t) {
            return redirect()
                ->back()
                ->with('message', 'Ocurrio un error (' . $t->getMessage() . ')')
                ->with('type', 'danger');
        }
    }
    public function confirm($id)
    {
        try {
            return view('confirm', [
                'th' => $this->th['confirm'],
                'p' => categoria_fotos::findOrFail(Crypt::decryptString($id)),
            ]);
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
            $p = categoria_fotos::findOrFail(Crypt::decryptString($id));
            $p->estado = !$p->estado;
            $p->save();

            return to_route($this->table . '.index')
                ->with('message', 'Estado modificado correctamente: ' . $p->categoria)
                ->with('type', 'success');
        } catch (\Throwable $th) {
            return redirect()
                ->route($this->table . '.index')
                ->with('message', 'Error al cambiar estado: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }
}
