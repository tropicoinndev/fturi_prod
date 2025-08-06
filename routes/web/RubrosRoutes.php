<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RubroController as Rubro; #Usar controllador

Route::middleware(['auth'])->group(function () {
    $path = 'rubros'; #Ruta
    $name = $path;      #Nombre de ruta

    Route::get($path, [Rubro::class, 'index'])
        ->name($name . '.index')
        ->middleware('permission:' . $path . '.index');

    Route::post($path, [Rubro::class, 'search'])
        ->name($name . '.search')
        ->middleware('permission:' . $path . '.index');

    Route::get($path . '/crear', [Rubro::class, 'create'])
        ->name($name . '.create')
        ->middleware('permission:' . $path . '.create');

    Route::post($path . '/store', [Rubro::class, 'store'])
        ->name($name . '.store')
        ->middleware('permission:' . $path . '.create');

    Route::get($path . '/edit/{id}', [Rubro::class, 'edit'])
        ->name($name . '.edit')
        ->middleware('permission:' . $path . '.edit');
   

    Route::post($path . '/update', [Rubro::class, 'update'])
        ->name($name . '.update')
        ->middleware('permission:' . $path . '.edit');

    Route::get($path . '/confirm/{id}', [Rubro::class, 'confirm'])
        ->name($name . '.confirm')
        ->middleware('permission:' . $path . '.delete');

    Route::post($path . '/delete', [Rubro::class, 'destroy'])
        ->name($name . '.delete')
        ->middleware('permission:' . $path . '.delete');

    Route::get($path . '/status/{id}', [Rubro::class, 'status'])
        ->name($name . '.status')
        ->middleware('permission:' . $path . '.status');
});