<?php

namespace App\Http\Controllers;

use App\Http\Requests\Storedetalle_cobrosRequest;
use App\Http\Requests\Updatedetalle_cobrosRequest;
use App\Models\detalle_cobros;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class DetalleCobrosController extends Controller
{
    private $table = 'detalle_cobros';

    public function __construct()
    {
        $this->getTh($this->table, 'Cobros');
    }
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
     * @param  \App\Http\Requests\Storedetalle_cobrosRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Storedetalle_cobrosRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\detalle_cobros  $detalle_cobros
     * @return \Illuminate\Http\Response
     */
    public function show(detalle_cobros $detalle_cobros)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\detalle_cobros  $detalle_cobros
     * @return \Illuminate\Http\Response
     */
    public function edit(detalle_cobros $detalle_cobros)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Updatedetalle_cobrosRequest  $request
     * @param  \App\Models\detalle_cobros  $detalle_cobros
     * @return \Illuminate\Http\Response
     */
    public function update(Updatedetalle_cobrosRequest $request, detalle_cobros $detalle_cobros)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\detalle_cobros  $detalle_cobros
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $r)
    {
        try {
            $p = detalle_cobros::find(Crypt::decryptString($r->id));
            $cobro = $p->cobros_id;
            $p->delete();
            return redirect()->route('cobros.create_config', ['id' => Crypt::encryptString($cobro)])
                ->with('message', 'Se elimino un pago configurado, puede volver a agregarlo si es necesario.');
        } catch (\Throwable $th) {
            return redirect()->back()
                ->with('message', 'Ocurrio un error (' . $th->getMessage() . ')')
                ->with('type', 'danger');
        }
    }
    public function confirm($id)
    {
        try {
            return view("confirm", [
                'th' => $this->th['confirm'],
                'p' => detalle_cobros::findOrFail(Crypt::decryptString($id))
            ]);
        } catch (\Throwable $t) {
            return redirect()->back()
                ->with('message', 'Ocurrio un error (' . $t->getMessage() . ')')
                ->with('type', 'danger');
        }
    }
}
