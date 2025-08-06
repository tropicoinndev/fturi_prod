<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreidentificacionesRequest;
use App\Http\Requests\UpdateidentificacionesRequest;

use App\Models\identificaciones;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;


#Agregar


use Illuminate\Support\Facades\DB;


class IdentificacionesController extends Controller
{
    private $table = 'identificaciones';

    public function __construct()
    {
        $this->getTh($this->table, 'Identificaciones');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        #return identificaciones::orderBy('id','DESC')->first('identificacion');
        #return DB::table('identificaciones')->latest('id')->first('identificacion');

        return view($this->table . '.index', [
            'th' => $this->th['index'],
            'p' => identificaciones::orderBy('id', 'DESC')->paginate(15)
        ]);
    }

    public function search(Request $r)
    {
        return view($this->table . '.index', [
            'th' => $this->th['index'],
            'p' => identificaciones::where('identificacion', 'ilike', '%' . $r->txtBusqueda . '%')->paginate(),
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
            'th' => $this->th['create']
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreidentificacionesRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreidentificacionesRequest $request)
    {
        try {
             $p = new identificaciones;
            $p->identificacion = $request->identificacion;
            $p->regex = $request->regex;
            $p->tipo_cliente = $request->tipo_cliente;
            $p->info = $request->info;
            $p->codigo = $request->codigo;
            $p->save();


            return to_route($this->table . '.index')
                ->with('message', 'Registro guardado correctamente: ' . $p->identificacion)
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
     * @param  \App\Models\identificaciones  $identificaciones
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\identificaciones  $identificaciones
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            return view($this->table . '.edit', [
                'th' => $this->th['edit'],
                'p' => identificaciones::findOrFail(Crypt::decryptString($id)),
                'table' => $this->table,
                'data' => [],
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
     * @param  \App\Http\Requests\UpdateidentificacionesRequest  $request
     * @param  \App\Models\identificaciones  $identificaciones
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateidentificacionesRequest $request)
    {
        try {
            $p = identificaciones::findOrFail($request->id);

            $p->identificacion = $request->identificacion;
            $p->regex = $request->regex;
            $p->tipo_cliente = $request->tipo_cliente;
            $p->info = $request->info;
            $p->codigo = $request->codigo;
            $p->save();

            $p->save();

            return to_route($this->table . '.index')
                ->with('message', 'Registro actualizado correctamente: ' . $p->identificacion)
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
                'p' => identificaciones::findOrFail(Crypt::decryptString($id))
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
     * @param  \App\Models\identificaciones  $identificaciones
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $r)
    {
        try {
            if (!isset($r->id) || empty(trim($r->id)))
                return redirect()->route($this->table . '.index')
                    ->with('message', 'Ocurrio un error, el identificador del registro no cumple los requerimientos necesarios.')
                    ->with('type', 'danger');

            #identificaciones::destroy(Crypt::decryptString($r->id));
            $p = identificaciones::findOrFail(Crypt::decryptString($r->id));
            $p->delete();

            return to_route($this->table . '.index')
                ->with('message', 'Registro eliminado con exito: ' . $p->identificacion);
        } catch (\Throwable $t) {
            return redirect()->back()
                ->with('message', 'Ocurrio un error (' . $t->getMessage() . ')')
                ->with('type', 'danger');
        }
    }

    public function status($id)
    {
        try {
            $p = identificaciones::findOrFail(Crypt::decryptString($id));
            $p->estado = !$p->estado;
            $p->save();

            return to_route($this->table . '.index')
                ->with('message', 'Estado modificado correctamente: ' . $p->identificacion)
                ->with('type', 'success');
        } catch (\Throwable $th) {
            return redirect()->route($this->table . '.index')
                ->with('message', 'Error al cambiar estado: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }

    public function apiGetIdentificaciones(){
        return response()->json([
            'identificaciones'=>identificaciones::orderBy('identificacion','asc')->get(),
        ]);
    }
}
