<?php

namespace App\Http\Controllers;

use App\Http\Requests\Storeanticipo_reservacionRequest;
use App\Http\Requests\Updateanticipo_reservacionRequest;
use App\Models\anticipo_reservacion;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Http\Request;

class AnticipoReservacionController extends Controller
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
     * @param  \App\Http\Requests\Storeanticipo_reservacionRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Storeanticipo_reservacionRequest $request)
    {
        //
    }

    public function save($tipo_reservacion, $reservacion_id, $anticipo)
    {
        $p = new anticipo_reservacion;
        $p->tipo_reservacion = $tipo_reservacion;
        $p->reservacion_id = $reservacion_id;
        $p->anticipos_id = $anticipo;
        return $p->save();
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\anticipo_reservacion  $anticipo_reservacion
     * @return \Illuminate\Http\Response
     */
    public function show(anticipo_reservacion $anticipo_reservacion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\anticipo_reservacion  $anticipo_reservacion
     * @return \Illuminate\Http\Response
     */
    public function edit(anticipo_reservacion $anticipo_reservacion)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Updateanticipo_reservacionRequest  $request
     * @param  \App\Models\anticipo_reservacion  $anticipo_reservacion
     * @return \Illuminate\Http\Response
     */
    public function update(Updateanticipo_reservacionRequest $request, anticipo_reservacion $anticipo_reservacion)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\anticipo_reservacion  $anticipo_reservacion
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $r)
    {
        try {
            anticipo_reservacion::destroy(Crypt::decryptString($r->id));
            return redirect()->back()->with('message', 'Se elimino la asignacion del anticipo a esta cuenta');
        } catch (\Throwable $th) {
            return redirect()->back()->with('message', 'Ocurrio un error al eliminar la asignacion del anticipo a esta cuenta, erro:' . $th->getMessage());
        }
    }
    
}
