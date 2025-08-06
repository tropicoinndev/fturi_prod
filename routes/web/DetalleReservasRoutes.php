<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DetalleReservasController as DetalleReservas; #Usar controllador

Route::middleware(['auth'])->group(function () {
    $path = 'detalle/reservas'; #Ruta
    $name = 'detalle_reservas'; #Nombre de ruta

    Route::get($path . '/{id}', [DetalleReservas::class, 'index'])
        ->name($name . '.index')
        ->middleware('permission:' . $name . '.index');

    //Nueva ruta para no perder las fechas.
    Route::get($path . '/{id}/{fecha2Ingreso}/{fecha2Salida}', [DetalleReservas::class, 'index'])
        ->name($name . '.index_rdir')
        ->middleware('permission:' . $name . '.index');

    Route::post($path, [DetalleReservas::class, 'search'])
        ->name($name . '.search')
        ->middleware('permission:' . $name . '.index');

    Route::get($path . '/crear', [DetalleReservas::class, 'create'])
        ->name($name . '.create')
        ->middleware('permission:' . $name . '.create');

    //Guardar las habitaciones reservadas.
    Route::post($path . '/store', [DetalleReservas::class, 'store'])
        ->name($name . '.store')
        ->middleware('permission:' . $name . '.create');

    Route::get($path . '/show/{id}', [DetalleReservas::class, 'show'])
        ->name($name . '.show')
        ->middleware('permission:' . $name . '.index');

    Route::get($path . '/edit/{id}', [DetalleReservas::class, 'edit'])
        ->name($name . '.edit')
        ->middleware('permission:' . $name . '.edit');

    Route::post($path . '/update', [DetalleReservas::class, 'update'])
        ->name($name . '.update')
        ->middleware('permission:' . $name . '.edit');

    Route::get($path . '/confirm/{id}', [DetalleReservas::class, 'confirm'])
        ->name($name . '.confirm')
        ->middleware('permission:' . $name . '.delete');

    Route::post($path . '/delete', [DetalleReservas::class, 'destroy'])
        ->name($name . '.delete')
        ->middleware('permission:' . $name . '.delete');

    Route::get($path . '/status/{id}', [DetalleReservas::class, 'status'])
        ->name($name . '.status')
        ->middleware('permission:' . $name . '.status');

    Route::get('/reservaciones/edicion/tarifas/{id}', [DetalleReservas::class, 'editarTarifa'])
        ->name($name . '.edicionTarifas')
        ->middleware('permission:' . $name . '.create');

    Route::post('/reservaciones/update/tarifas/', [DetalleReservas::class, 'updateTarifa'])
        ->name($name . '.update_tarifa')
        ->middleware('permission:' . $name . '.create');

    #-Editar descripcion-
    Route::get('/reservaciones/edicion/descripcion/{id}', [DetalleReservas::class, 'editarDescripcion'])
        ->name($name . '.editarDescripcion')
        ->middleware('permission:' . $name . '.edit');

    Route::post('/reservaciones/update/descripcion/', [DetalleReservas::class, 'updateDescripcion'])
        ->name($name . '.updateDescripcion')
        ->middleware('permission:' . $name . '.edit');
    #--------------------
});
