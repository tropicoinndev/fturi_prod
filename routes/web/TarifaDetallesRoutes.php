<?php

use App\Http\Controllers\TarifaDetallesController as TarifaDetalle; #Usar controllador
use Illuminate\Support\Facades\Route;
Route::middleware(['auth'])->group(function () {
    $path = 'tarifa_detalles'; #Ruta
    $name = $path; #Nombre de ruta
    Route::post($path . '/store', [TarifaDetalle::class, 'store'])
        ->name($name . '.store')
        ->middleware('permission:' . $path . '.create');
    Route::post($path . '/delete', [TarifaDetalle::class, 'destroy'])
        ->name($name . '.delete')
        ->middleware('permission:' . $path . '.delete');
    Route::get($path . '/tarifas/{id}', [TarifaDetalle::class, 'getHabitacionesByTarifa'])
        ->name($name . '.getHabitacionesByTarifa')
        ->middleware('permission:' . $path . '.index');
    Route::post($path . '/delete/api', [TarifaDetalle::class, 'destroy_api'])
        ->name($name . '.destroy_api')
        ->middleware('permission:' . $path . '.delete');
});
