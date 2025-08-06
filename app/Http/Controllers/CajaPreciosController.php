<?php

namespace App\Http\Controllers;

use App\Http\Requests\Storecaja_preciosRequest;
use App\Http\Requests\Updatecaja_preciosRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Models\caja_precios;
use App\Models\precios;
use App\Models\cajas;

class CajaPreciosController extends Controller
{   
    private $table = 'caja_precios';

    public function __construct()
    {
        $this->getTh($this->table, 'Caja Precios');
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
            'p' => caja_precios::orderBy('id', 'DESC')->paginate(15),
            'table' => $this->table,
            'data' => [
                'precios' => precios::orderBy('detalle', 'ASC')->get(),
                'cajas' => cajas::orderBy('caja', 'ASC')->get()
            ],
        ]);
    }
    /***funcion para listar cajas agregar a cierto precio */
        public function index_api()
    {
        return response()->json(['list' => $this->getList()]);
    }

    private function getList()
    {
        return cajas::orderBy('caja', 'ASC')->get();
    }
    /***funcion para listar precios agregar a cierto caja */
        public function index_precio()
    {
        return response()->json(['listp' => $this->getListP()]);
    }

    private function getListP()
    {
        return precios::orderBy('detalle', 'ASC')->get();
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
            'p' => caja_precios::orderBy('id', 'DESC')->paginate(15),
            'table' => $this->table,
            'data' => [
                'precios' => precios::orderBy('detalle', 'ASC')->get(),
                'cajas' => cajas::orderBy('caja', 'ASC')->get()
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Storecaja_preciosRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Storecaja_preciosRequest $request)
    {
        try {
            $p = new caja_precios;
            
            $p->precios_id = $request->precios_id;
            $p->cajas_id = $request->cajas_id;
            $p->save();

            return to_route($this->table . '.index')
                ->with('message', 'Registro guardado correctamente.')
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
     * @param  \App\Models\caja_precios  $caja_precios
     * @return \Illuminate\Http\Response
     */
    public function show(caja_precios $caja_precios)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\caja_precios  $caja_precios
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
            try {
            return view($this->table . '.edit', [
                'th' => $this->th['edit'],
                'p' => caja_precios::findOrFail(Crypt::decryptString($id)),
                'table' => $this->table,
                'data' => [
                    'precios' => precios::orderBy('detalle', 'ASC')->get(),
                    'cajas' => Cajas::orderBy('caja', 'ASC')->get()
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
     * @param  \App\Http\Requests\Updatecaja_preciosRequest  $request
     * @param  \App\Models\caja_precios  $caja_precios
     * @return \Illuminate\Http\Response
     */
    public function update(Updatecaja_preciosRequest $request)
    {
        try {
            $p = caja_precios::findOrFail($request->id);

            $p->precios_id = $request->precios_id;
            $p->cajas_id = $request->cajas_id;
            $p->save();

            return to_route($this->table . '.index')
                ->with('message', 'Registro actualizado correctamente.')
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
                'p' => caja_precios::findOrFail(Crypt::decryptString($id))
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
     * @param  \App\Models\caja_precios  $caja_precios
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $r)
    {
            try {
            if (!isset($r->id) || empty(trim($r->id)))
                return redirect()->route($this->table . '.index')
                    ->with('message', 'Ocurrio un error, el identificador del registro no cumple los requerimientos necesarios.')
                    ->with('type', 'danger');

            #cajas_users::destroy(Crypt::decryptString($r->id));
            $p = caja_precios::findOrFail(Crypt::decryptString($r->id));
            $p->delete();

            return to_route($this->table . '.index')
                ->with('message', 'Registro eliminado con exito.');
        } catch (\Throwable $t) {
            return redirect()->back()
                ->with('message', 'Ocurrio un error (' . $t->getMessage() . ')')
                ->with('type', 'danger');
        }
    }
        public function destroy_api(Request $r)
    {
        $m = "Se elimino una caja a este precio";
        $t = true;
        try {
            cajas::destroy($r->id);
        } catch (\Throwable $th) {
            $t = false;
            $m = "Error: " . $th->getMessage();
        }
        return response()->json([
            'list' => $this->getList(),
            'message' => $m,
            'type' => $t ? 'success' : 'danger',
        ]);
    }
        public function destroy_apiPrecio(Request $r)
    {
        $m = "Se elimino un precio a esta caja";
        $t = true;
        try {
            precios::destroy($r->id);
        } catch (\Throwable $th) {
            $t = false;
            $m = "Error: ". $th->getMessage();
        }
        return response()->json([
            'listp' => $this->getListP(),
            'message' => $m,
            'type' => $t ? 'success' : 'danger',
        ]);
    }
}
