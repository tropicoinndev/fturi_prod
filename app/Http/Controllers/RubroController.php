<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorerubroRequest;
use App\Http\Requests\UpdaterubroRequest;
use App\Models\rubro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class RubroController extends Controller
{
    private $table = 'rubros';

    public function __construct()
    {
        $this->getTh($this->table, 'Rubros');
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
            'p' => rubro::orderBy('id', 'DESC')->paginate(15),
            'table' => $this->table,
        ]);
    }
    /**funcion para realiza busqueda */
    public function search(Request $r)
    {
        return view($this->table . '.index', [
            'th' => $this->th['index'],
            'p' => rubro::where('rubro', 'ilike', '%' . $r->txtBusqueda . '%')->paginate(),
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
            'table' => $this->table,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorerubroRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorerubroRequest $request)
    {
        try {
            $p = new Rubro();
            $p->rubro = $request->rubro;
            $p->token = $request->token;
            $p->save();

            return redirect()
                ->route($this->table . '.index', ['id' => Crypt::encryptString($p->id)])
                ->with('message', 'Registro guardado correctamente: ' . $p->rubro)
                ->with('type', 'success');
        } catch (\Throwable $th) {
            return redirect()
                ->route($this->table . '.index')
                ->with('message', 'Error al guardar el registro: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }

    

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\rubro  $rubro
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            return view($this->table . '.edit', [
                'th' => $this->th['edit'],
                'p' => Rubro::findOrFail(Crypt::decryptString($id)),
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
     * @param  \App\Http\Requests\UpdaterubroRequest  $request
     * @param  \App\Models\rubro  $rubro
     * @return \Illuminate\Http\Response
     */
    public function update(UpdaterubroRequest $request)
    {
        try {
            $p = rubro::findOrFail($request->id);
            $p->rubro = $request->rubro;
            $p->token = $request->token;
            $p->save();

            return redirect()
                ->route($this->table . '.index')
                ->with('message', 'Registro editado correctamente: ' . $p->rubro)
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
     * @param  \App\Models\rubro  $rubro
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

            $p = Rubro::findOrFail(Crypt::decryptString($r->id));
            $p->delete();

            return to_route($this->table . '.index')->with('message', 'Registro eliminado con exito: ' . $p->rubro);
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
                'p' => Rubro::findOrFail(Crypt::decryptString($id)),
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
            $p = Rubro::findOrFail(Crypt::decryptString($id));
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
