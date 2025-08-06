<?php

namespace App\Http\Controllers;

use App\Http\Requests\Storebodega_cajasRequest;
use App\Http\Requests\Updatebodega_cajasRequest;
use App\Models\bodega_cajas;
use App\Models\bodegas;
use App\Models\cajas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class BodegaCajasController extends Controller
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
     * @param  \App\Http\Requests\Storebodega_cajasRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $r)
    {
        try {
            $caja = cajas::find(Crypt::decryptString($r->caja));
            $bodega = bodegas::find($r->bodega);
            $message = "";

            if (bodega_cajas::where('cajas_id', $caja->id)->where('bodegas_id', $bodega->id)->count() > 0) {
                bodega_cajas::where('cajas_id', $caja->id)->where('bodegas_id', $bodega->id)->delete();
                $message = "Se elimino correctamente";
            } else {
                $p = new bodega_cajas;
                $p->cajas_id = $caja->id;
                $p->bodegas_id = $bodega->id;
                $p->save();
                $message = "Se agrego correctamente";
            }
            return response()->json(['message' => $message, 'type' => "info", "list" => bodega_cajas::where("cajas_id", $caja->id)->with("bodegas")->get()]);
        } catch (\Throwable $th) {
            return response()->json(['message' => "Ocurrio un error:" . $th->getMessage(), 'type' => "info"]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\bodega_cajas  $bodega_cajas
     * @return \Illuminate\Http\Response
     */
    public function show(bodega_cajas $bodega_cajas)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\bodega_cajas  $bodega_cajas
     * @return \Illuminate\Http\Response
     */
    public function edit(bodega_cajas $bodega_cajas)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Updatebodega_cajasRequest  $request
     * @param  \App\Models\bodega_cajas  $bodega_cajas
     * @return \Illuminate\Http\Response
     */
    public function update(Updatebodega_cajasRequest $request, bodega_cajas $bodega_cajas)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\bodega_cajas  $bodega_cajas
     * @return \Illuminate\Http\Response
     */
    public function destroy(bodega_cajas $bodega_cajas)
    {
        //
    }
}
