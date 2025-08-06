<?php

namespace App\Http\Controllers;
use App\Http\Requests\Storeaplicacion_pagosRequest;
use App\Models\aplicacion_pagos;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PagosAplicacionController extends Controller
{
    private $table = 'aplicacion_pagos';

    public function __construct()
    {
        $this->getTh($this->table, 'Pagos aplicacion');
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
            'p' => aplicacion_pagos::with('usuarios')
                ->orderBy('id', 'asc')
                ->get(),
            'table' => $this->table,
            'data' => [
                'usuarios' => User::orderBy('id', 'DESC')->get(),
            ],
        ]);
    }
        /** busqueda de pagos */
    public function search(Request $r)
    {
        return view($this->table . '.index', [
            'th' => $this->th['index'],
            'p' => aplicacion_pagos::where('id', 'ilike', '%' . $r->txtBusqueda . '%')
                ->orWhere('origen', 'ilike', '%' . $r->txtBusqueda . '%')
                ->orWhere('origen_id', 'ilike', '%' . $r->txtBusqueda . '%')
                ->paginate(15),
            'txtBusqueda' => $r->txtBusqueda,
            'table' => $this->table,
            'data' => [
                'usuarios' => User::orderBy('id', 'ASC')->get(),
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
                'usuarios' => User::orderBy('id', 'asc')->get(),
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Storeaplicacion_pagosRequest  $request
     * @return \Illuminate\Http\Response
     */

    public function pagoCuentasEvento($cuenta, $pago_anticipado, $origen)
    {
        try {
            $pa  = new aplicacion_pagos();
            $pa->origen = $origen;
            $pa->origen_id = $cuenta;
            $pa->pago_anticipados_id = $pago_anticipado;
            $pa->users_id = Auth::id();
            $pa->save();
            return $pa;
         } catch (\Throwable $th) {
            throw $th;
        }
    }
    
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\aplicacion_pagos  $aplicacion_pagos
     * @return \Illuminate\Http\Response
     */
    public function show(aplicacion_pagos $aplicacion_pagos)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\aplicacion_pagos  $aplicacion_pagos
     * @return \Illuminate\Http\Response
     */
    public function edit(aplicacion_pagos $aplicacion_pagos)
    {
        //
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\aplicacion_pagos  $aplicacion_pagos
     * @return \Illuminate\Http\Response
     */
    public function destroy(aplicacion_pagos $aplicacion_pagos)
    {
        //
    }
}
