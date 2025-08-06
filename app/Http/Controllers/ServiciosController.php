<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreserviciosRequest;
use App\Http\Requests\UpdateserviciosRequest;
use App\Models\ordenes;

#Agregar
use App\Models\rubro;
use App\Models\servicios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class ServiciosController extends Controller
{
    private $table = 'servicios';

    public function __construct()
    {
        $this->getTh($this->table, 'Servicios');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view($this->table . '.index', [
            'th'   =>$this->th['index'],
            'p'    =>servicios::orderBy('id', 'DESC')->paginate(15),
            'table'=>$this->table,
            'data' =>[
                'rubros'=>rubro::orderBy('rubro', 'ASC')->where('estado', true)->get()
            ],
        ]);
    }

    public function search(Request $request)
    {
        if (isset($request->txtBusqueda)) {
            $p = servicios::where('servicio', 'like', '%' . $request->txtBusqueda . '%')->paginate();

            return view($this->table . '.index', [
                'th' => $this->th['index'],
                'p' => $p,
                'txtBusqueda' => $request->txtBusqueda,
                'data' => [
                    'rubros' => rubro::orderBy('rubro', 'ASC')->where('estado', true)->get(),
                ],
            ]);
        }
        else {
            return to_route($this->table . '.index');
        }
    }

    public function apiSearch(Request $r)
    {
           $txtBusqueda = strtolower($r->txtBusqueda);

            // recibe el id de la orden y se verifica que esté activa para permitir la búsqueda de los servicios.
            $estadoOrden = ordenes::find($r->idOrden);

            // Realiza la búsqueda de servicios
            $servicios = [];

            if ($estadoOrden->estado) {
                $servicios = servicios::with('rubros')
                    ->whereRaw('LOWER(servicio) like ?', ['%' . $txtBusqueda . '%'])
                    ->get();
            }

            // Verificar si se encontraron servicios
            $mensaje = (count($servicios) === 0) ? 'NO SE ENCONTRO EL SERVICIO .' : null;

            return response()->json([
                'servicios' => $servicios,
                'txt' => $r->txtBusqueda,
                'mensaje' => $mensaje,
            ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view($this->table . '.create', [
            'th'   =>$this->th['create'],
            'table'=>$this->table,
            'p'    =>servicios::all(),
            'data' =>[
                'rubros'=>rubro::orderBy('rubro','ASC')->where('estado',true)->get()
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreserviciosRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreserviciosRequest $request)
    {
        try {
            if ($request->has('sugerido') && $request->sugerido > $request->precio_unitario) {
                return back()
                    ->with('message', 'El precio sugerido no puede ser mayor que el precio unitario.')
                    ->with('type', 'danger');
            }
            $p = new servicios;
            $p->rubros_id       = $request->rubros_id;
            $p->servicio          = $request->servicio;
            $p->precio_unitario   = $request->precio_unitario;
            $p->sugerido          = (isset($request->sugerido) && ($request != '')) ? $request->sugerido : 0;
            $p->iva               = $request->iva;
            $p->cesc              = (isset($request->cesc)) ? $request->cesc : false;
            $p->advalorem         = (isset($request->advalorem)) ? $request->advalorem : false;
            $p->propina           = (isset($request->propina)) ? $request->propina : false;
            $p->descuento         = (isset($request->descuento)) ? $request->descuento : false;
            $p->save();

            return to_route($this->table . '.index')
                ->with('message', 'Registro guardado correctamente: ' . $p->servicio)
                ->with('type', 'success');
        } catch (\Throwable $th) {
            return redirect()->route($this->table . '.index')
                ->with('message', 'Error al guardar el registro: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\servicios  $servicios
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\servicios  $servicios
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try{
            return view($this->table.'.edit',[
                'th'   =>$this->th['edit'],
                'table'=>$this->table,
                'p'    =>servicios::with('rubros')->findOrFail(Crypt::decryptString($id)),
                'data' =>[
                    'rubros'=>rubro::orderBy('rubro','ASC')->where('estado',true)->get(),

                ],
            ]);
        }
        catch(\Throwable $th){
            return to_route($this->table.'.index')
                ->with('message','Error al encontar el registro: '.$th->getMessage())
                ->with('type','danger');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateserviciosRequest  $request
     * @param  \App\Models\servicios  $servicios
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateserviciosRequest $request)
    {
        try {
            if ($request->has('sugerido') && $request->sugerido > $request->precio_unitario) {
                return back()
                    ->with('message', 'El precio sugerido no puede ser mayor que el precio unitario.')
                    ->with('type', 'danger');
            }

            $p = servicios::findOrFail($request->id);
            $p->rubros_id = $request->rubros_id;
            $p->servicio          = $request->servicio;
            $p->precio_unitario   = $request->precio_unitario;
            $p->sugerido          = (!$request->advalorem)? 0 : $request->sugerido;
            $p->iva               = $request->iva;
            $p->cesc              = (isset($request->cesc)) ? $request->cesc : false;
            $p->advalorem         = (isset($request->advalorem)) ? $request->advalorem : false;
            $p->propina           = (isset($request->propina)) ? $request->propina : false;
            $p->descuento         = (isset($request->descuento)) ? $request->descuento : false;
            $p->save();

            return to_route($this->table . '.index')
                ->with('message', 'Registro guardado correctamente: ' . $p->servicio)
                ->with('type', 'success');
        } catch (\Throwable $th) {
            return redirect()->route($this->table . '.index')
                ->with('message', 'Error al guardar el registro: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }

    public function confirm($id)
    {
        try {
            return view('confirm', [
                'th' => $this->th['confirm'],
                'p' => servicios::findOrFail(Crypt::decryptString($id))
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
     * @param  \App\Models\servicios  $servicios
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $r)
    {
        try {
            if (!isset($r->id) || empty(trim($r->id)))
                return redirect()->route($this->table . '.index')
                    ->with('message', 'Ocurrio un error, el identificador del registro no cumple los requerimientos necesarios.')
                    ->with('type', 'danger');

            #servicios::destroy(Crypt::decryptString($r->id));
            $p = servicios::findOrFail(Crypt::decryptString($r->id));
            $p->delete();

            return to_route($this->table . '.index')
                ->with('message', 'Registro eliminado con exito: ' . $p->servicio);
        } catch (\Throwable $t) {
            return redirect()->back()
                ->with('message', 'Ocurrio un error (' . $t->getMessage() . ')')
                ->with('type', 'danger');
        }
    }

    public function status($id)
    {
        try {
            $p = servicios::findOrFail(Crypt::decryptString($id));
            $p->estado = !$p->estado;
            $p->save();

            return to_route($this->table . '.index')
                ->with('message', 'Estado modificado correctamente: ' . $p->servicio)
                ->with('type', 'success');
        } catch (\Throwable $th) {
            return redirect()->route($this->table . '.index')
                ->with('message', 'Error al cambiar estado: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }

    public function statusServiciosIva($id){
        try {
            $p = servicios::findOrFail(Crypt::decryptString($id));
            $p->iva = !$p->iva;
            $p->save();

            return to_route($this->table . '.index')
                ->with('message', 'Se quito el IVA a: ' . $p->servicio)
                ->with('type', 'success');
        } catch (\Throwable $th) {
            return redirect()->route($this->table . '.index')
                ->with('message', 'Error al cambiar estado de IVA: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }

    public function statusServiciosCesc($id){
        try {
            $p = servicios::findOrFail(Crypt::decryptString($id));
            $p->cesc = !$p->cesc;
            $p->save();

            return to_route($this->table . '.index')
                ->with('message', 'Se quito el CESC a: ' . $p->servicio)
                ->with('type', 'success');
        } catch (\Throwable $th) {
            return redirect()->route($this->table . '.index')
                ->with('message', 'Error al cambiar estado de CESC: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }

    public function statusServiciosAdvalorem($id){
        try {
            $p = servicios::findOrFail(Crypt::decryptString($id));
            $p->advalorem = !$p->advalorem;
            $p->save();

            return to_route($this->table . '.index')
                ->with('message', 'Se quito el Ad-valorem a: ' . $p->servicio)
                ->with('type', 'success');
        } catch (\Throwable $th) {
            return redirect()->route($this->table . '.index')
                ->with('message', 'Error al cambiar estado de Ad-valorem: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }

    public function statusServiciosPropina($id){
        try {
            $p = servicios::findOrFail(Crypt::decryptString($id));
            $p->propina = !$p->propina;
            $p->save();

            return to_route($this->table . '.index')
                ->with('message', 'Se quito la Propina a: ' . $p->servicio)
                ->with('type', 'success');
        } catch (\Throwable $th) {
            return redirect()->route($this->table . '.index')
                ->with('message', 'Error al cambiar estado de la Propina: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }

    public function statusServiciosPrecios($id){
        try {
            $p = servicios::findOrFail(Crypt::decryptString($id));
            $p->precios = !$p->precios;
            $p->save();

            return to_route($this->table . '.index')
                ->with('message', 'Bloqueo de precios agregado: ' . $p->servicio)
                ->with('type', 'success');
        } catch (\Throwable $th) {
            return redirect()->route($this->table . '.index')
                ->with('message', 'Error al cambiar estado de los precios: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }
    public function serviciosDescuento($id)
    {
        try {
            $p = servicios::findOrFail(Crypt::decryptString($id));
            $p->descuento = !$p->descuento;
            $p->save();
            $message = $p->descuento
            ? 'Se permite descuento en este servicio: ' . $p->servicio
            : 'ya no se permite descuento en este servicio: ' . $p->servicio;

            // Redirige con el mensaje adecuado
            return to_route($this->table . '.index')
            ->with('message', $message)
            ->with('type', 'success');
        } catch (\Throwable $th) {
            return redirect()->route($this->table . '.index')
            ->with('message', 'Error al cambiar que permita descuento: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }
}
