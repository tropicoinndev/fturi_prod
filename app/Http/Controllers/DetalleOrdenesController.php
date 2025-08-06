<?php

namespace App\Http\Controllers;

use App\Http\Requests\Storedetalle_ordenesRequest;
use App\Http\Requests\Updatedetalle_ordenesRequest;
use App\Models\descuentos;
use App\Models\detalle_ordenes;

#Agregar
use App\Models\ordenes;
use App\Models\servicios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Barryvdh\DomPDF\Facade\Pdf;
use Barryvdh\DomPDF\PDF as DomPDFPDF;

class DetalleOrdenesController extends Controller
{
    private $table = 'detalle_ordenes';

    public function __construct()
    {
        $this->getTh($this->table, 'Detalle ordenes');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $r)
    {
        $ordenes = ordenes::findOrFail(Crypt::decryptString($r->detalleOrdenesId));

        return view('ordenes.prefacturacion', [
            'th' => $this->th['index'],
            'p' => $ordenes,
            'detalleOrdenes' => $this->getDetalleOrden(Crypt::decryptString($ordenes->id)),
            'descuentos' => descuentos::orderBy('descuento', 'ASC')->get(),
            'table' => $this->table,
        ]);
    }

    public function getDetalleOrden($ordenId)
    {
        return detalle_ordenes::with(['servicios', 'descuentos'])->where('ordenes_id', '=', $ordenId)->orderBy('id', 'DESC')->get();
    }

