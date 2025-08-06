    <?php

    use Illuminate\Support\Facades\Route;
    use App\Http\Controllers\ProductosController as Productos; #Usar controllador

    Route::middleware(['auth'])->group(function () {
        $path = 'productos'; #Ruta
        $name = $path;        #Nombre de ruta

        Route::get($path, [Productos::class, 'index'])
            ->name($name . '.index')
            ->middleware('permission:' . $path . '.index');

        Route::post($path, [Productos::class, 'search'])
            ->name($name . '.search')
            ->middleware('permission:' . $path . '.index');

        Route::post($path.'/api/search/productos',[Productos::class,'apiSearchProductos'])
            ->name($name.'.apiSearchProductos')
            ->middleware('permission:'.$path.'.index');
        Route::post($path.'/api/search/productos2',[Productos::class,'apiSearchProductos2'])
            ->name($name.'.apiSearchProductos2')
            ->middleware('permission:'.$path.'.index');


        Route::get($path . '/crear', [Productos::class, 'create'])
            ->name($name . '.create')
            ->middleware('permission:' . $path . '.create');

        Route::post($path . '/store', [Productos::class, 'store'])
            ->name($name . '.store')
            ->middleware('permission:' . $path . '.create');


        Route::get($path . '/detalle/{id}', [Productos::class, 'detalleProducto'])
            ->name($name . '.detalleProducto')
            ->middleware('permission:' . $path . '.index');

        Route::get($path . '/edit/{id}', [Productos::class, 'edit'])
            ->name($name . '.edit')
            ->middleware('permission:' . $path . '.edit');

        Route::post($path . '/update', [Productos::class, 'update'])
            ->name($name . '.update')
            ->middleware('permission:' . $path . '.edit');

        Route::get($path . '/confirm/{id}', [Productos::class, 'confirm'])
            ->name($name . '.confirm')
            ->middleware('permission:' . $path . '.delete');

        Route::post($path . '/delete', [Productos::class, 'destroy'])
            ->name($name . '.delete')
            ->middleware('permission:' . $path . '.delete');


        Route::get($path . '/status/{id}', [Productos::class, 'status'])
            ->name($name . '.status')
            ->middleware('permission:' . $path . '.status');

    });
