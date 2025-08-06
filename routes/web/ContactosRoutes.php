<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactosController as Contactos; #Usar controllador

Route::middleware(['auth'])->group(function () {
    $path = 'contactos'; #Ruta
    $name = $path;      #Nombre de ruta

    Route::get($path, [Contactos::class, 'index'])
        ->name($name . '.index')
        ->middleware('permission:' . $path . '.index');

    Route::post($path, [Contactos::class, 'search'])
        ->name($name . '.search')
        ->middleware('permission:' . $path . '.index');

    Route::get($path . '/crear', [Contactos::class, 'create'])
        ->name($name . '.create')
        ->middleware('permission:' . $path . '.create');

    Route::post($path . '/store', [Contactos::class, 'store'])
        ->name($name . '.store')
        ->middleware('permission:' . $path . '.create');

    Route::get($path . '/edit/{id}', [Contactos::class, 'edit'])
        ->name($name . '.edit')
        ->middleware('permission:' . $path . '.edit');

    Route::post($path . '/update', [Contactos::class, 'update'])
        ->name($name . '.update')
        ->middleware('permission:' . $path . '.edit');

    Route::get($path . '/confirm/{id}', [Contactos::class, 'confirm'])
        ->name($name . '.confirm')
        ->middleware('permission:' . $path . '.delete');

    Route::post($path . '/delete', [Contactos::class, 'destroy'])
        ->name($name . '.delete')
        ->middleware('permission:' . $path . '.delete');

    Route::get($path . '/status/{id}', [Contactos::class, 'status'])
        ->name($name . '.status')
        ->middleware('permission:' . $path . '.status');
});
