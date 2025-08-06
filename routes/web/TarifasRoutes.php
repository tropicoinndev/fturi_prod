<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TarifasController as Tarifas; #Usar controllador
Route::middleware(['auth'])->group(function () {
    $path = 'tarifas'; #Ruta
    $name = $path; #Nombre de ruta
    Route::get($path, [Tarifas::class, 'index'])
        ->name($name . '.index')
        ->middleware('permission:' . $path . '.index');

    Route::post($path, [Tarifas::class, 'search'])
        ->name($name . '.search')
        ->middleware('permission:' . $path . '.index');

    Route::get($path . '/crear', [Tarifas::class, 'create'])
        ->name($name . '.create')
        ->middleware('permission:' . $path . '.create');

    Route::post($path . '/store', [Tarifas::class, 'store'])
        ->name($name . '.store')
        ->middleware('permission:' . $path . '.create');

    Route::get($path . '/edit/{id}', [Tarifas::class, 'edit'])
        ->name($name . '.edit')
        ->middleware('permission:' . $path . '.edit');

    Route::post($path . '/update', [Tarifas::class, 'update'])
        ->name($name . '.update')
        ->middleware('permission:' . $path . '.edit');

    Route::get($path . '/confirm/{id}', [Tarifas::class, 'confirm'])
        ->name($name . '.confirm')
        ->middleware('permission:' . $path . '.delete');
    Route::get($path . '/show/{id}', [Tarifas::class, 'show'])
        ->name($name . '.show')
        ->middleware('permission:' . $path . '.index');
    Route::get($path . '/detalle/{id}', [Tarifas::class, 'detalle'])
        ->name($name . '.detalle')
        ->middleware('permission:' . $path . '.index');

    Route::post($path . '/delete', [Tarifas::class, 'destroy'])
        ->name($name . '.delete')
        ->middleware('permission:' . $path . '.delete');
    Route::get($path . '/paquete/{id}', [Tarifas::class, 'statusPaquete'])
        ->name($name . '.statusPaquete')
        ->middleware('permission:' . $path . '.status');
    Route::get($path . '/tarifa/{id}', [Tarifas::class, 'statusTarifas'])
        ->name($name . '.statusTarifas')
        ->middleware('permission:' . $path . '.status');
});
