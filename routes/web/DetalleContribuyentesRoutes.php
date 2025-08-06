<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DetalleContribuyentesController as controller; #Usar controllador

Route::middleware(['auth'])->group(function () {
    $path = 'detalle_contribuyentes'; #Ruta
    $name = $path;      #Nombre de ruta

    Route::post($path . '/store', [controller::class, 'store'])
        ->name($name . '.store')
        ->middleware('permission:' . $path . '.create');

    Route::get($path . '/edit/{id}', [controller::class, 'edit'])
        ->name($name . '.edit')
        ->middleware('permission:' . $path . '.edit');

    Route::post($path . '/update', [controller::class, 'update'])
        ->name($name . '.update')
        ->middleware('permission:' . $path . '.edit');

    Route::get($path . '/confirm/{id}', [controller::class, 'confirm'])
        ->name($name . '.confirm')
        ->middleware('permission:' . $path . '.delete');

    Route::post($path . '/delete', [controller::class, 'destroy'])
        ->name($name . '.delete')
        ->middleware('permission:' . $path . '.delete');


    Route::get($path . '/exento/{id}/{tipo}', [controller::class, 'exento'])
        ->name($name . '.exento')
        ->middleware('permission:' . $path . '.status');
});
