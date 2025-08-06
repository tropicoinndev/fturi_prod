<?php

use App\Http\Controllers\RequisicionDetallesController as RequisicionDetalles; #Usar controllador
use App\Http\Middleware\isLoginBodega;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', isLoginBodega::class])->group(function () {
    $path = 'requisicion_detalles';#Ruta
    $name = $path; #Nombre de ruta

    Route::get($path . '/crear', [RequisicionDetalles::class, 'create'])
        ->name($name . '.create')
        ->middleware('permission:' . $name . '.create');
    Route::post($path . '/completar', [RequisicionDetalles::class, 'RequisicionDetalleCompleta'])
        ->name($name . '.RequisicionDetalleCompleta')
        ->middleware('permission:' . $name . '.create');

    Route::post($path . '/store', [RequisicionDetalles::class, 'store'])
        ->name($name . '.store')
        ->middleware('permission:' . $name . '.create');

    Route::get($path . '/show/{id}', [RequisicionDetalles::class, 'show'])
        ->name($name . '.show')
        ->middleware('permission:' . $name . '.index');

    Route::get($path . '/edit/{id}', [RequisicionDetalles::class, 'edit'])
        ->name($name . '.edit')
        ->middleware('permission:' . $name . '.edit');

    Route::post($path . '/update', [RequisicionDetalles::class, 'update'])
        ->name($name . '.update')
        ->middleware('permission:' . $name . '.edit');

    Route::get($path . '/confirm/{id}', [RequisicionDetalles::class, 'confirm'])
        ->name($name . '.confirm')
        ->middleware('permission:' . $name . '.delete');

    Route::post($path . '/delete', [RequisicionDetalles::class, 'destroy'])
        ->name($name . '.delete')
        ->middleware('permission:' . $name . '.delete');
    Route::post($path . '/test', [RequisicionDetalles::class, 'devolverProducto'])
        ->name($name . '.devolverProducto')
        ->middleware('permission:' . $name . '.create');
});
