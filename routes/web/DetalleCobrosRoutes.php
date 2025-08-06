<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DetalleCobrosController as model; #Usar controllador

Route::middleware(['auth', 'caja'])->group(function () {
    $path = 'detalle/cobros'; #Ruta
    $name = 'detalle_cobros';

    Route::get($path . '/confirm/{id}', [model::class, 'confirm'])
        ->name($name . '.confirm')
        ->middleware('permission:' . $name . '.delete');

    Route::post($path . '/delete', [model::class, 'destroy'])
        ->name($name . '.delete')
        ->middleware('permission:' . $name . '.delete');
});
