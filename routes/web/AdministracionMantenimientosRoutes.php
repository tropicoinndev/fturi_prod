<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdministracionMantenimientosController as AdminMantenimientos; #Usar controllador
Route::middleware(['auth'])->group(function () {
    $path = 'administracion/mantenimientos'; #Ruta
    $name = 'administracion_mantenimientos'; #Nombre de ruta
    Route::get($path, [AdminMantenimientos::class, 'index'])
        ->name($name . '.index')
        ->middleware('permission:' . $path . '.index');

    Route::post($path, [AdminMantenimientos::class, 'search'])
        ->name($name . '.search')
        ->middleware('permission:' . $path . '.index');

    Route::get($path . '/crear', [AdminMantenimientos::class, 'create'])
        ->name($name . '.create')
        ->middleware('permission:' . $path . '.create');

    Route::post($path . '/store', [AdminMantenimientos::class, 'store'])
        ->name($name . '.store')
        ->middleware('permission:' . $path . '.create');

    Route::get($path . '/edit/{id}', [AdminMantenimientos::class, 'edit'])
        ->name($name . '.edit')
        ->middleware('permission:' . $path . '.edit');

    Route::post($path . '/update', [AdminMantenimientos::class, 'update'])
        ->name($name . '.update')
        ->middleware('permission:' . $path . '.edit');

    Route::get($path . '/confirm/{id}', [AdminMantenimientos::class, 'confirm'])
        ->name($name . '.confirm')
        ->middleware('permission:' . $path . '.delete');

    Route::post($path . '/delete', [AdminMantenimientos::class, 'destroy'])
        ->name($name . '.delete')
        ->middleware('permission:' . $path . '.delete');
});
