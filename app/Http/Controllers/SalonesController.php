<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests\StoresalonesRequest;
use App\Http\Requests\UpdatesalonesRequest;
use App\Models\salones;
use Illuminate\Support\Facades\Crypt;

class SalonesController extends Controller
{
    private $table = 'salones';

    public function __construct()
    {
        $this->getTh($this->table, 'Salones');
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
            'p' => salones::orderBy('id', 'DESC')->paginate(15),
        ]);
    }
    public function search(Request $r)
    {
        return view($this->table . '.index', [
            'th' => $this->th['index'],
            'p' => salones::where('salon', 'ilike', '%' . $r->txtBusqueda . '%')->paginate(),
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
     * @param  \App\Http\Requests\StoresalonesRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoresalonesRequest $request)
    {
        try {
            $data = new salones();
            $data->salon = $request->salon;
            $data->descripcion = $request->descripcion;
            $data->save();

            return to_route($this->table . '.index')
                ->with('message', 'Registro guardado correctamente: ' . $data->salon)
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
     * @param  \App\Models\salones  $salones
     * @return \Illuminate\Http\Response
     */
    public function show(salones $salones)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\salones  $salones
     * @return \Illuminate\Http\Response
     */
    public function edit( $id)
    {
        try {
            return view($this->table . '.edit', [
                'th' => $this->th['edit'],
                'p' => salones::findOrFail(Crypt::decryptString($id)),
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
     * @param  \App\Http\Requests\UpdatesalonesRequest  $request
     * @param  \App\Models\salones  $salones
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatesalonesRequest $request)
    {
        try {
            $p = salones::findOrFail($request->id);
            $p->salon = $request->salon;
            $p->descripcion = $request->descripcion;
            $p->save();

            return redirect()
                ->route($this->table . '.index')
                ->with('message', 'Registro editado correctamente: ' . $p->salon)
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
                'p' => salones::findOrFail(Crypt::decryptString($id)),
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
     * @param  \App\Models\salones  $salones
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

            salones::destroy(Crypt::decryptString($r->id));

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
            $p = salones::findOrFail(Crypt::decryptString($id));
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
