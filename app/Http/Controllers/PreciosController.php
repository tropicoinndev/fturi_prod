<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorepreciosRequest;
use App\Http\Requests\UpdatepreciosRequest;
use App\Models\bodega_cajas;
use App\Models\bodegas;
use App\Models\caja_precios;
use App\Models\cajas;
use App\Models\categorias;
use App\Models\categorias_precios;
use App\Models\comanda_detalles;
use App\Models\existencias;
use App\Models\precio_productos;
use App\Models\precios;
use App\Models\productos;
use App\Models\vprecios5;
use App\Models\vprecios;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

#Add
use Maatwebsite\Excel\Facades\Excel;
use Throwable;

class PreciosController extends Controller
{
    private $table = 'precios';

    public function __construct()
    {
        $this->getTh($this->table, 'Precios');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view($this->table . '.index', [
            'th' => $this->th['create'],
            'showBtnPrecioCajas' => true, #Sirve como bandera para mostrar el boton de precio cajas
            'p' => precios::with(['categorias_precios', 'precio_cajas'])->orderBy('id', 'DESC')->paginate(15),
            'table' => $this->table,
            'data' => [
                'precios' => categorias_precios::orderBy('id', 'DESC')->get(),
            ],
        ]);
    }

    #---MOVIL---
    public function getPreciosApp(Request $r)
    { #Recibe como parametro el id de categorias_precios.
        $catPrecios = [];
        $catPreciosId = null;
        if ($r->categoria_precios_id) {
            $catPreciosId = Crypt::decryptString($r->categoria_precios_id);
        }

        if (isset($r)) {
            $catPrecios = categorias_precios::find($catPreciosId); #Se usa solo para saber el nombre de la categoria seleccionada.
        }

        return view('app.precios', [
            'p'   => precios::with('categorias_precios')->where('categorias_precios_id', $catPreciosId)->orderBy('detalle', 'ASC')->get(),
            'catPreciosId' => $catPreciosId,
            'data' => [
                'categoriaPrecio' => $catPrecios,
                'bodegas'        => bodega_cajas::where('cajas_id', session('caja')->id)->with('bodegas')->get(), #Se usa para saber de que bodega saldran los productos.
            ],
        ]);
    }

