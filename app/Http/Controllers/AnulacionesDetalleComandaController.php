<?php

namespace App\Http\Controllers;

use App\Http\Requests\Storeanulaciones_detalle_comandaRequest;
use App\Http\Requests\Updateanulaciones_detalle_comandaRequest;
use App\Models\anulaciones_detalle_comanda;
use Illuminate\Support\Facades\Auth;

class AnulacionesDetalleComandaController extends Controller
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
     * @param  \App\Http\Requests\Storeanulaciones_detalle_comandaRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store($cantidad, $comanda, $observacion)
    {
        try {
            $p = new anulaciones_detalle_comanda;
            $p->cantidad = $cantidad;
            $p->observacion = $observacion;
            $p->comanda_detalles_id = $comanda;
            $p->users_id = Auth::user()->id;
            $p->save();
            var_dump($p);
        } catch (\Throwable $th) {
            return throw $th;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\anulaciones_detalle_comanda  $anulaciones_detalle_comanda
     * @return \Illuminate\Http\Response
     */
    public function show(anulaciones_detalle_comanda $anulaciones_detalle_comanda)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\anulaciones_detalle_comanda  $anulaciones_detalle_comanda
     * @return \Illuminate\Http\Response
     */
    public function edit(anulaciones_detalle_comanda $anulaciones_detalle_comanda)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Updateanulaciones_detalle_comandaRequest  $request
     * @param  \App\Models\anulaciones_detalle_comanda  $anulaciones_detalle_comanda
     * @return \Illuminate\Http\Response
     */
    public function update(Updateanulaciones_detalle_comandaRequest $request, anulaciones_detalle_comanda $anulaciones_detalle_comanda)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\anulaciones_detalle_comanda  $anulaciones_detalle_comanda
     * @return \Illuminate\Http\Response
     */
    public function destroy(anulaciones_detalle_comanda $anulaciones_detalle_comanda)
    {
        //
    }
}
