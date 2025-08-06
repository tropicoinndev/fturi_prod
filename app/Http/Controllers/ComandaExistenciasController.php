<?php

namespace App\Http\Controllers;

use App\Http\Requests\Storecomanda_existenciasRequest;
use App\Http\Requests\Updatecomanda_existenciasRequest;
use App\Models\comanda_existencias;

class ComandaExistenciasController extends Controller
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
     * @param  \App\Http\Requests\Storecomanda_existenciasRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(
        $comanda_detalles_id,
        $existencias_id,
        $productos_id,
        $cantidad

    ) {
        try {

            $dt = new comanda_existencias;
            $dt->comanda_detalles_id = $comanda_detalles_id;
            $dt->existencias_id = $existencias_id;
            $dt->productos_id = $productos_id;
            $dt->cantidad = $cantidad;
            $dt->save();
        } catch (\Throwable $th) {
            return throw $th;
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\comanda_existencias  $comanda_existencias
     * @return \Illuminate\Http\Response
     */
    public function show(comanda_existencias $comanda_existencias)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\comanda_existencias  $comanda_existencias
     * @return \Illuminate\Http\Response
     */
    public function edit(comanda_existencias $comanda_existencias)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Updatecomanda_existenciasRequest  $request
     * @param  \App\Models\comanda_existencias  $comanda_existencias
     * @return \Illuminate\Http\Response
     */
    public function update(Updatecomanda_existenciasRequest $request, comanda_existencias $comanda_existencias)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\comanda_existencias  $comanda_existencias
     * @return \Illuminate\Http\Response
     */
    public function destroy(comanda_existencias $comanda_existencias)
    {
        //
    }
}
