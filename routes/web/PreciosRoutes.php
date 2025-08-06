    <?php

    use Illuminate\Support\Facades\Route;
    use App\Http\Controllers\PreciosController as Precios; #Usar controllador
    use App\Http\Middleware\isLoginCaja;

    Route::middleware(['auth'])->group(function () {
        $path = 'precios'; #Ruta
        $name = $path; #Nombre de ruta
        Route::get($path, [Precios::class, 'index'])
            ->name($name . '.index')
            ->middleware('permission:' . $path . '.index');

        #DISEÑO MOVIL
        #Listar precios
        #Recibimos como parametro el id de categorias_precios
        Route::get('/app/precios/{categoria_precios_id?}', [Precios::class, 'getPreciosApp'])
            ->name('precios.app')
            ->middleware('permission:' . $path . '.index');

        Route::post('/app/get/precios/productos', [Precios::class, 'getProductosApp'])
            ->name('precios.getProductosApp')
            ->middleware('permission:' . $path . '.index');
        #New:
        Route::post('app/get/precios/productos2',[Precios::class,'getProductosApp2'])
            ->name($name.'.getProductosApp2')
            ->middleware('permission:'.$path.'.index');
        Route::post('app/get/existencias/precios/productos2',[Precios::class,'getExistenciasProductosApp2'])
            ->name($name.'.getExistenciasProductosApp2')
            ->middleware('permission:'.$path.'.index');

        Route::post('/app/buscar/productos/bodega',[Precios::class,'buscarProductosEnBodega'])
            ->name('precios.buscarProductosEnBodega')
            ->middleware('permission:'.$path.'.index');

        Route::post('/app/precios/add/producto',[Precios::class,'addProductoApp'])
            ->name('precios.addProductoApp')
            ->middleware('permission:'.$path.'.index');
        #---

        Route::post($path, [Precios::class, 'search'])
            ->name($name . '.search')
            ->middleware('permission:' . $path . '.index');

        Route::get($path . '/crear', [Precios::class, 'create'])
            ->name($name . '.create')
            ->middleware('permission:' . $path . '.create');

        Route::post($path . '/store', [Precios::class, 'store'])
            ->name($name . '.store')
            ->middleware('permission:' . $path . '.create');

        Route::get($path . '/show/{id}', [Precios::class, 'show'])
            ->name($name . '.show')
            ->middleware('permission:' . $path . '.index');
        Route::get($path . '/detalle/{id}', [Precios::class, 'detallePrecio'])
            ->name($name . '.detallePrecio')
            ->middleware('permission:' . $path . '.index');

        Route::get($path . '/edit/{id}', [Precios::class, 'edit'])
            ->name($name . '.edit')
            ->middleware('permission:' . $path . '.edit');

        Route::post($path . '/update', [Precios::class, 'update'])
            ->name($name . '.update')
            ->middleware('permission:' . $path . '.edit');

        Route::get($path . '/confirm/{id}', [Precios::class, 'confirm'])
            ->name($name . '.confirm')
            ->middleware('permission:' . $path . '.delete');

        Route::post($path . '/cajas/list', [Precios::class, 'list_cajas'])
            ->name($name . '.list_cajas')
            ->middleware('permission:' . $path . '.create');

        Route::post($path . '/api/store', [Precios::class, 'store_apiPrecio'])
            ->name($name . '.store_apiPrecio')
            ->middleware('permission:' . $path . '.create');
        Route::post($path . '/api/store/producto', [Precios::class, 'store_apiProducto'])
            ->name($name . '.store_apiProducto')
            ->middleware('permission:' . $path . '.create');
        Route::post($path . '/api/store/sugerido', [Precios::class, 'storePrecioSugerido'])
            ->name($name . '.storePrecioSugerido')
            ->middleware('permission:' . $path . '.create');
        Route::post($path . '/api/store/fechas', [Precios::class, 'storeFechas'])
            ->name($name . '.storeFechas')
            ->middleware('permission:' . $path . '.create');

        Route::post($path . '/delete', [Precios::class, 'destroy'])
            ->name($name . '.delete')
            ->middleware('permission:' . $path . '.delete');

        Route::get($path . '/status/{id}', [Precios::class, 'statusPrecios'])
            ->name($name . '.statusPrecios')
            ->middleware('permission:' . $path . '.status');

        Route::get($path . '/statusPreciosIva/{id}', [Precios::class, 'statusPreciosIva'])
            ->name($name . '.statusPreciosIva')
            ->middleware('permission:' . $path . '.status');

        Route::get($path . '/statusPreciosAdvalorem/{id}', [Precios::class, 'statusPreciosAdvalorem'])
            ->name($name . '.statusPreciosAdvalorem')
            ->middleware('permission:' . $path . '.status');
        Route::get($path . '/statusPreciosPropina/{id}', [Precios::class, 'statusPreciosPropina'])
            ->name($name . '.statusPreciosPropina')
            ->middleware('permission:' . $path . '.status');
        Route::get($path . '/statusPreciosDescuentos/{id}', [Precios::class, 'statusPreciosDescuentos'])
            ->name($name . '.statusPreciosDescuentos')
            ->middleware('permission:' . $path . '.status');
        Route::get($path . '/impuestos/{id}', [Precios::class, 'impuestos'])
            ->name($name . '.impuestos')
            ->middleware('permission:' . $path . '.status');

        Route::post($path . '/api/get/producto', [Precios::class, 'apiGetProductos'])
            ->name($name . '.apiGetProductos')
            ->middleware(['permission:' . $path . '.api', isLoginCaja::class]);



        #---RUTAS PARA AGREGAR PRECIOS A CAJAS---
        Route::get($path.'/precio/cajas/index',[Precios::class,'precioCajasIndex'])
            ->name($name.'.precioCajasIndex')
            ->middleware(['permission:'.$path.'.precioCajasIndex']);

        Route::post($path.'/api/get/caja/precios',[Precios::class,'apiGetCajaPrecios'])
            ->name($name.'.apiGetCajaPrecios')
            ->middleware(['permission:'.$path.'.apiGetCajaPrecios']);

        Route::post($path.'/update/caja/precios',[Precios::class,'updateCajaPrecios'])
            ->name($name.'.updateCajaPrecios')
            ->middleware(['permission:'.$path.'.updateCajaPrecios']);
        #----------------------------------------
    });
