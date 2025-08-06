    <?php

    use Illuminate\Support\Facades\Route;
    use App\Http\Controllers\PrecioProductosController as PrecioProductos; #Usar controllador

    Route::middleware(['auth'])->group(function () {
        $path = 'precio_productos'; #Ruta
        $name = $path;        #Nombre de ruta

        Route::get($path, [PrecioProductos::class, 'index'])
            ->name($name . '.index')
            ->middleware('permission:' . $path . '.index');

        Route::post($path, [PrecioProductos::class, 'search'])
            ->name($name . '.search')
            ->middleware('permission:' . $path . '.index');

        Route::post($path . '/store', [PrecioProductos::class, 'store'])
            ->name($name . '.store')
            ->middleware('permission:' . $path . '.create');
        Route::get($path . '/edit/{id}', [PrecioProductos::class, 'edit'])
            ->name($name . '.edit')
            ->middleware('permission:' . $path . '.edit');

        Route::post($path . '/update', [PrecioProductos::class, 'update'])
            ->name($name . '.update')
            ->middleware('permission:' . $path . '.edit');

        Route::get($path . '/confirm/{id}', [PrecioProductos::class, 'confirm'])
            ->name($name . '.confirm')
            ->middleware('permission:' . $path . '.delete');

        Route::post($path . '/delete', [PrecioProductos::class, 'destroy'])
            ->name($name . '.delete')
            ->middleware('permission:' . $path . '.delete');

        Route::get($path . '/api/descargo', [PrecioProductos::class, 'getDescargo'])
        ->name($name . '.getDescargo')
        ->middleware('permission:' . $path . '.index');
        
        Route::post($path . '/delete/api', [PrecioProductos::class, 'destroy_api'])
        ->name($name . '.destroy_api')
        ->middleware("permission:" . $path . ".delete");

    });
