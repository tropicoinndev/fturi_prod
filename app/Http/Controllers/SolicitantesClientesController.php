<?php

namespace App\Http\Controllers;

use App\Http\Requests\Storesolicitantes_clientesRequest;
use App\Models\solicitantes;
use App\Models\solicitantes_clientes;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Crypt;

class SolicitantesClientesController extends Controller
{


    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Storesolicitantes_clientesRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store($solicitante, $cliente)
    {
        try {
            $solicitante_cliente =  new solicitantes_clientes();
            $solicitante_cliente->solicitantes_id = $solicitante;
            $solicitante_cliente->clientes_id = $cliente;
            $solicitante_cliente->save();
            return $solicitante_cliente;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\solicitantes_clientes  $solicitantes_clientes
     * @return \Illuminate\Http\Response
     */
    public function show(solicitantes_clientes $solicitantes_clientes)
    {
        //
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\solicitantes_clientes  $solicitantes_clientes
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $r)
    {

    }
    public function delete($id)
    {
        try {
            $p = solicitantes::find(Crypt::decryptString($id));
            solicitantes_clientes::where('solicitantes_id', $p->id)->delete();
            $p->delete();

            return redirect()->back()
                ->with('message', 'Registro eliminado con exito: ')
                ->with('type', 'success');
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
