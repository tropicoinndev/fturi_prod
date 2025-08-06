<?php

namespace App\Http\Controllers;

use App\Http\Requests\Storecliente_identificacionesRequest;
use App\Http\Requests\Updatecliente_identificacionesRequest;
use App\Models\cliente_identificaciones;

class ClienteIdentificacionesController extends Controller
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
     * @param  \App\Http\Requests\Storecliente_identificacionesRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Storecliente_identificacionesRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\cliente_identificaciones  $cliente_identificaciones
     * @return \Illuminate\Http\Response
     */
    public function show(cliente_identificaciones $cliente_identificaciones)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\cliente_identificaciones  $cliente_identificaciones
     * @return \Illuminate\Http\Response
     */
    public function edit(cliente_identificaciones $cliente_identificaciones)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Updatecliente_identificacionesRequest  $request
     * @param  \App\Models\cliente_identificaciones  $cliente_identificaciones
     * @return \Illuminate\Http\Response
     */
    public function update(Updatecliente_identificacionesRequest $request, cliente_identificaciones $cliente_identificaciones)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\cliente_identificaciones  $cliente_identificaciones
     * @return \Illuminate\Http\Response
     */
    public function destroy(cliente_identificaciones $cliente_identificaciones)
    {
        //
    }
}