    public function search(Request $request)
    {
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view($this->table . '.create', [
            'th' => $this->th['create'],
            'table' => $this->table,
            'data' => [
                'servicios' => servicios::orderBy('servicio', 'ASC')->get(),
                'ordenes' => ordenes::orderBy('numero_orden', 'ASC')->get()
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Storedetalle_ordenesRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Storedetalle_ordenesRequest $r)
    {
        try {
            if ($r->cantidad <= 0) {
                return response()->json([
                    'msj' => 'La cantidad debe ser mayor a cero para poder agregarse al detalle de orden .',
                    'type' => 'danger'
                ]);
            }
            $precioUnitario = (isset($r->precio_unitario) && ($r->precio_unitario != '')) ? $r->precio_unitario : 0;
            #$precioSugerido = (isset($r->precio_sugerido) && ($r->precio_sugerido != '')) ? $r->precio_sugerido : 0;

            $duplicados = detalle_ordenes::where('servicios_id', '=', $r->servicios_id)
                ->where('ordenes_id',     '=', $r->ordenes_id)
                ->where('precio_unitario', '=', $precioUnitario)
                ->first();

            $p = null;
            if ($duplicados != null) {
                $p           = detalle_ordenes::findOrFail($duplicados->id);
                $p->cantidad = $p->cantidad + $r->cantidad;
            } else {
                $p           = new detalle_ordenes;
                $p->cantidad = $r->cantidad;
            }



            $detalle = $this->calServicio($r->servicios_id, $r->descuentos_id ?? null, $r->precio_unitario ?? null);

            $p->precio_unitario = round($precioUnitario, 2);
            $p->neto            = $detalle['neto'];     #number_format($neto, 4);
            $p->iva             = $detalle['iva'];      #number_format($iva, 4);
            $p->cesc            = $detalle['cesc'];     #number_format($cesc, 4);
            $p->advalorem       = $detalle['advalorem']; #number_format($advalorem, 4);
            $p->propina         = $detalle['propina'];  #number_format($propina, 4);
            $p->descuentos_id   = $r->descuentos_id;
            $p->servicios_id    = $r->servicios_id;
            $p->ordenes_id      = $r->ordenes_id;
            $p->user_creacion_id = Auth::id();
            $p->save();

            return response()->json([
                'msj' => 'Registro guardado correctamente.',
                'type' => 'success',
                'detalleOrdenes' => $this->getDetalleOrden($p->ordenes_id),
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'msj' => 'Error al guardar el registro: ' . $th->getMessage(),
                'type' => 'danger'
            ]);
        }
    }



    public function setDescuento($id, $descuento_id)
    {
        $detalle = detalle_ordenes::where("ordenes_id", $id)->get();
        foreach ($detalle as $d)
            $this->setDescuentoServicio($d, $descuento_id);
    }
    private function setDescuentoServicio($d, $descuento_id)
    {
        $s = servicios::find($d->servicios_id);
        if ($s->precio_unitario == $d->precio_unitario && $s->descuento) {
            $detalle            = $this->calServicio($s->id, $descuento_id);
            $p                  = detalle_ordenes::find($d->id);
            $p->precio_unitario = $d->precio_unitario;
            $p->neto            = $detalle['neto'];     #number_format($neto, 4);
            $p->iva             = $detalle['iva'];      #number_format($iva, 4);
            $p->cesc            = $detalle['cesc'];     #number_format($cesc, 4);
            $p->advalorem       = $detalle['advalorem']; #number_format($advalorem, 4);
            $p->propina         = $detalle['propina'];  #number_format($propina, 4);
            $p->descuentos_id   = $descuento_id;
            return $p->save();
        }
        return true;
    }
    /*
    *   @param  (int) $servicio_id, (int | null) $descuento_id, (int | null) $precio_mod
     *  @return NULL | Array $detalle['descuento'=>(float), 'precio'=>(float), 'neto'=>(float),
     * 'iva'=>(float), 'cesc'=>(float), 'propina'=>(float), 'advalorem'=>(float)]
     *
    */
    public function calServicio($servicio_id, $descuento_id = null, $precio_mod = null, $propina_mod = null)
    {
        $servicio = servicios::find($servicio_id);
        /*
        *  Al no encontrarse el servicio no es calculable el precio,
        *  se debe manejar el retorno NULL como precio invalido
        */
        if ($servicio == null)
            return null;

        $precio = $servicio->precio_unitario;

        /*
        * Validacion de precios modificables
        * Se recibe un precio modificado por el usuario
        */

        if (isset($precio_mod) && $precio_mod != null)
            $precio = $precio_mod; //*despues analizar detalladamente el parametro estaba mal lo llamaba $r->precio_mod cuando el parametro que viene es $r->precio_unitario

        //Variables locales
        $agregados = 1;
        $advalorem = 0;
        $detalle = [];

        //Calculo de descuentos
        $detalle['descuento'] = 0;
        if ($descuento_id != null && $servicio->descuento) {
            $d = descuentos::find($descuento_id);
            if ($d != null) {
                //Calculo de precio menos descuento
                $precio = round($servicio->precio_unitario - ($servicio->precio_unitario * $d->decimales), 4);
                $detalle['descuento'] = round($servicio->precio_unitario * $d->decimales, 4);
            }
        }
        $detalle['precio'] = $precio;

        //Precio incluye IVA
        if ($servicio->iva)
            $agregados += env('iva', 0.13);

        //Precio incluye CESC
        if ($servicio->cesc)
            $agregados += env('cesc', 0.05);

        //Precio incluye Propina

        if ($servicio->propina)
            if (isset($propina_mod)) {
                if ($propina_mod)
                    $agregados += env('propina', 0.10);
            } else
                $agregados += env('propina', 0.10);

        //calcular advalorem
        if ($servicio->advalorem) {
            $precio_neto = round($precio / $agregados, 4);
            $sugerido = round($servicio->sugerido / (1 + env('iva', 0.13)), 4);

            if ($precio_neto > $sugerido)
                $advalorem = round(($precio_neto - $sugerido) *  env('advalorem', 0.05), 4);
        }
        //Calculo de precio neto
        $detalle['neto'] = round(($precio - $advalorem) / $agregados, 4);
        //Calculo de IVA
        $detalle['iva'] = ($servicio->iva) ? round($detalle['neto'] * env('iva', 0.13), 4) : 0;
        //Calculo de CESC
        $detalle['cesc'] = ($servicio->cesc) ? round($detalle['neto'] * env('cesc', 0.05), 4) : 0;
        //Calculo de propina
        $detalle['propina'] = ($servicio->propina) ? round($detalle['neto'] * env('propina', 0.1), 4) : 0;
        //Calculo de Ad-Valorem
        $detalle['advalorem'] =  $advalorem;

        //Retorno de datos
        return $detalle;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\detalle_ordenes  $detalle_ordenes
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $data = detalle_ordenes::with('servicios')->where('id', '=', $id)->get();

            return response()->json([
                'msj' => 'Registro encontrado.',
                'type' => 'success',
                'data' => $data
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'msj' => 'Error al encontrar el registro: ' . $th->getMessage(),
                'type' => 'danger'
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\detalle_ordenes  $detalle_ordenes
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            return view($this->table . '.edit', [
                'th' => $this->th['edit'],
                'p' => detalle_ordenes::findOrFail(Crypt::decryptString($id)),
                'table' => $this->table,
                'data' => [
                    'servicios' => servicios::orderBy('servicio', 'ASC')->get(),
                    'ordenes' => ordenes::orderBy('numero_orden', 'ASC')->get()
                ],
            ]);
        } catch (\Throwable $th) {
            return to_route($this->table . '.index')
                ->with('message', 'Error al encontrar el registro: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Updatedetalle_ordenesRequest  $request
     * @param  \App\Models\detalle_ordenes  $detalle_ordenes
     * @return \Illuminate\Http\Response
     */
    public function update(Updatedetalle_ordenesRequest $request)
    {
        try {
            $p = detalle_ordenes::findOrFail($request->id);

            $p->cantidad = $request->cantidad;
            $p->servicios_id = $request->servicios_id;
            $p->ordenes_id = $request->ordenes_id;
            $p->save();

            return to_route($this->table . '.index')
                ->with('message', 'Registro actualizado correctamente.')
                ->with('type', 'success');
        } catch (\Throwable $th) {
            return to_route($this->table . '.index')
                ->with('message', 'Error al actualizar el registro: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }

    public function confirm($id)
    {
        try {
            return view('confirm', [
                'th' => $this->th['confirm'],
                'p' => detalle_ordenes::findOrFail(Crypt::decryptString($id))
            ]);
        } catch (\Throwable $t) {
            return redirect()->back()
                ->with('message', 'Ocurrio un error (' . $t->getMessage() . ')')
                ->with('type', 'danger');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\detalle_ordenes  $detalle_ordenes
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $r)
    {

        try {
            detalle_ordenes::destroy($r->idEliminarDetalleOrden);

            return redirect()->back()
                ->with('message', 'Registro Eliminado correctamente.')
                ->with('type', 'success');
        } catch (\Throwable $th) {
            return redirect()->back()
                ->with('message', 'Error al eliminar el registro: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }
    /**funcion para incremnetar cantidad si el evento es modificable esta funcion se aplica para eventos */
    public function addCantidadEvento(Request $r)
    {
        try {
            $c = intval($r->cantidad);
            if ($c <= 0) {
                return redirect()->back()->with('message','La cantidad debe ser mayor que cero.')->with('type', 'danger');
            }
            $p = detalle_ordenes::findOrFail($r->detalle);
            if ($p && $p->ordenes && $p->ordenes->comprobante && !$p->ordenes->estado ) {
                return redirect()->back()->with('message', 'La cuenta de orden ya ha sido facturada, no es posible editar la cantidad.')->with('type', 'danger');
            }

            if($c <= $p->cantidad)
            return redirect()->back()->with('message','La cantidad no debe disminuir solo agregar la requerida por modificacion del evento')->with('type','danger');

            $p->cantidad = $c;
            $p->save();

            return redirect()->back()
                ->with('message', 'Registro actualizado correctamente.')
                ->with('type', 'success');
        } catch (\Throwable $th) {
            return redirect()->back()
                ->with('message', 'Error al actualizar el registro: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }

    protected function getPDF(): DomPDFPDF {
        $pdf = PDF::getFacadeRoot();
        $dompdf = $pdf->getDomPDF();
        $dompdf->setHttpContext(stream_context_create([
            'ssl'=>[
                'verify_peer'=>FALSE,
                'verify_peer_name'=>FALSE,
                'allow_self_signed'=>TRUE
            ],
        ]));
        return $pdf;
    }

    public function printDetallePdf($id){
        $orden = ordenes::find(Crypt::decryptString($id));

        $detalleOrdenes = detalle_ordenes::with(['descuentos','servicios','ordenes','user_detalle'])
            ->where('ordenes_id',Crypt::decryptString($id))->get();

        $pdf = $this->getPDF();
        $pdf->loadView($this->table.'.printPdf',[
                'detalleOrdenes'=>$detalleOrdenes,
                'orden'=>$orden,
            ]);
        $pdf->setPaper('letter');
        return $pdf->stream();
    }
}
