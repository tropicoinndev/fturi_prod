<?php

namespace App\Http\Controllers;

use App\Http\Requests\Storeabonos_detallesRequest;
use App\Http\Requests\Updateabonos_detallesRequest;
use App\Models\abonos;
use App\Models\abonos_detalles;
use App\Models\comprobantes;
use App\Models\comprobantes_pagos;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class AbonosDetallesController extends Controller
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
    public function create(Request $r)
    {
        $id = Crypt::decryptString($r->id);
        $p = abonos::findOrFail($id);
        $comprobantes = (new AbonosController)->getComprobantesInterface($p->clientes_id)->get();
        return view('abonos_detalles.create', ['p' => $p, 'comprobantes' => $comprobantes]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Storeabonos_detallesRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Storeabonos_detallesRequest $r)
    {
        try {
            $p = abonos::findOrFail(Crypt::decryptString($r->id));
            if ($p->estado)
                throw new Exception('Esta cuenta ya se completo, no se pueden agregar mas facturas');
            $comprobantes = array();
            foreach ($r->comprobantes as $c)
                array_push($comprobantes, Crypt::decryptString($c));

            if (abonos_detalles::whereIn('comprobantes_id', $comprobantes)->count() > 0)
                throw new Exception('Error: Los comprobantes seleccionado ya están agregados a un ingreso de caja, intente de nuevo o elimine el ingreso a caja.');

            $pagos = DB::table('getcomprobantescreditos')->whereIn('comprobantes_id', $comprobantes)->get();
            $sum = 0;
            $concepto = "POR CANCELACION DE COMPROBANTES: ";
            foreach ($pagos as $v) {
                $ad = new abonos_detalles;
                $ad->monto = $v->monto;
                $ad->estado = true;
                $ad->users_id = Auth::user()->id;
                $ad->abonos_id = $p->id;
                $ad->comprobantes_id = $v->comprobantes_id;
                $ad->save();
                $sum += $ad->monto;
                $concepto = $concepto . " " . $v->correlativo . "->" . $v->fecha . " $" . $ad->monto . "; ";
            }
            $p->monto = $sum;
            $p->estado = true;
            $p->concepto = $concepto;
            $p->save();
            return redirect()->route('abonos.container', ['id' => Crypt::encryptString($p->id)])
                ->with('message', 'Se agrego el ingreso a caja #' . $p->id . ' con el monto: $' . $p->monto);
        } catch (\Throwable $th) {
            return redirect()->back()->with('message', 'Ocurrio un problema al agregar ' . $th->getMessage())->with('type', 'danger');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\abonos_detalles  $abonos_detalles
     * @return \Illuminate\Http\Response
     */
    public function show(abonos_detalles $abonos_detalles)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\abonos_detalles  $abonos_detalles
     * @return \Illuminate\Http\Response
     */
    public function edit(abonos_detalles $abonos_detalles)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Updateabonos_detallesRequest  $request
     * @param  \App\Models\abonos_detalles  $abonos_detalles
     * @return \Illuminate\Http\Response
     */
    public function update(Updateabonos_detallesRequest $request, abonos_detalles $abonos_detalles)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\abonos_detalles  $abonos_detalles
     * @return \Illuminate\Http\Response
     */
    public function destroy(abonos_detalles $abonos_detalles)
    {
        //
    }
}
