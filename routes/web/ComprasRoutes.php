<?php
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\isLoginBodega;
use App\Http\Controllers\ComprasController as Compras;#Usar controllador

Route::middleware(['auth'])->group(function(){
    $ruta       = 'compras';
    $nombreRuta = $ruta;

    Route::get($ruta,[Compras::class,'index'])
        ->name($nombreRuta.'.index')
        ->middleware(isLoginBodega::class);

    Route::post($ruta,[Compras::class,'search'])
        ->name($nombreRuta.'.search')
        ->middleware('permission:'.$ruta.'.index');

    Route::post($ruta .'/api/search/proveedores',[Compras::class,'apiSearchProveedores'])
        ->name($nombreRuta .'.api_search_proveedores')
        ->middleware('permission:'.$ruta.'.index');

    Route::get($ruta .'/historial',[Compras::class,'historialCompras'])
        ->name($nombreRuta .'.historialCompras')
        ->middleware('permission:' . $ruta .'.index');

    Route::post($ruta.'/api/estado/completado',[Compras::class,'apiEstadoCompletado'])
        ->name($nombreRuta.'.api_estado_completado')
        ->middleware('permission:'.$ruta.'.status');

    Route::get($ruta . '/crear', [Compras::class, 'create'])
        ->name($nombreRuta . '.create')
        ->middleware("permission:" . $ruta . ".create");

    Route::post($ruta.'/store',[Compras::class,'store'])
        ->name($nombreRuta.'.store')
        ->middleware('permission:'.$ruta.'.create');

    Route::get($ruta . '/edit/{id}', [Compras::class, 'edit'])
        ->name($nombreRuta . '.edit')
        ->middleware("permission:" . $ruta . ".edit");

    Route::post($ruta . '/update', [Compras::class, 'update'])
        ->name($nombreRuta . '.update')
        ->middleware("permission:" . $ruta . ".edit");

    Route::get($ruta . '/confirm/{id}', [Compras::class, 'confirm'])
        ->name($nombreRuta . '.confirm')
        ->middleware("permission:" . $ruta . ".delete");

    Route::post($ruta . '/delete', [Compras::class, 'destroy'])
        ->name($nombreRuta . '.delete')
        ->middleware("permission:" . $ruta . ".delete");

    Route::post($ruta.'/status',[Compras::class,'status'])
        ->name($nombreRuta.'.status')
        ->middleware('permission:'.$ruta.'.status');
});
