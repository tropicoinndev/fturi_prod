<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TipoMantenimientosController as TipoMantenimientos; #Usar controllador
Route::middleware(['auth'])->group(function () {
    $path = 'tipo_mantenimientos'; #Ruta
    $name = $path; #Nombre de ruta
    Route::get($path, [TipoMantenimientos::class, 'index'])
        ->name($name . '.index')
        ->middleware('permission:' . $path . '.index');

    Route::post($path, [TipoMantenimientos::class, 'search'])
        ->name($name . '.search')
        ->middleware('permission:' . $path . '.index');
    Route::get($path . '/api/list', [TipoMantenimientos::class, 'index_api'])
        ->name($name . '.index_api')
        ->middleware('permission:' . $path . '.index');

    Route::get($path . '/crear', [TipoMantenimientos::class, 'create'])
        ->name($name . '.create')
        ->middleware('permission:' . $path . '.create');
    Route::post($path . '/usuarios/list', [TipoMantenimientos::class, 'list_usuarios'])
        ->name($name . '.list_usuarios')
        ->middleware('permission:' . $path . '.create');

    Route::post($path . '/mantenimiento/store', [TipoMantenimientos::class, 'store_apiUsuariosMantenimientos'])
        ->name($name . '.store_apiUsuariosMantenimientos')
        ->middleware('permission:' . $path . '.create');

    Route::post($path . '/store', [TipoMantenimientos::class, 'store'])
        ->name($name . '.store')
        ->middleware('permission:' . $path . '.create');

    Route::get($path . '/edit/{id}', [TipoMantenimientos::class, 'edit'])
        ->name($name . '.edit')
        ->middleware('permission:' . $path . '.edit');

    Route::post($path . '/update', [TipoMantenimientos::class, 'update'])
        ->name($name . '.update')
        ->middleware('permission:' . $path . '.edit');

    Route::get($path . '/confirm/{id}', [TipoMantenimientos::class, 'confirm'])
        ->name($name . '.confirm')
        ->middleware('permission:' . $path . '.delete');
    Route::get($path . '/show/{id}', [TipoMantenimientos::class, 'show'])
        ->name($name . '.show')
        ->middleware('permission:' . $path . '.index');

    Route::post($path . '/delete', [TipoMantenimientos::class, 'destroy'])
        ->name($name . '.delete')
        ->middleware('permission:' . $path . '.delete');
    Route::get($path . '/mantenimiento/{id}', [TipoMantenimientos::class, 'status'])
        ->name($name . '.status')
        ->middleware('permission:' . $path . '.status');
    Route::post($path . '/delete/api', [TipoMantenimientos::class, 'destroy_api'])
        ->name($name . '.destroy_api')
        ->middleware('permission:' . $path . '.delete');
});
