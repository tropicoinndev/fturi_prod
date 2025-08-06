<?php

namespace App\Http\Controllers;

use App\Http\Requests\Storecliente_contactosRequest;
use App\Http\Requests\Updatecliente_contactosRequest;
use App\Models\cliente_contactos;

class ClienteContactosController extends Controller
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
     * @param  \App\Http\Requests\Storecliente_contactosRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Storecliente_contactosRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\cliente_contactos  $cliente_contactos
     * @return \Illuminate\Http\Response
     */
    public function show(cliente_contactos $cliente_contactos)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\cliente_contactos  $cliente_contactos
     * @return \Illuminate\Http\Response
     */
    public function edit(cliente_contactos $cliente_contactos)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Updatecliente_contactosRequest  $request
     * @param  \App\Models\cliente_contactos  $cliente_contactos
     * @return \Illuminate\Http\Response
     */
    public function update(Updatecliente_contactosRequest $request, cliente_contactos $cliente_contactos)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\cliente_contactos  $cliente_contactos
     * @return \Illuminate\Http\Response
     */
    public function destroy(cliente_contactos $cliente_contactos)
    {
        //
    }
}
