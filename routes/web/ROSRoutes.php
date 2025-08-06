<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RosController as Controller;

Route::middleware(['auth'])->group(function () {
    $path = 'ros';
    $name = 'ros';

    Route::get($path, [Controller::class, 'index'])
        ->name($name . '.index');

    Route::post($path, [Controller::class, 'search'])
        ->name($name . '.search');

    Route::get($path . '/crear', [Controller::class, 'create'])
        ->name($name . '.create');

    Route::post($path . '/store', [Controller::class, 'store'])
        ->name($name . '.store');

    Route::get($path . '/edit/{id}', [Controller::class, 'edit'])
        ->name($name . '.edit');

    Route::post($path . '/update', [Controller::class, 'update'])
        ->name($name . '.update');

    Route::get($path . '/confirm/{id}', [Controller::class, 'confirm'])
        ->name($name . '.confirm');

    Route::post($path . '/delete', [Controller::class, 'destroy'])
        ->name($name . '.delete');

    Route::post($path . '/comprobantes', [Controller::class, 'comprobantes'])
        ->name($name . '.comprobantes');

    Route::get($path . '/print/{id}', [Controller::class, 'print'])
        ->name($name . '.print');
    Route::get($path . '/excel/{id}', [Controller::class, 'excel'])
        ->name($name . '.excel');
});
