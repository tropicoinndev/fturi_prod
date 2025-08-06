<?php

namespace App\Http\Controllers;

use App\Http\Requests\Storemh_contingencia_itemsRequest;
use App\Http\Requests\Updatemh_contingencia_itemsRequest;
use App\Models\dtes;
use App\Models\mh_contingencia_items;

class MhContingenciaItemsController extends Controller
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
     * @param  \App\Http\Requests\Storemh_contingencia_itemsRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store($tipo_dte, $codigo_generacion, $dtes_id, $mh_contingencias_id)
    {

        $n = new mh_contingencia_items;
        $n->tipo_doc = $tipo_dte;
        $n->codigo_generacion = $codigo_generacion;
        $n->dtes_id = $dtes_id;
        $n->mh_contingencias_id = $mh_contingencias_id;
        $n->save();
        return $n;
    }



    /**
     * Display the specified resource.
     *
     * @param  \App\Models\mh_contingencia_items  $mh_contingencia_items
     * @return \Illuminate\Http\Response
     */
    public function show(mh_contingencia_items $mh_contingencia_items)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\mh_contingencia_items  $mh_contingencia_items
     * @return \Illuminate\Http\Response
     */
    public function edit(mh_contingencia_items $mh_contingencia_items)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Updatemh_contingencia_itemsRequest  $request
     * @param  \App\Models\mh_contingencia_items  $mh_contingencia_items
     * @return \Illuminate\Http\Response
     */
    public function update(Updatemh_contingencia_itemsRequest $request, mh_contingencia_items $mh_contingencia_items)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\mh_contingencia_items  $mh_contingencia_items
     * @return \Illuminate\Http\Response
     */
    public function destroy(mh_contingencia_items $mh_contingencia_items)
    {
        //
    }
}
