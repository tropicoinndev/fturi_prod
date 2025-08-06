<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorelotesRequest;
use App\Http\Requests\UpdatelotesRequest;
use App\Models\compras;
use App\Models\lotes;
use App\Models\productos;
use Carbon\Carbon;
use function PHPUnit\Framework\returnSelf;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class LotesController extends Controller
{
    private $table = 'lotes';

    public function __construct()
    {
        $this->getTh($this->table,'Lotes');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $r)
    {
        $compras = compras::with('relacionProveedores')
            ->with('relacionTipoPagos')
            ->findOrFail(Crypt::decryptString($r->lotesId));

        return view('compras.lotes',[
            'th'   =>$this->th['index'],
            'table'=>$this->table,
            'p'    =>$compras,
            'lotes'=>$this->getLotes(Crypt::decryptString($compras->id)),
        ]);
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
     * @param  \App\Http\Requests\StorelotesRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorelotesRequest $r)
    {
        try{

            // Validar que la cantidad y el precio no sean cero
            if ($r->cantidad <= 0 || $r->precio <= 0) {
                return response([
                    'msj' => 'La cantidad y el precio deben ser mayores a cero.',
                    'type' => 'danger'
                ]);
            }
            if(isset($r->fecha_vencimiento) && $r->fecha_vencimiento != null){
                $fechaVencimiento = Carbon::parse($r->fecha_vencimiento)->format('Y-m-d');

                $duplicados = lotes::where('productos_id', '=', $r->productos_id)
                    ->where('fecha_vencimiento', '=', $fechaVencimiento)
                    ->where('precio', '=', $r->precio)
                    ->where('compras_id', '=', Crypt::decryptString($r->compras_id))
                    ->first();
            }else{

                $duplicados = lotes::where('productos_id', '=', $r->productos_id)
                    ->where('precio', '=', $r->precio)
                    ->where('compras_id', '=', Crypt::decryptString($r->compras_id))
                    ->first();
            }


            $p = null;
            if($duplicados != null){
                $p           = lotes::findOrFail($duplicados->id);
                $p->cantidad = $p->cantidad + $r->cantidad;
            }
            else{
                $p           = new lotes;
                $p->cantidad = $r->cantidad;
            }
            #Calcular IVA, Neto y Retencion.
            #Son requeridos los parametros:
            #$detalle = $this->calProducto($r->productos_id, $p->cantidad, $r->precio);

            #$detalle = $this->calProducto($r->productos_id, $p->cantidad, $r->precio);

            $p->compras_id        = Crypt::decryptString($r->compras_id);
            $p->precio            = $r->precio;
            $p->iva               = $r->iva;
            $p->total             = $r->total;
            $p->retencion         = $r->retencion;
            $p->productos_id      = $r->productos_id;
            $p->fecha_vencimiento = $fechaVencimiento ?? null;
            $p->save();

            return response([
                'msj'=>'Registro guardado correctamente.',
                'type'=>'success',
                'lotes'=>$this->getLotes($p->compras_id),
            ]);
        }
        catch(\Throwable $th){
            return response([
                'msj'=>'Error al guardar el registro: '.$th->getMessage(),
                'type'=>'danger'
            ]);
        }
    }

    public function getLotes($compraId){
        return lotes::with('relacionProductos')->where('compras_id','=',$compraId)->orderBy('id','DESC')->get();
    }

    public function calProducto($productoId, $cant, $pre){
        $producto = productos::find($productoId);

        #Al no encontrarse el producto, no es calculable el precio,
        #Se debe manejar el retorno NULL como precio invalido.
        if($producto == null)
            return null;

        $cantidad = $cant;
        $precio   = $pre;

        #Variables locales.
        $agregados = 1;
        $detalle   = [];

        #Precio incluye IVA.
        #if($producto->iva)
            $agregados += env('iva',0.13);

        #Precio incluye Retencion.
        #if($producto->retencion)
            $agregados += env('retencion',0.01);

        #Calculo de Precio neto.
        $detalle['neto'] = round(($cantidad * $precio) / $agregados, 4);
        #Calculo de IVA.
        $detalle['iva'] = round($detalle['neto'] * env('iva',0.13), 4);
        #Calculo de Retencion.
        $detalle['retencion'] = round($detalle['neto'] * env('retencion',0.01), 4);

        #Retorno de datos.
        return $detalle;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\lotes  $lotes
     * @return \Illuminate\Http\Response
     */
    public function show(lotes $lotes)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\lotes  $lotes
     * @return \Illuminate\Http\Response
     */
    public function edit(lotes $lotes)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatelotesRequest  $request
     * @param  \App\Models\lotes  $lotes
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatelotesRequest $request, lotes $lotes)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\lotes  $lotes
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $r)
    {
        try{
            lotes::destroy($r->idEliminarLote);

            return redirect()->back()
                ->with('message','Registro eliminado correctamente.')
                ->with('type','success');
        }
        catch(\Throwable $th){
            return redirect()->back()
                ->with('message','Error al eliminar el registro: '.$th->getMessage())
                ->with('type','danger');
        }
    }
}
