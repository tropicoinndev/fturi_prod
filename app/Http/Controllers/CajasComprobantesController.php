<?php

namespace App\Http\Controllers;

use App\Http\Requests\Storecajas_comprobantesRequest;
use App\Http\Requests\Updatecajas_comprobantesRequest;
use App\Models\cajas_comprobantes;
use App\Models\cajas;
use Illuminate\Support\Facades\Crypt;
class CajasComprobantesController extends Controller
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
     * @param  \App\Http\Requests\Storecajas_comprobantesRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Storecajas_comprobantesRequest $r)
    {
        try {
            $caja = cajas::find(Crypt::decryptString($r->caja));
            $cajas_c = cajas::find($r->cajaC);
            $message = '';

            // Verifico si la caja y la caja origen son diferentes
            if ($caja->id != $cajas_c->id) {
                // Verifico si ya existe
                if (
                    cajas_comprobantes::where('cajas_id', $caja->id)
                        ->where('origen_cajas_id', $cajas_c->id)
                        ->count() > 0
                ) {
                    cajas_comprobantes::where('cajas_id', $caja->id)
                        ->where('origen_cajas_id', $cajas_c->id)
                        ->delete();
                    $message = 'Se eliminó correctamente';
                } else {
                    $p = new cajas_comprobantes();
                    $p->cajas_id = $caja->id;
                    $p->origen_cajas_id = $cajas_c->id;
                    $p->save();
                    $message = 'Se agregó correctamente';
                }
                return response()->json([
                    'message' => $message,
                    'type' => 'info',
                    'list' => cajas_comprobantes::where('cajas_id', $caja->id)
                        ->with('cajas_origen')
                        ->get(),
                ]);
            } else {
                return response()->json(['message' => 'No se puede agregar la misma caja como origen.', 'type' => 'info']);
            }
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Ocurrió un error:' . $th->getMessage(), 'type' => 'info']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\cajas_comprobantes  $cajas_comprobantes
     * @return \Illuminate\Http\Response
     */
    public function show(cajas_comprobantes $cajas_comprobantes)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\cajas_comprobantes  $cajas_comprobantes
     * @return \Illuminate\Http\Response
     */
    public function edit(cajas_comprobantes $cajas_comprobantes)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Updatecajas_comprobantesRequest  $request
     * @param  \App\Models\cajas_comprobantes  $cajas_comprobantes
     * @return \Illuminate\Http\Response
     */
    public function update(Updatecajas_comprobantesRequest $request, cajas_comprobantes $cajas_comprobantes)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\cajas_comprobantes  $cajas_comprobantes
     * @return \Illuminate\Http\Response
     */
    public function destroy(cajas_comprobantes $cajas_comprobantes)
    {
        //
    }
}
