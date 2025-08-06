<?php

namespace App\Http\Controllers;

use App\Http\Requests\Storedetalle_contribuyentesRequest;
use App\Http\Requests\Updatedetalle_contribuyentesRequest;
use App\Models\detalle_contribuyentes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class DetalleContribuyentesController extends Controller
{
    private $table = 'detalle_contribuyentes';

    public function __construct(){
        $this->getTh($this->table, 'Detalle contribuyente');
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
     * @param  \App\Http\Requests\Storedetalle_contribuyentesRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Storedetalle_contribuyentesRequest $r)
    {
        try {
            if(isset($r->id)) return $this->update($r);

            $nrc = isset($r->nrc) && is_numeric($r->nrc) ? intval($r->nrc) : null;

            if($nrc !== null && $nrc <= 0)
                return redirect()->back()->with('message','El NRC ingresado no debe ser cero, vuelva a intentarlo.');

            $p = new detalle_contribuyentes;
            $p->juridico    = $r->juridico;
            $p->clientes_id = $r->clientes_id;
            $p->nrc         = $nrc;
            $p->exento = $nrc === null;
            $p->save();
            return redirect()->back()->with('message', 'Se agrego el detalle del cliente contribuyente.');
        } catch (\Throwable $th) {
            return redirect()->back()->with('message', 'Ocurrio un problema al guardar el cliente.' . $th->getMessage())->with('type', 'danger');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\detalle_contribuyentes  $detalle_contribuyentes
     * @return \Illuminate\Http\Response
     */
    public function show(detalle_contribuyentes $detalle_contribuyentes)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\detalle_contribuyentes  $detalle_contribuyentes
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $r){
        return view("detalle_contribuyentes.edit", [
            'p'    =>detalle_contribuyentes::findOrFail(Crypt::decryptString($r->id)),
            'th'   =>$this->th['edit'],
            'table'=>$this->table,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Updatedetalle_contribuyentesRequest  $request
     * @param  \App\Models\detalle_contribuyentes  $detalle_contribuyentes
     * @return \Illuminate\Http\Response
     */
    public function update(Request $r)
    {
        try {
            $nrc = isset($r->nrc) && is_numeric($r->nrc) ? intval($r->nrc) : null;

            if($nrc !== null && $nrc <= 0)
                return redirect()->back()->with('message','El NRC ingresado no debe ser cero, vuelva a intentarlo.')->with('type','danger');

            $p = detalle_contribuyentes::find($r->id);
            $p->juridico = $r->juridico ?? $p->juridico;
            $p->nrc = $nrc;
            $p->exento = $nrc === null;
            $p->save();
            return redirect()->route('clientes.show', ['id' => Crypt::encryptString($p->clientes_id)])->with('message', 'Se agrego el detalle del cliente contribuyente.');
        } catch (\Throwable $th) {
            return redirect()->back()->with('message', 'Ocurrio un problema al guardar el cliente.' . $th->getMessage())->with('type', 'danger');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\detalle_contribuyentes  $detalle_contribuyentes
     * @return \Illuminate\Http\Response
     */
    public function destroy(detalle_contribuyentes $detalle_contribuyentes)
    {
        //
    }
    public function exento(Request $r)
    {
        try {
            $id = Crypt::decryptString($r->id);
            $tipo = Crypt::decryptString($r->tipo);
            $p = detalle_contribuyentes::findOrFail($id);
            $m = "Este cliente ahora ";
            switch ($tipo) {
                case 1:
                    $p->exento = !$p->exento;
                    $p->exento ? $m = $m . 'es exento de todos los impuestos' : $m = $m . 'no es exento';
                    break;

                case 2:
                    $p->exento_iva = !$p->exento_iva;
                    $p->exento_iva ? $m = $m . 'es exento de IVA' : $m = $m . 'no es exento IVA';
                    break;

                case 3:
                    $p->exento_cesc = !$p->exento_cesc;
                    $p->exento_cesc ? $m = $m . 'es exento de CESC' : $m = $m . 'no es exento CESC';
                    break;

                case 4:
                    $p->exento_advalorem = !$p->exento_advalorem;
                    $p->exento_advalorem ? $m = $m . 'es exento de AD-VALOREM' : $m = $m . 'no es exento AD-VALOREM';
                    break;
            }

            if ($p->exento_iva && $p->exento_cesc && $p->exento_advalorem) {
                $p->exento_iva  = false;
                $p->exento_cesc  = false;
                $p->exento_advalorem = false;
                $p->exento = true;
            }
            $p->save();

            return redirect()->back()
                ->with('message', $m)
                ->with('type', 'success');
        } catch (\Throwable $th) {
            return redirect()->back()
                ->with('message', 'Error al cambiar estado: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }
}
