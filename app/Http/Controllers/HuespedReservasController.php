<?php

namespace App\Http\Controllers;

use App\Http\Requests\Storehuesped_reservasRequest;
use App\Http\Requests\Updatehuesped_reservasRequest;
use App\Models\detalle_reservas;
use App\Models\huesped_reservas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class HuespedReservasController extends Controller
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
     * @param  \App\Http\Requests\Storehuesped_reservasRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Storehuesped_reservasRequest $r)
    {
        try {
            $huesped = $r->huespedes_id;
            $detalle_reservas = Crypt::decryptString($r->detalle_reservas_id);
            if ($this->isValidHuesped($huesped, $detalle_reservas)) {
                $p = new huesped_reservas();
                $p->huespedes_id = $huesped;
                $p->detalle_reservas_id = $detalle_reservas;
                $p->save();
                return redirect()->back()->with('message', 'Se agrego un nuevo huesped de esta reservacion');
            } else return redirect()->back()
                ->with('message', 'Este huesped ya fue agregado a esta habitacion')
                ->with('type', 'danger');
        } catch (\Throwable $th) {
            return redirect()->back()->with('message', 'No se pudo agregar el huesped a esta reservacion')->with('type', 'danger');
        }
    }
    public function save($huesped, $detalle_reservas)
    {
        if ($this->isValidHuesped($huesped, $detalle_reservas)) {
            $p = new huesped_reservas;
            $p->huespedes_id = $huesped;
            $p->detalle_reservas_id = $detalle_reservas;
            $p->save();
            return $p;
        }
    }
    private function isValidHuesped($huesped, $detalle_reservas)
    {
        return huesped_reservas::where('huespedes_id', $huesped)->where('detalle_reservas_id', $detalle_reservas)->count() == 0;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\huesped_reservas  $huesped_reservas
     * @return \Illuminate\Http\Response
     */
    public function show(huesped_reservas $huesped_reservas)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\huesped_reservas  $huesped_reservas
     * @return \Illuminate\Http\Response
     */
    public function edit(huesped_reservas $huesped_reservas)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Updatehuesped_reservasRequest  $request
     * @param  \App\Models\huesped_reservas  $huesped_reservas
     * @return \Illuminate\Http\Response
     */
    public function update(Updatehuesped_reservasRequest $request, huesped_reservas $huesped_reservas)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\huesped_reservas  $huesped_reservas
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $r)
    {
        try {
            huesped_reservas::destroy(Crypt::decryptString($r->id));
            return redirect()->back()->with('message', 'Huesped eliminado de esta reservacion');
        } catch (\Throwable $th) {
            return redirect()->back()->with('message', 'No se pudo eliminar el huesped de esta reservacion')->with('type', 'danger');
        }
    }
}
