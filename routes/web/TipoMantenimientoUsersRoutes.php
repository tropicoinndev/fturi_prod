<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TipoMantenimientoUsersController as Mcontroller; #Usar controllador

Route::middleware(['auth'])->group(function () {
    $path = 'tipo_mantenimiento_users'; #Ruta
    $name = $path;      #Nombre de ruta
    Route::get($path, [Mcontroller::class, 'index'])
        ->name($name . '.index')
        ->middleware('permission:' . $path . '.index');
    Route::post($path . '/store', [Mcontroller::class, 'store'])
        ->name($name . '.store')
        ->middleware('permission:' . $path . '.create');

    Route::get($path . '/edit/{id}', [Mcontroller::class, 'edit'])
        ->name($name . '.edit')
        ->middleware('permission:' . $path . '.edit');

    Route::post($path . '/update', [Mcontroller::class, 'update'])
        ->name($name . '.update')
        ->middleware('permission:' . $path . '.edit');

    Route::get($path . '/confirm/{id}', [Mcontroller::class, 'confirm'])
        ->name($name . '.confirm')
        ->middleware('permission:' . $path . '.delete');

    Route::post($path . '/delete', [Mcontroller::class, 'destroy'])
        ->name($name . '.delete')
        ->middleware('permission:' . $path . '.delete');
});