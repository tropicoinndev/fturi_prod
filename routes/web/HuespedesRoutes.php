<?php

use App\Http\Controllers\HuespedesController as controller; #Usar controllador
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    $path = 'huespedes';
    $name = $path;

    Route::get($path, [controller::class, 'index'])
        ->name($name . '.index')
        ->middleware('permission:' . $path . '.index');

    Route::post($path, [controller::class, 'search'])
        ->name($name . '.search')
        ->middleware('permission:' . $path . '.index');

    Route::get($path . '/crear', [controller::class, 'create'])
        ->name($name . '.create')
        ->middleware('permission:' . $path . '.create');

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

    Route::post($path . '/api/buscar', [controller::class, 'api_buscar'])
        ->name($name . '.api_buscar')
        ->middleware('permission:' . $path . '.buscar');

    Route::get($path . '/status/{id}', [controller::class, 'status'])
        ->name($name . '.status')
        ->middleware('permission:' . $path . '.status');
    Route::get($path . '/bloquear/{id}', [controller::class, 'bloquear'])
        ->name($name . '.bloquear')
        ->middleware('permission:' . $path . '.bloquear');
});
