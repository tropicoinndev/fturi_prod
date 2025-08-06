<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoriaFotosController as CategoriasFotos; #Usar controllador

Route::middleware(['auth'])->group(function () {
    $path = 'categoria_fotos'; #Ruta
    $name = $path;      #Nombre de ruta

    Route::get($path, [CategoriasFotos::class, 'index'])
        ->name($name . '.index')
        ->middleware('permission:' . $path . '.index');

    Route::post($path, [CategoriasFotos::class, 'search'])
        ->name($name . '.search')
        ->middleware('permission:' . $path . '.index');

    Route::get($path . '/crear', [CategoriasFotos::class, 'create'])
        ->name($name . '.create')
        ->middleware('permission:' . $path . '.create');

    Route::post($path . '/store', [CategoriasFotos::class, 'store'])
        ->name($name . '.store')
        ->middleware('permission:' . $path . '.create');

    Route::get($path . '/edit/{id}', [CategoriasFotos::class, 'edit'])
        ->name($name . '.edit')
        ->middleware('permission:' . $path . '.edit');
   

    Route::post($path . '/update', [CategoriasFotos::class, 'update'])
        ->name($name . '.update')
        ->middleware('permission:' . $path . '.edit');

    Route::get($path . '/confirm/{id}', [CategoriasFotos::class, 'confirm'])
        ->name($name . '.confirm')
        ->middleware('permission:' . $path . '.delete');

    Route::post($path . '/delete', [CategoriasFotos::class, 'destroy'])
        ->name($name . '.delete')
        ->middleware('permission:' . $path . '.delete');

    Route::get($path . '/status/{id}', [CategoriasFotos::class, 'status'])
        ->name($name . '.status')
        ->middleware('permission:' . $path . '.status');
});