    /**
     * API Existencias
     *
     * @param precios_id:Crypt
     * @param bodegas_id:Crypt
     * @return Response:Json
     */
    public function getProductosApp(Request $r)
    {
        try {
            $precios_id = $r->precio_id;
            $bodegas_id = $r->bodega_id;

            //Validacion de parametros
            if ($precios_id <= 0 || $precios_id == null)
                throw new Exception('No se encontró el parámetro: precio');

            if ($bodegas_id <= 0 || $bodegas_id == null)
                throw new Exception('No se encontró el parámetro: bodega');

            #La consulta de abajo se refactorizó en un metodo externo, y recibe como parametro el id del precio seleccionado
            $precios = precios::with('categorias_precios')
                ->where('estado', true)
                ->where('id', $precios_id)
                ->where(function ($q) {
                    $q->whereRaw('(? BETWEEN fecha_inicio and fecha_final)', ['fecha' => date("Y-m-d")])
                        ->orWhere('constante', true);
                })->get();
            return $precios;

            if ($precios->id == null)
                throw new Exception("No se encontró el precio, consulte si el precio está activo o vigente");

            switch ($precios->categorias_precios->token) {
                case 1101: #Productos bajo inventario
                    $producto = precio_productos::where('precios_id', $precios_id)->first();

                    if ($producto->id == null)
                        throw new Exception('Este precio no tiene ningun producto agregado, no puede agregarse porque no se realizara ningun descargo. Elija otro precio.');

                    $list = existencias::with(['bodegas'])->where('productos_id', $producto->id)
                        #->where('existencia','>',$producto->descargo)
                        ->where('bodegas_id', $bodegas_id)
                        ->orderBy('vencimiento', 'ASC')
                        ->first();

                    return response()->json(['list' => $list, 'status' => true]);
                    break;
                case 1102: #Bebidas preparadas
                    throw new Exception('Este tipo de precios aun no se pueden comandar desde este entorno.');
                    break;
                case 1103: #Platos
                    throw new Exception('Este tipo de precios aun no se pueden comandar desde este entorno.');
                    break;
                case 1104: #Combos-promocion
                    throw new Exception('Este tipo de precios aun no se pueden comandar desde este entorno.');
                    break;
                case 1105: #Productos sin existencias
                    break;
                default:
                    throw new Exception('No se encontro el tipo de token para este precio.');
                    break;
            }
        } catch (Exception $th) {
            return response()->json(['list' => null, 'status' => false, 'message' => $th->getMessage()]);
        }
    }
    public function getProductosApp2(Request $r)
    {
        try {
            #Asignacion de datos POST a variables
            $categoriaPreciosId = Crypt::decryptString($r->categoriaPreciosId);
            $bodegasId = intval($r->bodegasId);

            #Validacion de datos
            if ($categoriaPreciosId <= 0 || $categoriaPreciosId === null)
                throw new Exception('No se encontró el parámetro: categoria_precios_id.');
            if ($bodegasId <= 0 || $bodegasId === null)
                throw new Exception('No se encontró el parámetro: bodegas_id.');

            #---Traer todos los precios segun la categoria del precio seleccionada---
            $precios = precios::with('categorias_precios')
                ->where('categorias_precios_id', $categoriaPreciosId)
                ->where('estado', true)
                ->where(function ($q) {
                    $q->whereRaw('(? BETWEEN fecha_inicio and fecha_final)', ['fecha' => date('Y-m-d')])
                        ->orWhere('constante', true);
                })
                ->orderBy('detalle', 'asc')
                ->get();

            if ($precios->isEmpty())
                throw new Exception('No se encontraron precios disponibles para esta categoría, consulte si el precio está activo o vigente.');
            #----------

            return response()->json([
                'status' => true,
                'list' => $precios,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ]);
        }
    }
    public function getExistenciasProductosApp2(Request $r)
    {
        try {
            #Asignacion de datos POST a variables
            $categoriaPreciosId = null;
            if (isset($r->categoriaPreciosId) && $r->categoriaPreciosId != null)
                $categoriaPreciosId = Crypt::decryptString($r->categoriaPreciosId);

            $preciosId = intval($r->preciosId);
            $bodegasId = intval($r->bodegasId);

            #Validacion de datos
            /*if($categoriaPreciosId <= 0 || $categoriaPreciosId === null)
                throw new Exception('No se encontró el parámetro: categoria_precios_id.');*/
            if ($preciosId <= 0 || $preciosId === null)
                throw new Exception('No se encontró el parámetro: precios_id.');
            if ($bodegasId <= 0 || $bodegasId === null)
                throw new Exception('No se encontró el parámetro: bodegas_id.');

            #---Encontrar el precio seleccionado segun su categoria---
            $precio = null;

            if ($categoriaPreciosId === null) {
                $precio = precios::with('categorias_precios')
                    ->where('id', $preciosId)
                    ->where('constante', true)
                    ->where('estado', true)
                    ->first();
            } else {
                $precio = precios::with('categorias_precios')
                    ->where('id', $preciosId)
                    ->where('categorias_precios_id', $categoriaPreciosId)
                    ->where('constante', true)
                    ->where('estado', true)
                    ->first();
            }

            if (!$precio)
                throw new Exception('No se encontró el precio solicitado, consulte si el precio está activo o vigente.');
            #----------

            #---Clasificacion de precios segun su token---
            $producto = null;
            $list = null;

            switch ($precio->categorias_precios->token) {
                case 1101: #Productos bajo inventario
                    $precioProd = precio_productos::with(['precios', 'productos'])->where('precios_id', $precio->id)->first();

                    if (!$precioProd)
                        throw new Exception('Este precio no tiene ningún producto agregado, no puede agregarse porque no se realizará ningún descargo; Elija otro precio.');

                    #La vista SQL 'getprecios', guarda como id principal el id del precio, por eso se hace el filtrado con el metodo find()
                    #$list = vprecios::find($precio->id);
                    $pro = vprecios::find($precio->id);

                    /*if($pro->vencimiento >= Carbon::now()->format('Y-m-d'))
                        throw new Exception('Este producto ya venció.');*/

                    $list = $pro;
                    break;
                case 1102: #Bebidas preparadas
                    break;
                case 1103: #Platos
                    break;
                case 1104: #Combos-promocion
                    break;
                case 1105: #Productos sin existencias (desayunos, cenas, combos, cafes, bebidas preparadas)
                    #La vista SQL 'getpreciossinexistencias' guarda como id principal el id del precio, por eso se hace el filtrado con el metodo find()
                    $list = vprecios5::find($precio->id);
                    break;
                default:
                    throw new Exception('No se encontró el token para este precio.');
            }
            #----------

            return response()->json([
                'status'  => true,
                #'precio'  =>$precio,
                #'producto'=>$producto,
                'list'    => $list,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ]);
        }
    }



