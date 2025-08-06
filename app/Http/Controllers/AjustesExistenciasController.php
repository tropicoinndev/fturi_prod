<?php

namespace App\Http\Controllers;

use App\Http\Requests\Storeajustes_existenciasRequest;
use App\Http\Requests\Updateajustes_existenciasRequest;
use App\Models\ajustes_existencias;

class AjustesExistenciasController extends Controller
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
     * @param  \App\Http\Requests\Storeajustes_existenciasRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Storeajustes_existenciasRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ajustes_existencias  $ajustes_existencias
     * @return \Illuminate\Http\Response
     */
    public function show(ajustes_existencias $ajustes_existencias)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ajustes_existencias  $ajustes_existencias
     * @return \Illuminate\Http\Response
     */
    public function edit(ajustes_existencias $ajustes_existencias)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Updateajustes_existenciasRequest  $request
     * @param  \App\Models\ajustes_existencias  $ajustes_existencias
     * @return \Illuminate\Http\Response
     */
    public function update(Updateajustes_existenciasRequest $request, ajustes_existencias $ajustes_existencias)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ajustes_existencias  $ajustes_existencias
     * @return \Illuminate\Http\Response
     */
    public function destroy(ajustes_existencias $ajustes_existencias)
    {
        //
    }
}
