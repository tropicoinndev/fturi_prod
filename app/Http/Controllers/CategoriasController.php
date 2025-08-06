<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

use App\Http\Requests\StorecategoriasRequest as StoreRequest;
use App\Http\Requests\UpdatecategoriasRequest as UpdateRequest;
use App\Models\categorias as model;

class CategoriasController extends Controller
{

    private $table = 'categorias';
    public $tokensCategorias = [
        ['token' => 1201, 'categoria' => 'venta'],
        ['token' => 1202, 'categoria' => 'produción'],
        ['token' => 1203, 'categoria' => 'ventas-produción']

    ];


    public function __construct()
    {
        $this->getTh($this->table, 'Categorías');
    }

    public function index()
    {
        return view($this->table . '.index', [
            'th'        => $this->th['index'],
            'p'         => $this->getCategorias(),
            'table'     => $this->table,

        ]);
    }

    public function getCategorias(){
        return model::orderBy('categoria','ASC')->paginate(15);
    }

    public function search(Request $r)
    {
        return view($this->table . '.index', [
            'th'            => $this->th['index'],
            'p'             => model::where('categoria', 'ilike', '%' . $r->txtBusqueda . '%')->paginate(),
            'txtBusqueda'   => $r->txtBusqueda,
            'table'         => $this->table,

        ]);
    }

    public function create()
    {
        return view($this->table . '.create', [
            'th' => $this->th['create'],
            'table' => $this->table,
            'tokensCategorias' => $this->tokensCategorias,

        ]);
    }

    public function store(StoreRequest $request)
    {
        try {
            $p = new model;
            $p->categoria = $request->categoria;
            $p->token = $request->token;
            $p->save();

            return redirect()
                ->route($this->table . '.index', ['id' => Crypt::encryptString($p->id)])
                ->with('message', 'Registro guardado correctamente: ' . $p->categoria)
                ->with('type', 'success');
        } catch (\Throwable $th) {
            return redirect()->route($this->table . '.index')
                ->with('message', 'Error al guardar el registro: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }


    public function edit($id)
    {
        try {

            return view($this->table . ".edit", [

                'th' => $this->th['edit'],
                'p' => model::findOrFail(Crypt::decryptString($id)),
                'table' => $this->table,

            ]);
        } catch (\Throwable $th) {
            return redirect()->route($this->table . '.index')
                ->with('message', 'Error al encontrar el registro: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }
    public function update(UpdateRequest $request)
    {
        try {
            $p = model::findOrFail($request->id);
            $p->categoria = $request->categoria;
            $p->token = $request->token;
            $p->save();

            return redirect()->route($this->table . '.index')
                ->with('message', 'Registro editado correctamente: ' . $p->categoria)
                ->with('type', 'info');
        } catch (\Throwable $th) {
            return redirect()->route($this->table . '.index')
                ->with('message', 'Error al editar el registro: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }

    public function destroy(Request $r)
    {
        try {
            if (!isset($r->id) || empty(trim($r->id)))
                return redirect()->route($this->table . '.index')
                    ->with('message', 'Ocurrio un error, el identificador del registro no cumple los requerimientos necesarios.')
                    ->with('type', 'danger');

            $p = model::findOrFail(Crypt::decryptString($r->id));
            $p->delete();

            return to_route($this->table . '.index')
                ->with('message', 'Registro eliminado con exito: ' . $p->categoria);
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
                'p' => model::findOrFail(Crypt::decryptString($id))
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
            $p = model::findOrFail(Crypt::decryptString($id));
            $p->estado = !$p->estado;
            $p->save();

            return to_route($this->table . '.index')
                ->with('message', 'Estado modificado correctamente: ' . $p->categoria)
                ->with('type', 'success');
        } catch (\Throwable $th) {
            return redirect()->route($this->table . '.index')
                ->with('message', 'Error al cambiar estado: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }
}
