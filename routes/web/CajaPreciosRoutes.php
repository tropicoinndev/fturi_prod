<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CajaPreciosController as CajaPrecios; #Usar controllador

Route::middleware(['auth'])->group(function () {
    $path = 'caja_precios'; #Ruta
    $name = $path;        #Nombre de ruta

    Route::get($path, [CajaPrecios::class, 'index'])
        ->name($name . '.index')
        ->middleware('permission:' . $path . '.index');

    Route::post($path, [CajaPrecios::class, 'search'])
        ->name($name . '.search')
        ->middleware('permission:' . $path . '.index');
    Route::post($path, [CajaPrecios::class, 'login'])
        ->name($name . '.login')
        ->middleware('permission:' . $path . '.login');
    Route::get($path . '/api/list', [CajaPrecios::class, 'index_api'])
        ->name($name . '.index_api')
        ->middleware("permission:" . $path . ".index");
        
    Route::get($path . '/api/precio', [CajaPrecios::class, 'index_precio'])
        ->name($name . '.index_precio')
        ->middleware("permission:" . $path . ".index");
    Route::post($path . '/api/store', [CajaPrecios::class, 'store_api'])
        ->name($name . '.store_api')
        ->middleware("permission:" . $path . ".create");

    Route::get($path . '/crear', [CajaPrecios::class, 'create'])
        ->name($name . '.create')
        ->middleware('permission:' . $path . '.create');

    Route::post($path . '/store', [CajaPrecios::class, 'store'])
        ->name($name . '.store')
        ->middleware('permission:' . $path . '.create');


    

    Route::post($path, [CajaPrecios::class, 'search'])
        ->name($name . '.search')
        ->middleware('permission:' . $path . '.index');

    Route::get($path . '/edit/{id}', [CajaPrecios::class, 'edit'])
        ->name($name . '.edit')
        ->middleware('permission:' . $path . '.edit');

    Route::post($path . '/update', [CajaPrecios::class, 'update'])
        ->name($name . '.update')
        ->middleware('permission:' . $path . '.edit');

    Route::get($path . '/confirm/{id}', [CajaPrecios::class, 'confirm'])
        ->name($name . '.confirm')
        ->middleware('permission:' . $path . '.delete');

    Route::post($path . '/delete', [CajaPrecios::class, 'destroy'])
        ->name($name . '.delete')
        ->middleware('permission:' . $path . '.delete');
    Route::post($path . '/delete/api', [CajaPrecios::class, 'destroy_api'])
        ->name($name . '.destroy_api')
        ->middleware("permission:" . $path . ".delete");
    Route::post($path . '/delete/precio', [CajaPrecios::class, 'destroy_apiPrecio'])
        ->name($name . '.destroy_apiPrecio')
        ->middleware("permission:" . $path . ".delete");

    

    
});
