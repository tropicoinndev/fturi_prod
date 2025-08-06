<?php

namespace App\Http\Controllers;

use App\Http\Requests\Storeconceptos_sujeto_excluidosRequest;
use App\Http\Requests\Updateconceptos_sujeto_excluidosRequest;
use App\Models\conceptos_sujeto_excluidos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use function Pest\Laravel\json;

class ConceptosSujetoExcluidosController extends Controller
{
    /**
     * cSpell:ignore busqueda
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function search(Request $r)
    {
        return response()->json([
            'list' => conceptos_sujeto_excluidos::where(DB::raw('UPPER(conceptos)'), 'like', '%' . strtoupper($r->busqueda) . '%')->where("estado", true)->get()
        ]);
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
     * @param  \App\Http\Requests\Storeconceptos_sujeto_excluidosRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store($r)
    {
        $c = new conceptos_sujeto_excluidos;
        $c->conceptos = $r->concepto;
        $c->tipo_item = $r->tipo_item;
        $c->unidad = $r->unidad_medida;
        $c->monto = $r->precio_unitario;
        $c->renta = $r->renta ?? 0;
        $c->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\conceptos_sujeto_excluidos  $conceptos_sujeto_excluidos
     * @return \Illuminate\Http\Response
     */
    public function show(conceptos_sujeto_excluidos $conceptos_sujeto_excluidos)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\conceptos_sujeto_excluidos  $conceptos_sujeto_excluidos
     * @return \Illuminate\Http\Response
     */
    public function edit(conceptos_sujeto_excluidos $conceptos_sujeto_excluidos)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Updateconceptos_sujeto_excluidosRequest  $request
     * @param  \App\Models\conceptos_sujeto_excluidos  $conceptos_sujeto_excluidos
     * @return \Illuminate\Http\Response
     */
    public function update(Updateconceptos_sujeto_excluidosRequest $request, conceptos_sujeto_excluidos $conceptos_sujeto_excluidos)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\conceptos_sujeto_excluidos  $conceptos_sujeto_excluidos
     * @return \Illuminate\Http\Response
     */
    public function destroy(conceptos_sujeto_excluidos $conceptos_sujeto_excluidos)
    {
        //
    }
}
