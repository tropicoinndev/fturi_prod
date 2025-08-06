<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\JobContingenciasController as controller; #Usar controllador

Route::middleware(['auth', 'permission:admin'])->group(function () {
    $path = 'job/contingencias';
    $name = 'job_contingencias';
    Route::post($path . '/desactivar', [controller::class, 'desactivar'])
        ->name($name . '.desactivar');

    Route::post($path . '/store', [controller::class, 'store'])
        ->name($name . '.store');
});
