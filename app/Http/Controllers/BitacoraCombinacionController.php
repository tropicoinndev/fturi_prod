<?php

namespace App\Http\Controllers;

use App\Http\Requests\Storebitacora_combinacionRequest;
use App\Http\Requests\Updatebitacora_combinacionRequest;
use App\Models\bitacora_combinacion;

class BitacoraCombinacionController extends Controller
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
     * @param  \App\Http\Requests\Storebitacora_combinacionRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Storebitacora_combinacionRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\bitacora_combinacion  $bitacora_combinacion
     * @return \Illuminate\Http\Response
     */
    public function show(bitacora_combinacion $bitacora_combinacion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\bitacora_combinacion  $bitacora_combinacion
     * @return \Illuminate\Http\Response
     */
    public function edit(bitacora_combinacion $bitacora_combinacion)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Updatebitacora_combinacionRequest  $request
     * @param  \App\Models\bitacora_combinacion  $bitacora_combinacion
     * @return \Illuminate\Http\Response
     */
    public function update(Updatebitacora_combinacionRequest $request, bitacora_combinacion $bitacora_combinacion)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\bitacora_combinacion  $bitacora_combinacion
     * @return \Illuminate\Http\Response
     */
    public function destroy(bitacora_combinacion $bitacora_combinacion)
    {
        //
    }
}
