<?php
use App\Http\Controllers\SonidosController as SonidosController;#Usar controllador

use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function(){
    $ruta = 'sonidos';
    $nombreRuta = $ruta;

    Route::get($ruta. '/sonidos',[SonidosController::class,'index'])
        ->name($nombreRuta.'.index')
        ->middleware('permission:'.$ruta.'.index');

    Route::post($ruta,[SonidosController::class,'search'])
        ->name($nombreRuta.'.search')
        ->middleware('permission:'.$ruta.'.index');

    Route::get($ruta . '/crear', [SonidosController::class, 'create'])
        ->name($nombreRuta . '.create')
        ->middleware("permission:" . $ruta . ".create");

    Route::post($ruta.'/store',[SonidosController::class,'store'])
        ->name($nombreRuta.'.store')
        ->middleware('permission:'.$ruta.'.create');

    Route::get($ruta . '/edit/{id}', [SonidosController::class, 'edit'])
        ->name($nombreRuta . '.edit')
        ->middleware("permission:" . $ruta . ".edit");

    Route::post($ruta . '/update', [SonidosController::class, 'update'])
        ->name($nombreRuta . '.update')
        ->middleware("permission:" . $ruta . ".edit");

    Route::get($ruta . '/confirm/{id}', [SonidosController::class, 'confirm'])
        ->name($nombreRuta . '.confirm')
        ->middleware("permission:" . $ruta . ".delete");

    Route::post($ruta . '/delete', [SonidosController::class, 'destroy'])
        ->name($nombreRuta . '.delete')
        ->middleware("permission:" . $ruta . ".delete");
    Route::get($ruta . '/status/{id}', [SonidosController::class, 'status'])
        ->name($nombreRuta . '.status')
        ->middleware("permission:" . $ruta . ".status");

});
