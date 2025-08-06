<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoretemporadasRequest;
use App\Http\Requests\UpdatetemporadasRequest;
use App\Models\temporadas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class TemporadasController extends Controller
{
    private $table = 'temporadas';

    public function __construct()
    {
        $this->getTh($this->table, 'Temporadas');
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
            'p' => temporadas::orderBy('temporada', 'ASC')->paginate(15),
        ]);
    }
    public function search(Request $r)
    {
        return view($this->table . '.index', [
            'th' => $this->th['index'],
            'p' => temporadas::where('temporada', 'ilike', '%' . $r->txtBusqueda . '%')->paginate(),
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
     * @param  \App\Http\Requests\StoretemporadasRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoretemporadasRequest $request)
    {
        try {
            $data = new temporadas();
            $data->temporada = $request->temporada;
            $data->fecha_inicio = $request->fecha_inicio;
            $data->fecha_finalizacion = $request->fecha_finalizacion;
            $data->save();

            return to_route($this->table . '.index')
                ->with('message', 'Registro guardado correctamente: ' . $data->temporada)
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
     * @param  \App\Models\temporadas  $temporadas
     * @return \Illuminate\Http\Response
     */
    public function show(temporadas $temporadas)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\temporadas  $temporadas
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            return view($this->table . '.edit', [
                'th' => $this->th['edit'],
                'p' => temporadas::findOrFail(Crypt::decryptString($id)),
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
     * @param  \App\Http\Requests\UpdatetemporadasRequest  $request
     * @param  \App\Models\temporadas  $temporadas
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatetemporadasRequest $request, temporadas $temporadas)
    {
        try {
            $p = temporadas::findOrFail($request->id);

            $p->temporada = $request->temporada;
            $p->fecha_inicio = $request->fecha_inicio;
            $p->fecha_finalizacion = $request->fecha_finalizacion;
            $p->save();

            return to_route($this->table . '.index')
                ->with('message', 'Registro guardado correctamente: ' . $p->temporada)
                ->with('type', 'success');
        } catch (\Throwable $th) {
            return redirect()
                ->route($this->table . '.index')
                ->with('message', 'Error al guardar el registro: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }
    public function confirm($id)
    {
        try {
            return view('confirm', [
                'th' => $this->th['confirm'],
                'p' => temporadas::findOrFail(Crypt::decryptString($id)),
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
     * @param  \App\Models\temporadas  $temporadas
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

            temporadas::destroy(Crypt::decryptString($r->id));

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
            $p = temporadas::findOrFail(Crypt::decryptString($id));
            $p->estado = !$p->estado;
            $p->save();

            return redirect()
                ->back()
                ->with('message', 'Se ha modificado el estado ' . $p->detalle)
                ->with('type', 'success');
        } catch (\Throwable $th) {
            return redirect()
                ->route($this->table . '.index')
                ->with('message', 'Error al cambiar estado: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }
}
