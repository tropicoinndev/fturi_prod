<?php

use App\Http\Controllers\PagoAnticipadoController as Pago;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    $path = 'pago_anticipados'; #Ruta
    $name = $path; #Nombre de ruta

    Route::get($path, [Pago::class, 'index'])
        ->name($name . '.index')
        ->middleware("permission:" . $path . ".index");

    Route::post($path . '/api/search', [Pago::class, 'apiSearch'])
        ->name($name . '.api_search')
        ->middleware("permission:" . $path . ".index");

    Route::post($path, [Pago::class, 'search'])
        ->name($name . '.search')
        ->middleware("permission:" . $path . ".index");
    Route::get($path . '/resultado/{id}', [Pago::class, 'resultado'])
    ->name($name . '.resultado')
    ->middleware('permission:' . $path . '.create');

    Route::post($path . '/store', [Pago::class, 'store'])
        ->name($name . '.store')
        ->middleware("permission:" . $path . ".create");
    Route::post($path . '/pago/evento', [Pago::class, 'pagoAnticipadoEvento'])
    ->name($name . '.pago')
    ->middleware("permission:" . $path . ".create");

    Route::get($path . '/confirm/{id}', [Pago::class, 'confirm'])
        ->name($name . '.confirm')
        ->middleware("permission:" . $path . ".delete");

    Route::post($path . '/delete', [Pago::class, 'destroy'])
        ->name($name . '.delete')
        ->middleware("permission:" . $path . ".delete");

});
