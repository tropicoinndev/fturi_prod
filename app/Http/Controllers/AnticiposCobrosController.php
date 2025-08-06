<?php

namespace App\Http\Controllers;

use App\Http\Requests\Storeanticipos_cobrosRequest;
use App\Http\Requests\Updateanticipos_cobrosRequest;
use App\Models\anticipos_cobros;

class AnticiposCobrosController extends Controller
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
     * @param  \App\Http\Requests\Storeanticipos_cobrosRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Storeanticipos_cobrosRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\anticipos_cobros  $anticipos_cobros
     * @return \Illuminate\Http\Response
     */
    public function show(anticipos_cobros $anticipos_cobros)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\anticipos_cobros  $anticipos_cobros
     * @return \Illuminate\Http\Response
     */
    public function edit(anticipos_cobros $anticipos_cobros)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Updateanticipos_cobrosRequest  $request
     * @param  \App\Models\anticipos_cobros  $anticipos_cobros
     * @return \Illuminate\Http\Response
     */
    public function update(Updateanticipos_cobrosRequest $request, anticipos_cobros $anticipos_cobros)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\anticipos_cobros  $anticipos_cobros
     * @return \Illuminate\Http\Response
     */
    public function destroy(anticipos_cobros $anticipos_cobros)
    {
        //
    }
}
