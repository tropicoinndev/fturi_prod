<?php

namespace App\Http\Controllers;
use App\Http\Requests\Storedetalle_productosRequest;
use App\Http\Requests\Updatedetalle_productosRequest;
use App\Models\detalle_productos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;



class DetalleProductosController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */




    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Storedetalle_productosRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Storedetalle_productosRequest $r)
    {
          try {
              if (isset($r->id)) return $this->update($r);
            $p = new detalle_productos;
            $p->medida_ml =$r->medida_ml;
            $p->onzas = $r->onzas;
            $p->perdida_onzas = $r->perdida_onzas;
            $p->productos_id = $r->productos_id;
            $p->save();
                return redirect()->route('productos.detalleProducto', ['id' => Crypt::encryptString($p->productos_id)])->with('message', 'Se agrego el detalle del producto produccion.');
        } catch (\Throwable $th) {
            return redirect()->back()->with('message', 'Ocurrio un problema al guardar el producto.' . $th->getMessage())->with('type', 'danger');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\detalle_productos  $detalle_productos
     * @return \Illuminate\Http\Response
     */


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\detalle_productos  $detalle_productos
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $r)
    {
             return view("detalle_productos.edit", [
            'p' => detalle_productos::findOrFail(Crypt::decryptString($r->id)),
            'th' => [
                'table' => 'detalle_productos'
            ]
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Updatedetalle_productosRequest  $r
     * @param  \App\Models\detalle_productos  $detalle_productos
     * @return \Illuminate\Http\Response
     */
    public function update(Request $r)
    {

                try {
            $p =  detalle_productos::find($r->id);
            $p->medida_ml =$r->medida_ml ?? $p->medida_ml;
            $p->onzas = $r->onzas ??  $p->onzas;
            $p->perdida_onzas = $r->perdida_onzas ?? $p->perdida_onzas ;

            $p->save();

               return redirect()->route('productos.detalleProducto', ['id' => Crypt::encryptString($p->productos_id)])->with('message', 'Se agrego el detalle del producto produccion.');
        } catch (\Throwable $th) {
            return redirect()->back()->with('message', 'Ocurrio un problema al guardar el producto.' . $th->getMessage())->with('type', 'danger');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\detalle_productos  $detalle_productos
     * @return \Illuminate\Http\Response
     */


}
