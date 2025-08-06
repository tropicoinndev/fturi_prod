<?php

namespace App\Http\Controllers;
use App\Http\Requests\StoreproductosRequest;
use App\Http\Requests\UpdateproductosRequest;
use App\Models\categorias;
use App\Models\compras;
use App\Models\detalle_productos;
use App\Models\productos;
use Illuminate\Http\Request;

#Agregar.
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Throwable;

class ProductosController extends Controller
{
    private $table = 'productos';

    public function __construct()
    {
        $this->getTh($this->table, 'Productos');
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
            'p' => productos::orderBy('nombre', 'DESC')->paginate(15),
            'table' => $this->table,
            'data' => [
                'categorias' => categorias::orderBy('categoria', 'ASC')->get(),
            ],
        ]);
    }
    /**busqueda por nombre de producto */
    public function search(Request $r)
    {
        return view($this->table . '.index', [
            'th' => $this->th['create'],
            'p' => productos::where('nombre', 'ilike', '%' . $r->txtBusqueda . '%')->paginate(),
            'txtBusqueda' => $r->txtBusqueda,
            'table' => $this->table,

            'data' => [
                'categorias' => categorias::orderBy('categoria', 'ASC')->get(),
                'productos' => productos::orderBy('nombre', 'ASC')->paginate(6),
            ],
        ]);
    }

    /*API PARA BUSQUEDA DE PRODUCTOS CON PARAMETRO*/
    public function apiSearchProductos(Request $r)
    {
        #Se recibe el id de la compra, y se verifica que este activa para permitir la busqueda de los productos.
        $estadoCompra = compras::find(Crypt::decryptString($r->idCompra));

        $productos = $estadoCompra->estado ? productos::where('nombre', 'ilike', '%' . $r->txtBusqueda . '%')->where('estado',true)->get() : [];

        if (count($productos) === 0) {
            return response([
                'productos' => [],
                'mensaje' => 'No se encontraron productos.',
            ]);
        }

        return response([
            'productos' => $productos,
            'txt' => $r->txtBusqueda,
        ]);
    }

    /*API PARA BUSQUEDA DE PRODUCTOS SIN PARAMETROS*/
    public function apiSearchProductos2(Request $r)
    {
        return response([
            'productos' => productos::where('nombre', 'ilike', '%' . $r->txtBusqueda . '%')->get(),
            'txt' => $r->txtBusqueda,
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
                'productos' => productos::orderBy('nombre', 'ASC')->paginate(6),
                'categorias' => categorias::orderBy('categoria', 'ASC')->get(),
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreproductosRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreproductosRequest $request)
    {
        try {
            $data = new productos();
            $data->nombre = $request->nombre;
            $data->categorias_id = $request->categorias_id;
            $data->minimos = $request->minimos;
            $data->maximos = $request->maximos;
            $data->vencimiento = $request->vencimiento;
            $data->save();
            // Obténgo la categoría asociada al nuevo producto
            $categoriaAsociada = $data->categoria;

            // Ahora accedo a cualquier propiedad de la categoría
            $categoriasToken = $categoriaAsociada->token ?? null;
            if ($categoriasToken == 1201) {
                // Redirige a la ruta "create" de  "productos"
                return redirect()
                    ->route('productos.create')
                    ->with('message', 'Registro guardado correctamente:  ' . $data->nombre)
                    ->with('type', 'success');
            } else {
                // Redirige al show de los tokens que requieren detalles
                return redirect()
                    ->route($this->table . '.detalleProducto', ['id' => Crypt::encryptString($data->id)])
                    ->with('message', 'Registro guardado correctamente: ' . $data->nombre)
                    ->with('type', 'success');
            }
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
     * @param  \App\Models\productos  $productos
     * @return \Illuminate\Http\Response
     */
    public function detalleProducto($id)
    {
        $producto = productos::findOrFail(Crypt::decryptString($id));

        return view($this->table . '.detalle', [
            'th' => ($this->th['detalle'] = [
                'title' => $producto->nombre,
                'sub' => 'Detalles ' . $producto->categorias_id ? 'de produccion' : 'de venta-produccion',
                'table' => $this->table,
                'bread' => $this->table . '.detalle',
            ]),
            'p' => $producto,
            'detalle' => detalle_productos::where('productos_id', $producto->id)->first(),
            'table' => $this->table,
            'categorias' => categorias::where('categoria', $producto->id)->get(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\productos  $productos
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            return view($this->table . '.edit', [
                'th' => $this->th['edit'],
                'p' => productos::findOrFail(Crypt::decryptString($id)),
                'table' => $this->table,

                'data' => [
                    'categorias' => categorias::orderBy('categoria', 'ASC')->get(),
                    'productos' => productos::orderBy('nombre', 'ASC')->paginate(6),
                ],
            ]);
        } catch (\Throwable $th) {
            return redirect()
                ->route($this->table . '.index')
                ->with('message', 'Error al encontrar el registro: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateproductosRequest  $request
     * @param  \App\Models\productos  $productos
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateproductosRequest $request)
    {
        try {
            $p = productos::findOrFail($request->id);
            $p->nombre = $request->nombre;
            $p->categorias_id = $request->categorias_id;
            $p->minimos = $request->minimos;
            $p->maximos = $request->maximos;
            $p->vencimiento = $request->vencimiento;
            $p->save();

            return to_route($this->table . '.index')
                ->with('message', 'Registro actualizado correctamente. ' . $p->nombre)
                ->with('type', 'success');
        } catch (\Throwable $th) {
            return redirect()
                ->route($this->table . '.index')
                ->with('message', 'Error al guardar el registro: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\productos  $productos
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
            $pd = Productos::find(Crypt::decryptString($r->id));
            detalle_productos::where('productos_id', $pd->id)->delete();
            $pd->delete();

            return redirect()
                ->route($this->table . '.index')
                ->with('message', 'Registro eliminado con exito');
        } catch (\Throwable $t) {
            return redirect()
                ->back()
                ->with('message', 'Ocurrio un error (' . $t->getMessage() . ')')
                ->with('type', 'danger');
        }
    }
    public function confirm($id)
    {
        try {
            return view('confirm', [
                'th' => $this->th['confirm'],
                'p' => Productos::findOrFail(Crypt::decryptString($id)),
            ]);
        } catch (\Throwable $t) {
            return redirect()
                ->back()
                ->with('message', 'Ocurrio un error (' . $t->getMessage() . ')')
                ->with('type', 'danger');
        }
    }
    public function status($id)
    {
        try {
            $p = productos::findOrFail(Crypt::decryptString($id));
            $p->estado = !$p->estado;
            $p->save();

            return redirect()
                ->back()
                ->with('message', 'Estado modificado correctamente: ' . $p->nombre)
                ->with('type', 'success');
        } catch (\Throwable $th) {
            return redirect()
                ->route($this->table . '.index')
                ->with('message', 'Error al cambiar estado: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }
}
