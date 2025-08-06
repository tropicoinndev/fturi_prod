<?php

use App\Http\Controllers\BodegasController as Bodegas; #Usar controllador

use App\Http\Middleware\isLoginBodega;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    $ruta       = 'bodegas';
    $nombreRuta = $ruta;
    
    #1
    Route::get($ruta.'/my',[Bodegas::class,'my'])
        ->name($nombreRuta.'.my')
        ->middleware('permission:'.$ruta.'.bodega')
        ->middleware(isLoginBodega::class);
    #2
    Route::get($ruta.'/login',[Bodegas::class,'login'])
        ->name($nombreRuta.'.login')
        ->middleware('permission:'.$ruta.'.bodega');
        
    #3
    Route::post($ruta.'/auth',[Bodegas::class,'auth'])
    ->name($nombreRuta.'.auth')
    ->middleware('permission:'.$ruta.'.bodega');

    #4
    Route::get($ruta.'/logout',[Bodegas::class,'logout'])
        ->name($nombreRuta.'.logout')
        ->middleware('permission:'.$ruta.'.bodega');
});

Route::middleware(['auth',isLoginBodega::class])->group(
    function(){
        $ruta       = 'bodegas';
        $nombreRuta = $ruta;

        Route::get($ruta, [Bodegas::class, 'index'])
        ->name($nombreRuta . '.index')
        ->middleware('permission:' . $ruta . '.index');

        Route::post($ruta, [Bodegas::class, 'search'])
            ->name($nombreRuta . '.search')
            ->middleware('permission:' . $ruta . '.index');

        Route::get($ruta . '/api/get/usuarios', [Bodegas::class, 'apiGetUsuarios'])
            ->name($nombreRuta . '.api_get_usuarios')
            ->middleware("permission:" . $ruta . ".index");

        Route::get($ruta . '/api/get/bodegas/usuarios/{id}', [Bodegas::class, 'apiGetBodegasUsuarios'])
            ->name($nombreRuta . '.api_get_bodegas_usuarios')
            ->middleware("permission:" . $ruta . ".index");

        Route::get($ruta . '/crear', [Bodegas::class, 'create'])
            ->name($nombreRuta . '.create')
            ->middleware("permission:" . $ruta . ".create");

        Route::post($ruta . '/store', [Bodegas::class, 'store'])
            ->name($nombreRuta . '.store')
            ->middleware('permission:' . $ruta . '.create');

        Route::post($ruta . '/api/store/bodega/usuario', [Bodegas::class, 'apiStoreBodegaUsuario'])
            ->name($nombreRuta . '.api_store_bodega_usuario')
            ->middleware('permission:' . $ruta . '.create');

        Route::get($ruta . '/edit/{id}', [Bodegas::class, 'edit'])
            ->name($nombreRuta . '.edit')
            ->middleware("permission:" . $ruta . ".edit");

        Route::post($ruta . '/update', [Bodegas::class, 'update'])
            ->name($nombreRuta . '.update')
            ->middleware("permission:" . $ruta . ".edit");

        Route::get($ruta . '/show/{id}', [Bodegas::class, 'show'])
            ->name($nombreRuta . '.show')
            ->middleware('permission:' . $ruta . '.index');

        Route::get($ruta . '/confirm/{id}', [Bodegas::class, 'confirm'])
            ->name($nombreRuta . '.confirm')
            ->middleware("permission:" . $ruta . ".confirm");
        Route::post($ruta . '/delete', [Bodegas::class, 'destroy'])
                    ->name($nombreRuta . '.delete')
                    ->middleware('permission:' . $ruta . '.delete');

        Route::post($ruta . '/api/delete/bodega/usuario', [Bodegas::class, 'api_delete_bodega_usuario'])
            ->name($nombreRuta . '.api_delete_bodega_usuario')
            ->middleware("permission:" . $ruta . ".delete");

        Route::post($ruta . '/api/delete/usuario', [Bodegas::class, 'api_delete_usuario'])
            ->name($nombreRuta . '.api_delete_usuario')
            ->middleware('permission:' . $ruta . '.delete');

        Route::get($ruta.'/menu',[Bodegas::class,'menu'])
            ->name($nombreRuta.'.menu')
            ->middleware('permission:'.$ruta.'.bodega');
        Route::get($ruta.'/dashboard',[Bodegas::class,'dashboard'])
            ->name($nombreRuta.'.dashboard')
            ->middleware('permission:'.$ruta.'.bodega');
    }
);
