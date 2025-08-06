<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OperacionesReguladasController as controller;

Route::middleware(['auth'])->group(function () {
    $path = 'operaciones/reguladas';
    $name = 'operaciones_reguladas';

    Route::get($path, [controller::class, 'index'])
        ->name($name . '.index');

    Route::post($path, [controller::class, 'search'])
        ->name($name . '.search');

    Route::get($path . '/crear/{id}', [controller::class, 'create'])
        ->name($name . '.create');

    Route::get($path . '/configurar/{id}/{forma_pago}', [controller::class, 'configurar'])
        ->name($name . '.configurar');

    Route::post($path . '/seccion/a', [controller::class, 'seccionA'])
        ->name($name . '.seccionA');


    Route::get($path . '/seccion/a/{id}', [controller::class, 'deleteSeccionA'])
        ->name($name . '.deleteSeccionA');

    Route::get($path . '/seccion/b/{id}', [controller::class, 'deleteSeccionB'])
        ->name($name . '.deleteSeccionB');

    Route::post($path . '/seccion/b', [controller::class, 'seccionB'])
        ->name($name . '.seccionB');

    Route::post($path . '/parteii', [controller::class, 'parteII'])
        ->name($name . '.parteII');

    Route::get($path . '/completar/{id}', [controller::class, 'completar'])
        ->name($name . '.completar');

    Route::get($path . '/descompletar/{id}', [controller::class, 'descompletar'])
        ->name($name . '.descompletar');

    Route::get($path . '/formulario/{id}', [controller::class, 'formulario'])
        ->name($name . '.formulario');

    Route::post($path . '/revision', [controller::class, 'revision'])
        ->name($name . '.revision');

    Route::post($path . '/autorizacion', [controller::class, 'autorizacion'])
        ->name($name . '.autorizacion');

    Route::get($path . '/imprimir/{id}', [controller::class, 'imprimir'])
        ->name($name . '.imprimir');

    Route::get($path . '/excel/{id}', [controller::class, 'excel'])
        ->name($name . '.excel');

    Route::post($path . '/store', [controller::class, 'store'])
        ->name($name . '.store');

    Route::get($path . '/edit/{id}', [controller::class, 'edit'])
        ->name($name . '.edit');

    Route::post($path . '/update', [controller::class, 'update'])
        ->name($name . '.update');
});
