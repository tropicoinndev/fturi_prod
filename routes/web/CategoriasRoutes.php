<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoriasController as Categorias; #Usar controllador

Route::middleware(['auth'])->group(function () {
    $path = 'categorias'; #Ruta
    $name = $path;      #Nombre de ruta

    Route::get($path, [Categorias::class, 'index'])
        ->name($name . '.index')
        ->middleware('permission:' . $path . '.index');

    Route::post($path, [Categorias::class, 'search'])
        ->name($name . '.search')
        ->middleware('permission:' . $path . '.index');

    Route::get($path . '/crear', [Categorias::class, 'create'])
        ->name($name . '.create')
        ->middleware('permission:' . $path . '.create');

    Route::post($path . '/store', [Categorias::class, 'store'])
        ->name($name . '.store')
        ->middleware('permission:' . $path . '.create');

    Route::get($path . '/edit/{id}', [Categorias::class, 'edit'])
        ->name($name . '.edit')
        ->middleware('permission:' . $path . '.edit');
   

    Route::post($path . '/update', [Categorias::class, 'update'])
        ->name($name . '.update')
        ->middleware('permission:' . $path . '.edit');

    Route::get($path . '/confirm/{id}', [Categorias::class, 'confirm'])
        ->name($name . '.confirm')
        ->middleware('permission:' . $path . '.delete');

    Route::post($path . '/delete', [Categorias::class, 'destroy'])
        ->name($name . '.delete')
        ->middleware('permission:' . $path . '.delete');

    Route::get($path . '/status/{id}', [Categorias::class, 'status'])
        ->name($name . '.status')
        ->middleware('permission:' . $path . '.status');
});