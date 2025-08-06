<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Http\Requests\Storecontrol_cortesiasRequest;
use App\Http\Requests\Updatecontrol_cortesiasRequest;
use App\Models\control_cortesias;
use App\Models\tipo_cortesia;

class ControlCortesiasController extends Controller
{
    private $table = 'control_cortesias';

    public function __construct()
    {
        $this->getTh($this->table, 'Control cortesias');
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
            'p' => control_cortesias::with('tipo_cortesias')
                ->orderBy('id', 'asc')
                ->get(),
            'table' => $this->table,
            'data' => [
                'tipo_cortesia' => tipo_cortesia::orderBy('id', 'DESC')->get(),
            ],
        ]);
    }
    /** busqueda de control cortesias */
    public function search(Request $r)
    {
        return view($this->table . '.index', [
            'th' => $this->th['index'],
            'p' => control_cortesias::where('id', 'ilike', '%' . $r->txtBusqueda . '%')
                ->orWhere('monto', 'ilike', '%' . $r->txtBusqueda . '%')
                ->orWhere('titular', 'ilike', '%' . $r->txtBusqueda . '%')
                ->paginate(15),
            'txtBusqueda' => $r->txtBusqueda,
            'table' => $this->table,
            'data' => [
                'tipo_cortesia' => tipo_cortesia::orderBy('id', 'ASC')->get(),
            ],
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
            'data' => [
                'tipo_cortesia' => tipo_cortesia::orderBy('id', 'asc')->get(),
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Storecontrol_cortesiasRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Storecontrol_cortesiasRequest $request)
    {
        try {
            $data = new control_cortesias();
            $data->monto = $request->monto;
            $data->titular = $request->titular;
            $data->tipo_cortesias_id = $request->tipo_cortesias_id;
            $data->save();

            return redirect()
                ->route($this->table . '.index')
                ->with('message', 'Registro guardado correctamente: ' . $data->titular)
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
     * @param  \App\Models\control_cortesias  $control_cortesias
     * @return \Illuminate\Http\Response
     */
    public function show(control_cortesias $control_cortesias)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\control_cortesias  $control_cortesias
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            return view($this->table . '.edit', [
                'th' => $this->th['edit'],
                'p' => control_cortesias::with('tipo_cortesias')->findOrFail(Crypt::decryptString($id)),
                'table' => $this->table,
                'data' => [
                    'tipo_cortesia' => tipo_cortesia::orderBy('id', 'asc')->get(),
                ],
            ]);
        } catch (\Throwable $th) {
            return to_route($this->table . '.index')
                ->with('message', 'Error al contrar el registro: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }

    /**
     * Update the specified resource in storage.
     *4
     * @param  \App\Http\Requests\Updatecontrol_cortesiasRequest  $request
     * @param  \App\Models\control_cortesias  $control_cortesias
     * @return \Illuminate\Http\Response
     */
    public function update(Updatecontrol_cortesiasRequest $request)
    {
        try {
            $p = control_cortesias::findOrFail($request->id);
            $p->monto = $request->monto;
            $p->titular = $request->titular;
            $p->tipo_cortesias_id = $request->tipo_cortesias_id;
            $p->save();

            return redirect()
                ->route($this->table . '.index')
                ->with('message', 'Registro guardado correctamente: ' . $p->titular)
                ->with('type', 'success');
        } catch (\Throwable $th) {
            return redirect()
                ->route($this->table . '.index')
                ->with('message', 'Error al guardar el registro: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }
    /**fuction for confirm */
    public function confirm($id)
    {
        try {
            return view('confirm', [
                'th' => $this->th['confirm'],
                'p' => control_cortesias::findOrFail(Crypt::decryptString($id)),
            ]);
        } catch (\Throwable $th) {
            return redirect()
                ->back()
                ->with('message', 'Ocurrio un error (' . $th->getMessage() . ')')
                ->with('type', 'danger');
        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\control_cortesias  $control_cortesias
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $r)
    {
        try {
            if (!isset($r->id) || empty(trim($r->id))) {
                return redirect()
                    ->route($this->table . '. index')
                    ->with('message', 'Ocurrior un error, el identificador de registro no cumple con los requerimientos necesarios.')
                    ->with('type', 'danger');
            }
            $p = control_cortesias::findOrFail(Crypt::decryptString($r->id));
            $p->delete();
            return to_route($this->table . '.index')->with('message', 'Registro eliminado con exito: ' . $p->titular);
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
            $p = control_cortesias::findOrFail(Crypt::decryptString($id));
            $p->estado = !$p->estado;
            $p->save();

            return redirect()
                ->back()
                ->with('message', 'Se ha modificado el estado ' . $p->titular)
                ->with('type', 'success');
        } catch (\Throwable $th) {
            return redirect()
                ->route($this->table . '.index')
                ->with('message', 'Error al cambiar estado: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }
}
