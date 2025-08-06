<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

use App\Http\Requests\StorecorrelativosRequest;
use App\Http\Requests\UpdatecorrelativosRequest;
use App\Models\correlativos;
use App\Models\cajas;
use App\Models\tipo_comprobantes;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;

class CorrelativosController extends Controller
{
    private $table = 'correlativos';

    public function __construct()
    {
        $this->getTh($this->table, 'Correlativos');
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
            'p' => correlativos::orderBy('id', 'DESC')->paginate(15),
            'table' => $this->table,
            'data' => [
                'cajas' => cajas::orderBy('caja', 'ASC')->get(),
                'tipo_comprobantes' => tipo_comprobantes::orderBy('tipo', 'ASC')->get(),
                'users' => User::orderBy('name', 'ASC')->get(),
            ],
        ]);
    }
    public function getCorrelativoByToken($token, $caja)
    {
        return correlativos::where('cajas_id', $caja)
            ->where('estado', true)
            ->whereColumn('actual', '<', 'final')
            ->whereIn('tipo_comprobantes_id', tipo_comprobantes::where('token', $token)->pluck('id'))
            ->orderBy('id', 'desc')
            ->first();
    }
    public function isValidCorrelativo($correlativo)
    {
        if ($correlativo == null)
            throw new Exception('No se encontró un correlativo, agregue un correlativo y vuelva a intentar.');

        if (!$correlativo->estado)
            throw new Exception('El correlativo interno que esta utilizando ya esta desactivado, agregue uno nuevo.');

        if ($correlativo->actual + 1 > $correlativo->final)
            throw new Exception('Debe agregar un nuevo correlativo, el actual ya llego al final.');
    }
    public function setCorrelativo($id)
    {
        $c = correlativos::find($id);
        $actual = $c->actual == 0 ? $c->inicio : $c->actual + 1;
        $c->actual = $actual;

        if ($c->actual == $c->final)
            $c->estado = false;

        return $c->save();
    }
    public function search(Request $request)
    {
        if (isset($request->txtBusqueda)) {
            $p = correlativos::where('inicio', 'ilike', '%' . $request->txtBusqueda . '%')
                ->orWhere('actual', 'ilike', '%' . $request->txtBusqueda . '%')
                ->orWhere('final', 'ilike', '%' . $request->txtBusqueda . '%')
                ->paginate();

            return view($this->table . '.index', [
                'th' => $this->th['index'],
                'p' => $p,
                'txtBusqueda' => $request->txtBusqueda,
                'data' => [
                    'cajas' => cajas::orderBy('caja', 'ASC')->get(),
                    'users' => User::orderBy('name', 'ASC')->get(),
                    'tipo_comprobantes' => tipo_comprobantes::orderBy('tipo', 'ASC')->get()
                ],
            ]);
        } else {
            return to_route($this->table . '.index');
        }
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
                'cajas' => cajas::orderBy('caja', 'ASC')->get(),
                'tipo_comprobantes' => tipo_comprobantes::orderBy('tipo', 'ASC')->get(),
                'users' => User::orderBy('name', 'ASC')->get(),
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorecorrelativosRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorecorrelativosRequest $r)
    {
        if (!session('caja'))
            return redirect()->route('cajas.login');

        try {
            $data = new Correlativos;
            $data->inicio = $r->inicio;
            $data->final = $r->final;
            $data->actual = 0;
            $data->cajas_id = session('caja')->id;
            $data->users_id = Auth::user()->id;
            $data->tipo_comprobantes_id = $r->tipo_comprobantes_id;
            $data->save();

            return to_route('cajas.my')
                ->with('message', 'Registro guardado correctamente.')
                ->with('type', 'success');
        } catch (\Throwable $th) {
            return redirect()->route('cajas.my')
                ->with('message', 'Error al guardar el registro: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\correlativos  $correlativos
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\correlativos  $correlativos
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            return view($this->table . '.edit', [
                'th' => $this->th['edit'],
                'p' => correlativos::findOrFail(Crypt::decryptString($id)),
                'table' => $this->table,
                'data' => [
                    'cajas' => cajas::orderBy('caja', 'ASC')->get(),
                    'tipo_comprobantes' => tipo_comprobantes::orderBy('tipo', 'ASC')->get(),
                    'users' => User::orderBy('name', 'ASC')->get(),
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
     * @param  \App\Http\Requests\UpdatecorrelativosRequest  $request
     * @param  \App\Models\correlativos  $correlativos
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatecorrelativosRequest $request)
    {
        try {
            $p = correlativos::findOrFail($request->id);
            $p->inicio = $request->inicio;
            $p->actual = $request->actual;
            $p->final = $request->final;
            $p->cajas_id = $request->cajas_id;
            $p->users_id = $request->users_id;
            $p->tipo_comprobantes_id = $request->tipo_comprobantes_id;
            $p->save();

            return redirect()->route($this->table . '.index')
                ->with('message', 'Registro editado correctamente: ' . $p->inicio)
                ->with('type', 'info');
        } catch (\Throwable $th) {
            return redirect()->route($this->table . '.index')
                ->with('message', 'Error al editar el registro: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }

    public function confirm($id)
    {
        try {
            return view("confirm", [
                'th' => $this->th['confirm'],
                'p' => Correlativos::findOrFail(Crypt::decryptString($id))
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
     * @param  \App\Models\correlativos  $correlativos
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $r)
    {
        try {
            if (!isset($r->id) || empty(trim($r->id)))
                return redirect()->route($this->table . '.index')
                    ->with('message', 'Ocurrio un error, el identificador del registro no cumple los requerimientos necesarios.')
                    ->with('type', 'danger');

            Correlativos::destroy(Crypt::decryptString($r->id));

            return redirect()->route($this->table . '.index')
                ->with('message', 'Registro eliminado con exito');
        } catch (\Throwable $t) {
            return redirect()->back()
                ->with('message', 'Ocurrio un error (' . $t->getMessage() . ')')
                ->with('type', 'danger');
        }
    }

    public function status($id)
    {
        try {
            $p = correlativos::findOrFail(Crypt::decryptString($id));
            $p->estado = !$p->estado;
            $p->save();

            return to_route($this->table . '.index')
                ->with('message', 'Estado modificado correctamente: ' . $p->inicio)
                ->with('type', 'success');
        } catch (\Throwable $th) {
            return redirect()->route($this->table . '.index')
                ->with('message', 'Error al cambiar estado: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }
}
