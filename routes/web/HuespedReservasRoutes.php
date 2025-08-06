<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HuespedReservasController as controller; #Usar controllador

Route::middleware(['auth'])->group(function () {
    $path = 'huesped/reservas';
    $name = 'huesped_reservas';

    Route::post($path . '/store', [controller::class, 'store'])
        ->name($name . '.store')
        ->middleware('permission:' . $path . '.create');

    Route::get($path . '/confirm/{id}', [controller::class, 'confirm'])
        ->name($name . '.confirm')
        ->middleware('permission:' . $path . '.delete');

    Route::post($path . '/delete', [controller::class, 'destroy'])
        ->name($name . '.delete')
        ->middleware('permission:' . $path . '.delete');
});
