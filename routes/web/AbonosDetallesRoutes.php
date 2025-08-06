<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AbonosDetallesController as model;
use App\Http\Middleware\isLoginCaja;

Route::middleware(['auth', isLoginCaja::class])->group(function () {
    $path = 'abonos/detalles';
    $name = 'abonos_detalles';

    Route::get($path . '/crear/{id}', [model::class, 'create'])
        ->name($name . '.create')
        ->middleware('permission:' . $path . '.create');

    Route::post($path . '/store', [model::class, 'store'])
        ->name($name . '.store')
        ->middleware('permission:' . $path . '.create');

    Route::get($path . '/confirm/{id}', [model::class, 'confirm'])
        ->name($name . '.confirm')
        ->middleware('permission:' . $path . '.delete');

    Route::post($path . '/delete', [model::class, 'destroy'])
        ->name($name . '.delete')
        ->middleware('permission:' . $path . '.delete');
});
