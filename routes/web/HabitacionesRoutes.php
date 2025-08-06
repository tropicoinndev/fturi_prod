<?php

use App\Http\Controllers\HabitacionesController as Habitaciones; #Usar controllador
use App\Http\Controllers\HabitacionesEstadosController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    $ruta       = 'habitaciones';
    $nombreRuta = $ruta;

    Route::get($ruta, [Habitaciones::class, 'index'])
        ->name($nombreRuta . '.index')
        ->middleware('permission:' . $ruta . '.index');

    Route::post($ruta . '/api/get/habitaciones/disponibles', [Habitaciones::class, 'getHabDisponibles'])
        ->name($nombreRuta . '.api_get_habitaciones_disponibles')
        ->middleware('permission:' . $ruta . '.index');

    Route::post($ruta . '/api/get/habitaciones/fecha_salida/valid', [Habitaciones::class, 'getSalidaValid'])
        ->name($nombreRuta . '.api_get_salidaValid')
        ->middleware('permission:' . $ruta . '.index');


    Route::get($ruta . '/test/disponibles/{fecha_entrada}/{fecha_salida}', [Habitaciones::class, 'getHabDisponibles'])
        ->name($nombreRuta . '.test_disponibles')
        ->middleware('permission:' . $ruta . '.index');

    Route::post($ruta, [Habitaciones::class, 'search'])
        ->name($nombreRuta . '.search')
        ->middleware('permission:' . $ruta . '.index');

    Route::get($ruta . '/crear', [Habitaciones::class, 'create'])
        ->name($nombreRuta . '.create')
        ->middleware("permission:" . $ruta . ".create");

    Route::post($ruta . '/store', [Habitaciones::class, 'store'])
        ->name($nombreRuta . '.store')
        ->middleware('permission:' . $ruta . '.create');

    Route::get($ruta . '/edit/{id}', [Habitaciones::class, 'edit'])
        ->name($nombreRuta . '.edit')
        ->middleware("permission:" . $ruta . ".edit");

    Route::post($ruta . '/update', [Habitaciones::class, 'update'])
        ->name($nombreRuta . '.update')
        ->middleware("permission:" . $ruta . ".edit");

    Route::get($ruta . '/confirm/{id}', [Habitaciones::class, 'confirm'])
        ->name($nombreRuta . '.confirm')
        ->middleware("permission:" . $ruta . ".delete");

    Route::post($ruta . '/delete', [Habitaciones::class, 'destroy'])
        ->name($nombreRuta . '.destroy')
        ->middleware("permission:" . $ruta . ".delete");

    Route::get($ruta . '/status/{id}', [Habitaciones::class, 'status'])
        ->name($nombreRuta . '.status')
        ->middleware('permission:' . $ruta . '.status');
    Route::get($ruta . '/habitacionesd/{id}', [Habitaciones::class, 'getDetalleTarifaByHabitacion'])
        ->name($nombreRuta . '.getDetalleTarifaByHabitacion')
        ->middleware('permission:' . $ruta . '.index');
    Route::get($ruta . '/glorieta/{id}', [Habitaciones::class, 'glorieta'])
        ->name($nombreRuta . '.glorieta')
        ->middleware('permission:' . $ruta . '.status');

    Route::get($ruta . '/cambio/estado', [HabitacionesEstadosController::class, 'index'])
        ->name($nombreRuta . '.cambio_estado')
        ->middleware('permission:' . $ruta . '.cambio_estado');

    Route::get($ruta . '/cambio/estado/crear', [HabitacionesEstadosController::class, 'create'])
        ->name($nombreRuta . '.cambio_estado_crear')
        ->middleware('permission:' . $ruta . '.cambio_estado');

    Route::post($ruta . '/cambio/estado/store', [HabitacionesEstadosController::class, 'store'])
        ->name($nombreRuta . '.cambio_estado_store')
        ->middleware('permission:' . $ruta . '.cambio_estado');

    Route::get($ruta . '/cambio/estado/reenviar/{id}', [HabitacionesEstadosController::class, 'reenviar'])
        ->name($nombreRuta . '.cambio_estado_reenviar')
        ->middleware('permission:' . $ruta . '.cambio_estado');

    Route::post($ruta . '/cambio/estado/confirmacion', [HabitacionesEstadosController::class, 'confirmacion'])
        ->name($nombreRuta . '.cambio_estado_confirmar')
        ->middleware('permission:' . $ruta . '.cambio_estado');
});
