<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AbonosController as model;
use App\Http\Middleware\isLoginCaja;

Route::middleware(['auth', isLoginCaja::class])->group(function () {
    $path = 'abonos'; #Ruta
    $name = $path;      #Nombre de ruta

    Route::get($path, [model::class, 'index'])
        ->name($name . '.index')
        ->middleware('permission:' . $path . '.index');

    Route::post($path, [model::class, 'search'])
        ->name($name . '.search')
        ->middleware('permission:' . $path . '.index');

    Route::get($path . '/crear', [model::class, 'create'])
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

    Route::get($path . '/print/{id}', [model::class, 'print'])
        ->name($name . '.print')
        ->middleware('permission:' . $path . '.create');

    Route::get($path . '/container/print/{id}', [model::class, 'impresion'])
        ->name($name . '.container')
        ->middleware('permission:' . $path . '.create');
});
