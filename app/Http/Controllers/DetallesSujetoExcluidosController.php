<?php

namespace App\Http\Controllers;

use App\Http\Requests\Storedetalles_sujeto_excluidosRequest;
use App\Http\Requests\Updatedetalles_sujeto_excluidosRequest;
use App\Models\detalles_sujeto_excluidos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class DetallesSujetoExcluidosController extends Controller
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
     * @param  \App\Http\Requests\Storedetalles_sujeto_excluidosRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Storedetalles_sujeto_excluidosRequest $r)
    {
        try {

            $p = new detalles_sujeto_excluidos();
            $p->tipo_item = $r->tipo_item;
            $p->cantidad = $r->cantidad;
            $p->unidad_medida = $r->unidad_medida;
            $p->descripcion = $r->concepto;
            $p->precio_unitario = $r->precio_unitario;
            $p->renta = $r->renta ?? 0;
            $p->compra = $r->total;
            $p->sujeto_excluidos_id = Crypt::decryptString($r->id);
            $p->users_id = Auth::user()->id;
            $p->save();

            if ($r->opcion == 2) (new ConceptosSujetoExcluidosController)->store($r);

            return redirect()->back()->with('message', 'Se guardo el registro');
        } catch (\Throwable $th) {
            return redirect()->back()->with('type', 'danger')->with('message', 'Error:' . $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\detalles_sujeto_excluidos  $detalles_sujeto_excluidos
     * @return \Illuminate\Http\Response
     */
    public function show(detalles_sujeto_excluidos $detalles_sujeto_excluidos)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\detalles_sujeto_excluidos  $detalles_sujeto_excluidos
     * @return \Illuminate\Http\Response
     */
    public function edit(detalles_sujeto_excluidos $detalles_sujeto_excluidos)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Updatedetalles_sujeto_excluidosRequest  $request
     * @param  \App\Models\detalles_sujeto_excluidos  $detalles_sujeto_excluidos
     * @return \Illuminate\Http\Response
     */
    public function update(Updatedetalles_sujeto_excluidosRequest $request, detalles_sujeto_excluidos $detalles_sujeto_excluidos)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\detalles_sujeto_excluidos  $detalles_sujeto_excluidos
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $r)
    {
        try {
            detalles_sujeto_excluidos::destroy(Crypt::decryptString($r->id));
            return redirect()->back()->with('message', 'Se elimino el registro');
        } catch (\Throwable $th) {
            return redirect()->back()->with('type', 'danger')->with('message', 'Error:' . $th->getMessage());
        }
    }
}
