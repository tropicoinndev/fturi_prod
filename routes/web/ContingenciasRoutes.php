<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContingenciasController as controller;

Route::middleware(['auth', 'permission:admin'])->group(function () {
    $path = 'contingencias';
    $name = 'contingencias';

    Route::get($path, [controller::class, 'index'])
        ->name($name . '.index');

    Route::post($path . '/search', [controller::class, 'search'])
        ->name($name . '.search');

    Route::post($path . '/store', [controller::class, 'store'])
        ->name($name . '.store');

    Route::get($path . '/edit/{id}', [controller::class, 'edit'])
        ->name($name . '.edit');
    Route::post($path . '/update', [controller::class, 'update'])
        ->name($name . '.update');

    Route::get($path . '/confirm/{id}', [controller::class, 'confirm'])
        ->name($name . '.confirm');
    Route::post($path . '/delete', [controller::class, 'destroy'])
        ->name($name . '.delete');

    Route::get($path . '/status/{id}', [controller::class, 'status'])
        ->name($name . '.status');
});
