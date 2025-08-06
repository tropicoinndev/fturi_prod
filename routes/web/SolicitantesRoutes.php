<?php

use App\Http\Controllers\SolicitantesClientesController;
use App\Http\Controllers\SolicitantesController as Solicitantes;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    $path = 'solicitantes';
    $name = $path;

    Route::get($path,  [Solicitantes::class, 'index'])
        ->name($name . '.index')
        ->middleware('permission:' . $name . '.index');

    Route::post($path . '/search', [Solicitantes::class, 'search'])
        ->name($name . '.search')
        ->middleware('permission:' . $name . '.search');

    Route::post($path . '/store', [Solicitantes::class, 'store'])
        ->name($name . '.store')
        ->middleware('permission:' . $name . '.create');

    Route::get($path . '/edit/{id}', [Solicitantes::class, 'edit'])
        ->name($name . '.edit')
        ->middleware('permission:' . $name . '.edit');
    Route::post($path . '/update', [Solicitantes::class, 'update'])
        ->name($name . '.update')
        ->middleware('permission:' . $name . '.update');

    Route::get($path . '/confirm/{id}', [Solicitantes::class, 'confirm'])
        ->name($name . '.confirm')
        ->middleware('permission:' . $name . '.delete');

    Route::post($path . '/delete', [Solicitantes::class, 'destroy'])
        ->name($name . '.delete')
        ->middleware('permission:' . $name . '.delete');

    Route::get($path . '/status/{id}', [Solicitantes::class, 'status'])
        ->name($name . '.status')
        ->middleware('permission:' . $name . '.status');
    Route::get($path . '/api/delete/{id}', [SolicitantesClientesController::class, 'delete'])
        ->name($name . '.eliminar')
        ->middleware('permission:' . $path . '.index');
});
