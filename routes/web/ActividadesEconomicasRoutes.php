<?php

use App\Http\Controllers\ActividadesEconomicasController as ActividadesEconomicasController; #Usar controllador

use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    $ruta = 'actividades_economicas';
    $nombreRuta = $ruta;

    Route::get($ruta, [ActividadesEconomicasController::class, 'index'])
        ->name($nombreRuta . '.index')
        ->middleware('permission:' . $ruta . '.index');

    Route::post($ruta, [ActividadesEconomicasController::class, 'search'])
        ->name($nombreRuta . '.search')
        ->middleware('permission:' . $ruta . '.index');

    Route::get($ruta . '/crear', [ActividadesEconomicasController::class, 'create'])
        ->name($nombreRuta . '.create')
        ->middleware("permission:" . $ruta . ".create");

    Route::post($ruta . '/store', [ActividadesEconomicasController::class, 'store'])
        ->name($nombreRuta . '.store')
        ->middleware('permission:' . $ruta . '.create');

    Route::get($ruta . '/edit/{id}', [ActividadesEconomicasController::class, 'edit'])
        ->name($nombreRuta . '.edit')
        ->middleware("permission:" . $ruta . ".edit");

    Route::post($ruta . '/update', [ActividadesEconomicasController::class, 'update'])
        ->name($nombreRuta . '.update')
        ->middleware("permission:" . $ruta . ".update");

    Route::get($ruta . '/confirm/{id}', [ActividadesEconomicasController::class, 'confirm'])
        ->name($nombreRuta . '.confirm')
        ->middleware("permission:" . $ruta . ".delete");

    Route::post($ruta . '/delete', [ActividadesEconomicasController::class, 'destroy'])
        ->name($nombreRuta . '.delete')
        ->middleware("permission:" . $ruta . ".delete");
});
