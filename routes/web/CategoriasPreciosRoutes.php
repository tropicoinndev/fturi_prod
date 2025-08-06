<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoriasPreciosController as Categorias_precios; #Usar controllador

Route::middleware(['auth'])->group(function () {
    $path = 'categorias_precios'; #Ruta
    $name = $path;      #Nombre de ruta

    Route::get($path, [Categorias_precios::class, 'index'])
        ->name($name . '.index')
        ->middleware('permission:' . $path . '.index');

    #DISEÑO MOVIL
    #Listar categorias
    Route::get('/app/categorias/precios',[Categorias_precios::class, 'getCategoriasPreciosApp'])
        ->name('app.categorias.precios')
        ->middleware('permission:' . $path . '.index');
    #---

    Route::post($path, [Categorias_precios::class, 'search'])
        ->name($name . '.search')
        ->middleware('permission:' . $path . '.index');

    Route::get($path . '/crear', [Categorias_precios::class, 'create'])
        ->name($name . '.create')
        ->middleware('permission:' . $path . '.create');

    Route::post($path . '/store', [Categorias_precios::class, 'store'])
        ->name($name . '.store')
        ->middleware('permission:' . $path . '.create');

    Route::get($path . '/edit/{id}', [Categorias_precios::class, 'edit'])
        ->name($name . '.edit')
        ->middleware('permission:' . $path . '.edit');
   

    Route::post($path . '/update', [Categorias_precios::class, 'update'])
        ->name($name . '.update')
        ->middleware('permission:' . $path . '.edit');

    Route::get($path . '/confirm/{id}', [Categorias_precios::class, 'confirm'])
        ->name($name . '.confirm')
        ->middleware('permission:' . $path . '.delete');

    Route::post($path . '/delete', [Categorias_precios::class, 'destroy'])
        ->name($name . '.delete')
        ->middleware('permission:' . $path . '.delete');

    Route::get($path . '/status/{id}', [Categorias_precios::class, 'status'])
        ->name($name . '.status')
        ->middleware('permission:' . $path . '.status');
});