    public function getPrecioVigente($precios_id)
    {
        return precios::with('categorias_precios')
            ->where('estado', true)
            ->where('id', $precios_id)
            ->where(function ($q) {
                $q->whereRaw('(? BETWEEN fecha_inicio and fecha_final)', ['fecha' => date("Y-m-d")])
                    ->orWhere('constante', true);
            })
            ->whereIn('id', function ($qq) use ($precios_id) {
                $qq->select('precios_id')
                    ->from('caja_precios')
                    ->where('cajas_id', session('caja')->id);
            })
            ->first();
    }
    public function addProductoApp(Request $r)
    {
        try {
            #VARIABLES DE LA REQUEST SE PUEDEN VALIDAR ANTES DEL TRY CATCH
            $comandasId     = intval(Crypt::decryptString($r->comandas_id)); #Desencriptar id
            $preciosId      = $r->precios_id; #Id no encriptado
            $precio         = floatval($r->precio);
            $turnosId       = session('turno')->id;
            $cantidad       = intval($r->cantidad);
            $observaciones  = $r->observaciones;
            $usersComandaId = auth()->user()->id; #Usuario logueado en la App
            $productosId    = intval($r->productos_id); #Id del producto que se usara para hacer el descargo de la tabla 'existencias'
            $bodegasId      = intval($r->bodegas_id); #Id no encriptado

            #VALIDACION DE PARAMETROS
            if ($comandasId <= 0 || $comandasId == null)
                throw new Exception('No se encontró el parámetro: comanda');

            if ($preciosId <= 0 || $preciosId == null)
                throw new Exception('No se encontró el parámetro: precio_id');

            if ($precio <= 0 || $precio == null)
                throw new Exception('No se encontró el parámetro: precio');

            if ($turnosId <= 0 || $turnosId == null)
                throw new Exception('No se encontró el valor: turno');

            if ($cantidad <= 0 || $cantidad == null)
                throw new Exception('No se encontró el valor: cantidad');

            if ($usersComandaId <= 0 || $usersComandaId == null)
                throw new Exception('No se encontró el valor: users_comanda_id');

            if ($productosId <= 0 || $productosId == null)
                throw new Exception('No se encontró el parámetro: producto');

            if ($bodegasId <= 0 || $bodegasId == null)
                throw new Exception('No se encontró el parámentro: bodega');

            #LOGICA
            #---Verificar si hay existencias de ese producto
            $existencias = existencias::where('productos_id', $productosId)
                ->where('bodegas_id', $bodegasId)
                ->where('existencia', '>', 0)
                ->where('estado', true)
                ->first();

            if ($existencias == null)
                throw new Exception('No hay existencias de ese producto.');
            #---


            #---Verificar si el precio esta activo o vigente dentro de las fechas
            $precios = $this->getPrecioVigente($preciosId);

            if ($precios == null)
                throw new Exception('No se encontró el precio, consulte si el precio esta activo o vigente.');
            #---


            #---Verificar si ese producto esta registrado en la tabla 'precio_productos' y que el campo 'descargo' tiene un valor valido
            $precioProd = precio_productos::where('productos_id', $productosId)
                ->where('produccion', true)
                ->first();

            if ($precioProd == null)
                throw new Exception('No se encontró el registro solicitado.');

            if ($precioProd->descargo <= 0 || $precioProd == null)
                throw new Exception('El campo: (descargo) aún no tiene un valor asignado, por favor agrégue un valor a ese precio.');
            #---


            #---Verificar que la cantidad no exceda al de existencias
            $cantidadADescargar = ($cantidad * $precioProd->descargo);

            if ($cantidad > $cantidadADescargar)
                throw new Exception('La cantidad solicitada excede a la de existencia.');
            #---


            #Hacer descargo del producto en tabla 'existencias'
            $existencias->existencia = ($existencias->existencia - $cantidadADescargar);
            $existencias->save();

            #Agregar el producto en tabla 'comanda_detalles'
            $cd = new comanda_detalles();
            $cd->comandas_id      = $comandasId;
            $cd->precios_id       = $preciosId;
            $cd->precio           = $precio;
            $cd->turnos_id        = $turnosId;
            $cd->cantidad         = $cantidad;
            $cd->observaciones    = $observaciones;
            $cd->users_comanda_id = $usersComandaId;
            $cd->save();

            return response()->json(['status' => true, 'message' => 'Producto agregado correctamente a comanda.'], 200);
        } catch (Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 400);
        }
    }
    #---MOVIL---

    /***api listar cajas disponibles */
    public function list_cajas(Request $r)
    {
        return response()->json(['cajas' => $this->getCajaPrecio(Crypt::decryptString($r->precio))]);
    }

    /**funcion que trae cajaPrecio */
    private function getCajaPrecio($precio)
    {
        return caja_precios::where('precios_id', '=', $precio)->with('cajas')->get();
    }
    /** buscar caja */
    public function apiSearchCaja(Request $r)
    {
        return response()->json([
            'list' => $this->getCajaPrecio(Crypt::decryptString($r->id)),
        ]);
    }
    /**esta funcion es donde se agrega la caja a precio  */
    public function store_apiPrecio(Request $r)
    {
        $messege = '';
        $type = true;
        try {
            $precio = Crypt::decryptString($r->precio);
            $type = $this->setCajasPrecio($precio, $r->caja);
            $messege = $type ? 'Caja agregada a este precio' : 'Error al guardar';
        } catch (\Throwable $th) {
            $messege = 'Error: accion no realizada, actualice e intente de nuevo';
            $type = false;
        }
        return response()->json([
            'message' => $messege,
            'type' => $type ? 'success' : 'danger',
            'cajas' => $this->getCajaPrecio($precio),
        ]);
    }

    /**esta funcion guarda la caja creada para el precio */
    private function setCajasPrecio($precio, $caja)
    {
        try {
            $inPrecio = caja_precios::where('precios_id', $precio)
                ->where('cajas_id', $caja)
                ->count();

            if ($inPrecio >= 1) {
                caja_precios::where('precios_id', $precio)
                    ->where('cajas_id', $caja)
                    ->delete();
            } else {
                $p = new caja_precios();
                $p->precios_id = $precio;
                $p->cajas_id = $caja;

                $p->save();
            }
            return true;
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }
    /**busqueda por detalle de precios */
    public function search(Request $r)
    {
        return view($this->table . '.index', [
            'th' => $this->th['index'],
            'p' => precios::where('detalle', 'ilike', '%' . $r->txtBusqueda . '%')->paginate(200),
            'txtBusqueda' => $r->txtBusqueda,
            'table' => $this->table,
            'data' => [
                'categorias_precios' => categorias_precios::orderBy('categoria', 'ASC')->get(),
            ],
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
            'th' => $this->th['create'],
            'table' => $this->table,
            'data' => [
                'categorias_precios' => categorias_precios::orderBy('categoria', 'ASC')->get(),
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorepreciosRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorepreciosRequest $r)
    {
        try {
            $data = new precios();
            $data->detalle      = $r->detalle;
            $data->categorias_precios_id = $r->categorias_precios_id;
            $data->iva          = isset($r->iva) ? $r->iva : false;
            $data->precio       = $r->precio;
            #$data->sugerido     = isset($r->sugerido) && $r != '' ? $r->sugerido : 0;
            $data->sugerido     = (!$r->advalorem) ? 0 : $r->sugerido;
            $data->advalorem    = isset($r->advalorem) ? $r->advalorem : false;
            $data->propina      = isset($r->propina) ? $r->propina : false;
            $data->descuento    = isset($r->descuento) ? $r->descuento : false;
            $data->constante    = isset($r->constante) ? $r->constante : false;
            $data->fecha_inicio = isset($r->fecha_inicio) ? $r->fecha_inicio : null;
            $data->fecha_final  = isset($r->fecha_final) ? $r->fecha_final : null;
            $data->save();

            return redirect()
                ->route($this->table . '.index')
                ->with('message', 'Registro guardado correctamente: ' . $data->detalle)
                ->with('type', 'success');
        } catch (\Throwable $th) {
            return redirect()
                ->route($this->table . '.index')
                ->with('message', 'Error al guardar el registro: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\precios  $precios
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        /**completed */
        $precio = precios::findOrFail(Crypt::decryptString($id));
        return view($this->table . '.show', [
            'th' => ($this->th['show'] = [
                'title' => $precio->detalle,
                'table' => $this->table,
                'bread' => $this->table . '.show',
            ]),
            'p'       => $precio,
            'table'   => $this->table,
            'cajas'   => cajas::orderBy('caja', 'ASC')->get(),
            'precios' => precios::where('id', $precio->id)->get(),
            'cajasP'  => caja_precios::where('precios_id', '=', $precio->id)->get(),
            'preciosP' => precio_productos::where('precios_id', '=', $precio->id)->get(),
        ]);
    }
    public function detallePrecio(Request $r)
    {
        $precio = precios::findOrFail(Crypt::decryptString($r->id));
        return view($this->table . '.detallePrecio', [
            'th' => ($this->th['detallePrecio'] = [
                'title' => $precio->detalle,
                'table' => $this->table,
                'bread' => $this->table . '.detallePrecio',
            ]),
            'p' => $precio,
            'table' => $this->table,
            'productos' => productos::with('categoria')
                ->orderBy('nombre', 'ASC')
                ->get(),
            'categorias' => categorias::orderBy('categoria', 'ASC')->get(),
            'productosPrecios' => precio_productos::with('productos')
                ->where('precios_id', '=', $precio->id)
                ->get(),
            'precios' => precios::with('categorias_precios')->get(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\precios  $precios
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            return view($this->table . '.edit', [
                'th' => $this->th['edit'],
                'p' => precios::with('categorias_precios')->findOrFail(Crypt::decryptString($id)),
                'table' => $this->table,
                'data' => [
                    'categorias_precios' => categorias_precios::orderBy('categoria', 'ASC')->get(),
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
     * @param  \App\Http\Requests\UpdatepreciosRequest  $request
     * @param  \App\Models\precios  $precios
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatepreciosRequest $r)
    {
        try {
            $p = precios::findOrFail($r->id);
            $p->detalle      = $r->detalle;
            $p->categorias_precios_id = $r->categorias_precios_id;
            $p->iva          = isset($r->iva) ? $r->iva : false;
            $p->fecha_inicio = isset($r->fecha_inicio) ? $r->fecha_inicio : null;
            $p->fecha_final  = isset($r->fecha_final) ? $r->fecha_final : null;
            $p->precio       = $r->precio;
            $p->sugerido     = (!$r->advalorem) ? 0 : $r->sugerido;
            $p->advalorem    = isset($r->advalorem) ? $r->advalorem : false;
            $p->propina      = isset($r->propina) ? $r->propina : false;
            $p->descuento    = isset($r->descuento) ? $r->descuento : false;
            $p->constante    = isset($r->constante) ? $r->constante : false;
            $p->save();

            return to_route($this->table . '.index')
                ->with('message', 'Registro actualizado correctamente: ' . $p->detalle)
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
                'p' => precios::findOrFail(Crypt::decryptString($id)),
            ]);
        } catch (\Throwable $t) {
            return redirect()
                ->back()
                ->with('message', 'Ocurrio un error (' . $t->getMessage() . ')')
                ->with('type', 'danger');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\precios  $precios
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $r)
    {
        try {
            if (!isset($r->id) || empty(trim($r->id))) {
                return redirect()
                    ->route($this->table . '.index')
                    ->with('message', 'Ocurrio un error, el identificador del registro no cumple los requerimientos necesarios.')
                    ->with('type', 'danger');
            }

            $p = precios::findOrFail(Crypt::decryptString($r->id));
            $p->delete();

            return to_route($this->table . '.index')->with('message', 'Registro eliminado con exito: ' . $p->detalle);
        } catch (\Throwable $t) {
            return redirect()
                ->back()
                ->with('message', 'Ocurrio un error (' . $t->getMessage() . ')')
                ->with('type', 'danger');
        }
    }
    public function statusPrecios($id)
    {
        try {
            $p = precios::findOrFail(Crypt::decryptString($id));
            $p->estado = !$p->estado;
            $p->save();

            return redirect()
                ->back()
                ->with('message', 'Se ha modificado el estado ' . $p->detalle)
                ->with('type', 'success');
        } catch (\Throwable $th) {
            return redirect()
                ->route($this->table . '.index')
                ->with('message', 'Error al cambiar estado: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }
    public function statusPreciosIva($id)
    {
        try {
            $p = precios::findOrFail(Crypt::decryptString($id));
            $p->iva = !$p->iva;
            $p->save();

            return redirect()
                ->back()
                ->with('message', 'Se ha modificado estado de iva ' . $p->detalle)
                ->with('type', 'success');
        } catch (\Throwable $th) {
            return redirect()
                ->route($this->table . '.index')
                ->with('message', 'Error al cambiar estado de IVA: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }
    public function statusPreciosPropina($id)
    {
        try {
            $p = precios::findOrFail(Crypt::decryptString($id));
            $p->propina = !$p->propina;
            $p->save();

            return redirect()
                ->back()
                ->with('message', 'Se ha modificado el estado de la propina' . $p->detalle)
                ->with('type', 'success');
        } catch (\Throwable $th) {
            return redirect()
                ->route($this->table . '.index')
                ->with('message', 'Error al cambiar estado de la Propina: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }
    public function statusPreciosDescuentos($id)
    {
        try {
            $p = precios::findOrFail(Crypt::decryptString($id));
            $p->descuento = !$p->descuento;
            $p->save();
            return redirect()
                ->back()
                ->with('message', 'Se ha modificado el estado del descuento ' . $p->detalle)
                ->with('type', 'success');
        } catch (\Throwable $th) {
            return redirect()
                ->route($this->table . '.index')
                ->with('message', 'Error al cambiar estado del descuento: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }
    /**detalle de precios impuestos */
    public function impuestos(Request $r)
    {
        try {
            $id = Crypt::decryptString($r->id);
            $tipo = Crypt::decryptString($r->tipo);
            $p = precios::findOrFail($id);
            $m = 'Este precio ahora ';

            switch ($tipo) {
                case 1:
                    $p->constante = !$p->constante;
                    $m .= $p->constante ? 'Es constante y no posee impuestos' : 'No es constante';
                    // Desactivar los otros casos si están en true
                    $p->iva = false;
                    $p->propina = false;
                    $p->advalorem = false;
                    $p->descuento = false;
                    break;

                case 2:
                    $p->iva = !$p->iva;
                    $m .= $p->iva ? 'Posee IVA' : 'No posee IVA';
                    break;

                case 3:
                    $p->propina = !$p->propina;
                    $m .= $p->propina ? 'Posee propina' : 'No posee propina';
                    break;

                case 4:
                    $p->advalorem = !$p->advalorem;
                    if (!$p->advalorem) #Si se quita el ad-valorem, el precio sugerido debe ser cero
                        $p->sugerido = 0;

                    $m .= $p->advalorem ? 'Posee AD-VALOREM' : 'No posee AD-VALOREM, tambien se elimió el precio sugerido';
                    break;
                case 5:
                    $p->descuento = !$p->descuento;
                    $m .= $p->descuento ? 'Posee descuento' : 'No posee descuento';
                    break;
            }

            $p->save();

            return redirect()
                ->back()
                ->with('message', $m)
                ->with('type', 'success');
        } catch (\Throwable $th) {
            return redirect()
                ->back()
                ->with('message', 'Error al cambiar estado: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }
    public function storePrecioSugerido(Request $request)
    {
        try {
            $precioId = Crypt::decryptString($request->precio);
            $precio = precios::find($precioId);
            if (!$precio) {
                return response()->json(['success' => false, 'message' => 'Precio sugerido no se encontró.'], 400);
            }
            if ($precio->precio <= $request->sugerido) {
                return redirect()
                    ->back()
                    ->with('message', 'El precio sugerido no puede ser mayor al precio debe ser menor .')->with('type', 'danger');
            }
            $precio->sugerido = $request->sugerido;
            $precio->advalorem = isset($request->advalorem) && $request != '' ? $request->advalorem : true;
            $precio->save();
            return redirect()
                ->route('precios.show', ['id' => Crypt::encryptString($precioId)])
                ->with('message', 'Se agregó el precio sugerido correctamente y tambien el impuesto de Ad-valorem.')->with('type', 'success');
        } catch (\Throwable $th) {
            return redirect()
                ->back()
                ->with('message', 'Error al guardar el registro: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }
    public function storeFechas(Request $request)
    {
        try {
            $precioId = Crypt::decryptString($request->precio);
            $precio = precios::find($precioId);
            if (!$precio) {
                return response()->json(['success' => false, 'message' => 'Precio sugerido no se encontró.'], 400);
            }

            $precio->fecha_inicio = $request->fecha_inicio;
            $precio->fecha_final = $request->fecha_final;
            $precio->constante = isset($request->constante) && $request != '' ? $request->constante : false;
            $precio->save();
            return redirect()
                ->route('precios.show', ['id' => Crypt::encryptString($precioId)])
                ->with('message', 'Se agregaron las fecias de inicio y finalizacion correctamente.');
        } catch (\Throwable $th) {
            // Assuming this is a web route response, you can redirect back with an error message
            return redirect()
                ->back()
                ->with('message', 'Error al guardar el registro: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }

    public function apiGetProductos(Request $r)
    {
        try {

            return response()->json([
                'list_1' => $this->getPrecio($r->busqueda, $r->bodega),
                'list_5' => $this->getPrecioSinExistencias($r->busqueda),
                'busqueda' => strtoupper($r->busqueda),
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => $th->getMessage()
            ]);
        }
    }
    public function getPrecio($busqueda, $bodega)
    {
        return vprecios::whereIn('id', function ($q) {
            $q->select('precios_id')
                ->from('caja_precios')
                ->where('cajas_id', session('caja')->id);
        })
            ->where('nombre', 'ilike', '%' . $busqueda . '%')
            ->where('bodegas_id', $bodega)
            ->get();
    }
    public function getPrecioSinExistencias($busqueda)
    {
        return vprecios5::whereIn('id', function ($q) {
            $q->select('precios_id')
                ->from('caja_precios')
                ->where('cajas_id', session('caja')->id);
        })
            ->where('nombre', 'ilike', '%' . $busqueda . '%')
            ->get();
    }

    public function buscarProductosEnBodega(Request $r)
    {
        try {
            $bodegasId = $r->bodegas_id;

            if ($bodegasId <= 0 || $bodegasId == null)
                throw new Exception('No se encontró el parámetro: bodega.');

            $list = existencias::with(['bodegas', 'productosExistencias'])
                ->where('bodegas_id', $bodegasId)
                ->orderBy('vencimiento', 'ASC')
                ->where('estado', true)
                ->get();

            return response()->json(['list' => $list, 'status' => true]);
        } catch (Exception $e) {
            return response()->json(['list' => null, 'status' => false, 'message' => $e->getMessage()]);
        }
    }



    #---MÉTODOS PARA AGREGAR PRECIOS A CAJAS---
    public function precioCajasIndex()
    {
        return view($this->table . '.precioCajasIndex', [
            'th'     => $this->th['precioCajas'],
            'precios' => precios::with('categorias_precios')->get(),
            'cajas'  => cajas::orderBy('caja', 'asc')->get(),
        ]);
    }

    public function apiGetCajaPrecios(Request $r)
    {
        try {
            #---Asignacion de datos POST a variables---
            $cajaId = intval(Crypt::decryptString($r->cajaId));

            if ($cajaId <= 0 || $cajaId === null)
                throw new Exception('No se encontró el parámetro: caja id.');
            #------------------------------------------

            #---Encontrar registros solicitados---
            $cajaPrecios = caja_precios::with('precios')->where('cajas_id', $cajaId)->get();

            if ($cajaPrecios->isEmpty())
                throw new Exception('No hay precios agregados a esta caja.');
            #-------------------------------------

            return response()->json([
                'status' => true,
                'cajaPrecios' => $cajaPrecios,
            ]);
        } catch (Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Error: ' . $th->getMessage(),
            ]);
        }
    }

    public function updateCajaPrecios(Request $r)
    {
        try {
            #---Asignacion de datos POST a variables---
            $cajaId = intval(Crypt::decryptString($r->cajaId));
            $precioId = intval($r->precioId);

            if ($cajaId <= 0 || $cajaId === null)
                throw new Exception('No se encontró el parámetro: caja id.');
            if ($precioId <= 0 || $precioId === null)
                throw new Exception('No se encontró el parámetro: precio id.');
            #------------------------------------------

            #---Verificar si ya hay un registro guardado---
            $inPrecio = caja_precios::where('precios_id', $precioId)
                ->where('cajas_id', $cajaId)
                ->count();
            #---------------------------------------------

            if ($inPrecio >= 1) { #Si ya hay uno, se precede a eliminarlo
                caja_precios::where('precios_id', $precioId)
                    ->where('cajas_id', $cajaId)
                    ->delete();
            } else { #De lo contrario, se crea uno nuevo
                $p = new caja_precios();
                $p->precios_id = $precioId;
                $p->cajas_id = $cajaId;

                $p->save();
            }

            return response()->json([
                'status' => true,
                'message' => 'Precio eliminado de esta caja.',
                'cajaPrecios' => caja_precios::with('precios')->where('cajas_id', $cajaId)->get(),
            ]);
        } catch (Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Error: ' . $th->getMessage(),
            ]);
        }
    }
}
