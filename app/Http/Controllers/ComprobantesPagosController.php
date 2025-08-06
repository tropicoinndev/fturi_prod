<?php

namespace App\Http\Controllers;

use App\Http\Requests\Storecomprobantes_pagosRequest;
use App\Http\Requests\Updatecomprobantes_pagosRequest;
use App\Models\comprobantes;
use App\Models\comprobantes_pagos;
use App\Models\forma_pagos;

class ComprobantesPagosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Storecomprobantes_pagosRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Storecomprobantes_pagosRequest $request)
    {
        //
    }

    public function createPagos($forma_pago, $valor, $comprobante)
    {
        if ($forma_pago != null && $forma_pago > 0 && $valor != null && $valor > 0 && $comprobante != null && $comprobante > 0) {
            $p = new comprobantes_pagos();
            $p->monto = round($valor, 2);
            $p->forma_pagos_id = $forma_pago;
            $p->comprobantes_id = $comprobante;
            if (!$p->save()) {
                throw new \ErrorException(`El comprobante ($comprobante) se guardo, pero ocurrio un error al guardar las formas de pagos, tome un una captura a este mensaje y envie a informatica@tropicoinn.com.sv.`);
            }
        }
    }

    public function isValid($forma_pago)
    {
        return forma_pagos::findOrFail($forma_pago);
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\comprobantes_pagos  $comprobantes_pagos
     * @return \Illuminate\Http\Response
     */
    public function show(comprobantes_pagos $comprobantes_pagos)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\comprobantes_pagos  $comprobantes_pagos
     * @return \Illuminate\Http\Response
     */
    public function edit(comprobantes_pagos $comprobantes_pagos)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Updatecomprobantes_pagosRequest  $request
     * @param  \App\Models\comprobantes_pagos  $comprobantes_pagos
     * @return \Illuminate\Http\Response
     */
    public function update(Updatecomprobantes_pagosRequest $request, comprobantes_pagos $comprobantes_pagos)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\comprobantes_pagos  $comprobantes_pagos
     * @return \Illuminate\Http\Response
     */
    public function destroy(comprobantes_pagos $comprobantes_pagos)
    {
        //
    }
}
