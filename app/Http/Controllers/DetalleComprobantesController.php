<?php

namespace App\Http\Controllers;

use App\Http\Requests\Storedetalle_comprobantesRequest;
use App\Http\Requests\Updatedetalle_comprobantesRequest;
use App\Models\detalle_comprobantes;

class DetalleComprobantesController extends Controller
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
     * @param  \App\Http\Requests\Storedetalle_comprobantesRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Storedetalle_comprobantesRequest $request) {}

    public function createDetalle($data, $comprobantes_id, $nc = false)
    {
        $p = new detalle_comprobantes;
        $p->cantidad = $data->cantidad;
        $p->concepto = $data->concepto;
        $p->iva = $data->iva;
        $p->cesc = $nc ? 0 : $data->cesc;
        $p->advalorem = $nc ? 0 : $data->advalorem;
        $p->neto = $data->neto;
        $p->gravado = $data->gravado;
        $p->exento = $data->exento;
        $p->propina = $nc ? 0 : $data->propina;
        $p->total = $data->total;
        $p->descuento = $data->descuento;
        $p->comprobantes_id = $comprobantes_id;
        $p->descuentos_id = $data->descuentos_id;
        $p->porcentaje_descuento = $data->porcentaje_descuento;
        $p->sugerido = $data->sugerido;
        $p->rubros_id = $data->rubros_id;
        $p->registro = $data->registro;
        $p->tipo_registros = $data->tipo_registros;
        if (!$p->save())
            throw new \ErrorException(`El comprobante ($comprobantes_id) se guardo, pero ocurrio un error al guardar el detalle del comprobante, tome un una captura a este mensaje y envie a informatica@tropicoinn.com.sv.`);
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\detalle_comprobantes  $detalle_comprobantes
     * @return \Illuminate\Http\Response
     */
    public function show(detalle_comprobantes $detalle_comprobantes)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\detalle_comprobantes  $detalle_comprobantes
     * @return \Illuminate\Http\Response
     */
    public function edit(detalle_comprobantes $detalle_comprobantes)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Updatedetalle_comprobantesRequest  $request
     * @param  \App\Models\detalle_comprobantes  $detalle_comprobantes
     * @return \Illuminate\Http\Response
     */
    public function update(Updatedetalle_comprobantesRequest $request, detalle_comprobantes $detalle_comprobantes)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\detalle_comprobantes  $detalle_comprobantes
     * @return \Illuminate\Http\Response
     */
    public function destroy(detalle_comprobantes $detalle_comprobantes)
    {
        //
    }
}
