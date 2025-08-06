<?php

namespace App\Http\Controllers;

use App\Http\Requests\Storetipo_pagosRequest;
use App\Http\Requests\Updatetipo_pagosRequest;
use App\Models\tipo_pagos;

#Agregar.
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class TipoPagosController extends Controller
{
    private $table = 'tipo_pagos';

    private $tipoPagoTokens = [ ['value' => '1301', 'text' => 'Contado'],
        ['value' => '1302', 'text' => 'Crédito']];

    public function __construct()
    {
        $this->getTh($this->table, 'Tipo pagos');
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
            'p' => tipo_pagos::orderBy('id', 'DESC')->paginate(15),
            'data' => [
                'tipoPagoTokens' => $this->tipoPagoTokens,
            ],
        ]);
    }

    public function search(Request $request)
    {
        if (isset($request->txtBusqueda)) {
            $p = tipo_pagos::where('tipo_pago', 'ilike', '%' . $request->txtBusqueda . '%')
                ->orWhere('token', 'ilike', '%' . $request->txtBusqueda . '%')
                ->paginate();

            return view($this->table . '.index', [
                'th' => $this->th['index'],
                'p' => $p,
                'data' => [
                    'tipoPagoTokens' => $this->tipoPagoTokens,
                ],
                'txtBusqueda' => $request->txtBusqueda,
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
                'tipoPagoTokens' => $this->tipoPagoTokens,
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Storetipo_pagosRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Storetipo_pagosRequest $request)
    {
        try {
        $validatedData = $request->validate([
            'tipo_pago' => 'required|string',
        ]);

             // Agregar mensajes de depuración
        if ($request->filled('token')) {
                $token = $request->input('token');
            } else {
                $token = $request->input('token_user');
            }

        $nuevoTipoPago = new tipo_pagos;
        $nuevoTipoPago->tipo_pago = $validatedData['tipo_pago'];
        $nuevoTipoPago->token = $token;
        $nuevoTipoPago->save();
        $message = 'Registro guardado correctamente: ' . $validatedData['tipo_pago'];

        return to_route($this->table . '.index')
            ->with('message', 'Registro guardado correctamente: ')
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
     * @param  \App\Models\tipo_pagos  $tipo_pagos
     * @return \Illuminate\Http\Response
     */
    public function show(tipo_pagos $tipo_pagos)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\tipo_pagos  $tipo_pagos
     * @return \Illuminate\Http\Response
     */
    public function edit(tipo_pagos $tipo_pagos)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Updatetipo_pagosRequest  $request
     * @param  \App\Models\tipo_pagos  $tipo_pagos
     * @return \Illuminate\Http\Response
     */
    public function update(Updatetipo_pagosRequest $request, tipo_pagos $tipo_pagos)
    {
        //
    }

    public function confirm($id)
    {
        try {
            return view('confirm', [
                'th' => $this->th['confirm'],
                'p' => tipo_pagos::findOrFail(Crypt::decryptString($id)),
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
     * @param  \App\Models\tipo_pagos  $tipo_pagos
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

            #tipo_pagos::destroy(Crypt::decryptString($r->id));
            $p = tipo_pagos::findOrFail(Crypt::decryptString($r->id));
            $p->delete();

            return to_route($this->table . '.index')->with('message', 'Registro eliminado con exito: ' . $p->tipo_pago);
        } catch (\Throwable $t) {
            return redirect()
                ->back()
                ->with('message', 'Ocurrio un error (' . $t->getMessage() . ')')
                ->with('type', 'danger');
        }
    }
}
