<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ComandaDetallesController as model;#Usar controllador
use App\Http\Middleware\isLoginCaja;

Route::middleware(['auth', isLoginCaja::class])->group(function () {
    $path = 'detalle_comandas';#Ruta
    $name = 'detalle_comandas';#Nombre de ruta

    Route::get($path . '/comandas/{comandaId}', [model::class, 'index'])
        ->name($name . '.index')
        ->middleware('permission:' . $name . '.index');

    Route::post($path . '/api/store', [model::class, 'store'])
        ->name($name . '.store')
        ->middleware('permission:' . $name . '.create');

    #-----STORE APP-----
    Route::post($path.'/api/store/app',[model::class,'storeApp'])
        ->name($name.'.storeApp')
        ->middleware('permission:'.$name.'.create');
    #-------------------
        
    Route::post($path . '/api/detalle', [model::class, 'crearDetalle'])
        ->name($name . '.crearDetalle')
        ->middleware('permission:' . $name . '.create');
    Route::get($path . '/comandas/detalle/{comandaId}', [model::class, 'mostrarDetallesComanda'])
        ->name($name . '.detalle')
        ->middleware('permission:' . $name . '.detalle');
});
