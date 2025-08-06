<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests\Storetipo_eventosRequest;
use App\Http\Requests\Updatetipo_eventosRequest;
use App\Models\tipo_eventos;
use Illuminate\Support\Facades\Crypt;


class TipoEventosController extends Controller
{
    private $table = 'tipo_eventos';

    public function __construct()
    {
        $this->getTh($this->table, 'Tipo de eventos');
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
            'p' => tipo_eventos::orderBy('id', 'DESC')->paginate(15),
        ]);
    }
    public function search(Request $r)
    {
        return view($this->table . '.index', [
            'th' => $this->th['index'],
            'p' => tipo_eventos::where('evento', 'ilike', '%' . $r->txtBusqueda . '%')->paginate(),
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
     * @param  \App\Http\Requests\Storetipo_eventosRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Storetipo_eventosRequest $request)
    {
        try {
            $data = new tipo_eventos();
            $data->evento = $request->evento;
            $data->descripcion = $request->descripcion;
            $data->save();

            return to_route($this->table . '.index')
                ->with('message', 'Registro guardado correctamente: ' . $data->evento)
                ->with('type', 'success');
        } catch (\Throwable $th) {
            return redirect()
                ->route($this->table . '.index')
                ->with('message', 'Error al guardar el registro: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }

    public function salonTipoEvento (Request $r)
    {
        try {
            $r->validate([
                'tipo_eventos_id'=>['required'],
            ]);
            $tipo_evento = tipo_eventos::find($r->tipo_eventos_id);
            if ($tipo_evento && $tipo_evento->estado ) {
                return response()->json(['habilitar_salones' => $tipo_evento->salon]);
            } else {
                return response()->json(['habilitar_salones' => false]);
            }
        } catch (\Throwable $th) {
            return response()->json(['error'=>'No se encontro el tipo de evento no encontrado'],400);
        }

    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\tipo_eventos  $tipo_eventos
     * @return \Illuminate\Http\Response
     */
    public function edit( $id)
    {
        try {
            return view($this->table . '.edit', [
                'th' => $this->th['edit'],
                'p' => tipo_eventos::findOrFail(Crypt::decryptString($id)),
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
     * @param  \App\Http\Requests\Updatetipo_eventosRequest  $request
     * @param  \App\Models\tipo_eventos  $tipo_eventos
     * @return \Illuminate\Http\Response
     */
    public function update(Updatetipo_eventosRequest $request)
    {
        try {
            $p = tipo_eventos::findOrFail($request->id);
            $p->evento = $request->evento;
            $p->descripcion = $request->descripcion;
            $p->save();

            return redirect()
                ->route($this->table . '.index')
                ->with('message', 'Registro editado correctamente: ' . $p->evento)
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
                'p' => tipo_eventos::findOrFail(Crypt::decryptString($id)),
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
     * @param  \App\Models\tipo_eventos  $tipo_eventos
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

            tipo_eventos::destroy(Crypt::decryptString($r->id));

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
            $p = tipo_eventos::findOrFail(Crypt::decryptString($id));
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
    public function salon($id)
    {
        try {
            $p = tipo_eventos::findOrFail(Crypt::decryptString($id));
            $p->salon = !$p->salon;
            $p->save();

            return to_route($this->table . '.index')
                ->with('message', 'Salon modificado correctamente.')
                ->with('type', 'success');
        } catch (\Throwable $th) {
            return redirect()
                ->route($this->table . '.index')
                ->with('message', 'Error al cambiar Salon: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }
}
