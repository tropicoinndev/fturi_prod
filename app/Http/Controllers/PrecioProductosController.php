<?php

namespace App\Http\Controllers;

use App\Http\Requests\Storeprecio_productosRequest;
use App\Http\Requests\Updateprecio_productosRequest;
#Agregar
use App\Models\precio_productos;
use App\Models\precios;
use App\Models\productos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
class PrecioProductosController extends Controller
{
    private $table = 'precio_productos';

    public function __construct()
    {
        $this->getTh($this->table, 'Precio productos');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Storeprecio_productosRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $r)
    {
        try {
            $precio = Crypt::decryptString($r->precio);
            $producto = productos::find($r->productos_id);
            $precioT = precios::find($precio);
            $categoriaToken = $producto->categoria->token;
            $precioToken = $precioT->categorias_precios->token;

            // Mapeo de valores de precioToken a categorías token
            $categoriasPrecioToken = [
                '1102' => ['1201', '1202', '1203'],
                '1103' => ['1202', '1203'],
                '1104' => ['1201', '1202'],
                '1105' => ['1201']
            ];

            // Verificar si el precioToken existe en el mapeo y agregar las categorías token correspondientes
            $categoriaTokens = isset($categoriasPrecioToken[$precioToken]) ? $categoriasPrecioToken[$precioToken] : [];
            $categoriaTokens[] = $categoriaToken;

            if ($categoriaToken == '1201' && $precioToken == '1101') {
                if ($r->descargo < 1) {
                    return response()->json([
                        'message' => 'Debe asignar al menos un producto a descargo al precio',
                        'type' => 'danger',
                        'list' => $this->getProductosPrecios($precio)
                    ]);
                }
            }

            $existeProducto = precio_productos::where('productos_id', $r->productos_id)
                ->where('precios_id', $precio)
                ->first();

            if ($existeProducto != null) {
                $existeProducto->descargo = $r->descargo;
                $existeProducto->save();
                $message = "Se agregó el producto a este precio";
            } else {
                $p = new precio_productos;
                $p->descargo = $r->descargo;
                $p->produccion = $r->produccion ?? true;
                $p->precios_id = $precio;
                $p->productos_id = $r->productos_id;
                $p->save();
                $message = "Producto agregado a este precio";
            }

            return response()->json([
                'message' => $message,
                'type' => 'success',
                'list' => $this->getProductosPrecios($precio)
            ]);

        } catch (\Throwable $th) {
            return response()->json([
                'message' => "Error: Acción no realizada, actualice e intente de nuevo",
                'type' => 'danger',
                'list' => []
            ]);
        }
    }

    private function getProductosPrecios($precio_id)
    {
        return precio_productos::with('productos')->where('precios_id', '=',  $precio_id)->get();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\precio_productos  $precio_productos
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\precio_productos  $precio_productos
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $r)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Updateprecio_productosRequest  $request
     * @param  \App\Models\precio_productos  $precio_productos
     * @return \Illuminate\Http\Response
     */
    public function update(Request $r)
    {

    }
    public function confirm($id)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\precio_productos  $precio_productos
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $r)
    {


    }

    public function destroy_api(Request $r)
    {
        $m = "Se elimino un producto a este precio";
        $t = true;
        $list = [];
        try {
            $precio = precio_productos::find($r->id)->precios_id;
            precio_productos::destroy($r->id);
            $list = $this->getProductosPrecios($precio);
        } catch (\Throwable $th) {
            $t = false;
            $m = "Error: " + $th->getMessage();
        }
        return response()->json([

            'message'   => $m,
            'type'      => $t ? 'success' : 'danger',
            'list'      => $list
        ]);
    }
}
