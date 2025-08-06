<?php

namespace App\Http\Controllers;

use App\Http\Requests\Storedetalle_montaje_eventosRequest;
use App\Http\Requests\Updatedetalle_montaje_eventosRequest;
use App\Models\detalle_montaje_eventos;
use App\Models\eventos;
use Illuminate\Support\Facades\Crypt;

class DetalleMontajeEventosController extends Controller
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
     * @param  \App\Http\Requests\Storedetalle_montaje_eventosRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Storedetalle_montaje_eventosRequest $r)
    {
        try {
            $id = Crypt::decryptString($r->eventos_id);
            $e = eventos::find($id);
            if (!intval($id))
            throw new \Exception('No se encontro el evento.');

            $p = new detalle_montaje_eventos;
            $p->bandera = $r->bandera ? $r->bandera : null;
            $p->rotafolio_plumon = $r->rotafolio_plumon ? $r->rotafolio_plumon : null;
            $p->eventos_id = $e->id;
            $p->podium = $r->podium ? $r->podium : null;
            $p->pista_baile = $r->pista_baile ? $r->pista_baile : null;
            $p->tarima = $r->tarima ? $r->tarima : null;
            $p->tipo_mesa = $r->tipo_mesa ? $r->tipo_mesa : null;
            $p->equipo_montar = $r->equipo_montar ? $r->equipo_montar : null;
            $p->otros = $r->otros ? $r->otros : null;
            $p->save();
            return redirect()->back()->with('message', 'Se agreagaron los detalles del montaje al evento: '.$id)->with('type', 'success');
        }catch (\Throwable $th){
            return redirect()->back()->with('message', 'Ocurrio un error al guardar registro: ' .$th->getMessage())->with('type', 'danger');

        }


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\detalle_montaje_eventos  $detalle_montaje_eventos
     * @return \Illuminate\Http\Response
     */
    public function show(detalle_montaje_eventos $detalle_montaje_eventos)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\detalle_montaje_eventos  $detalle_montaje_eventos
     * @return \Illuminate\Http\Response
     */
    public function edit(detalle_montaje_eventos $detalle_montaje_eventos)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Updatedetalle_montaje_eventosRequest  $request
     * @param  \App\Models\detalle_montaje_eventos  $detalle_montaje_eventos
     * @return \Illuminate\Http\Response
     */
    public function update(Updatedetalle_montaje_eventosRequest $request, detalle_montaje_eventos $detalle_montaje_eventos)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\detalle_montaje_eventos  $detalle_montaje_eventos
     * @return \Illuminate\Http\Response
     */
    public function destroy(detalle_montaje_eventos $detalle_montaje_eventos)
    {
        //
    }
}
