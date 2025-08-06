<?php

use App\Http\Controllers\DetalleOrdenesController as DetalleOrdenes; #Usar controllador
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    $path = 'detalle_ordenes'; #Ruta
    $name = $path; #Nombre de ruta

    Route::get($path . '/{detalleOrdenesId}', [DetalleOrdenes::class, 'index'])
        ->name($name . '.index')
        ->middleware('permission:' . $name . '.index');

    Route::post($path, [DetalleOrdenes::class, 'search'])
        ->name($name . '.search')
        ->middleware('permission:' . $name . '.index');

    Route::get($path . '/crear', [DetalleOrdenes::class, 'create'])
        ->name($name . '.create')
        ->middleware('permission:' . $name . '.create');

    Route::post($path . '/store', [DetalleOrdenes::class, 'store'])
        ->name($name . '.store')
        ->middleware('permission:' . $name . '.create');

    Route::get($path . '/show/{id}', [DetalleOrdenes::class, 'show'])
        ->name($name . '.show')
        ->middleware('permission:' . $name . '.index');

    Route::get($path . '/edit/{id}', [DetalleOrdenes::class, 'edit'])
        ->name($name . '.edit')
        ->middleware('permission:' . $name . '.edit');

    Route::post($path . '/update', [DetalleOrdenes::class, 'update'])
        ->name($name . '.update')
        ->middleware('permission:' . $name . '.edit');

    Route::get($path . '/confirm/{id}', [DetalleOrdenes::class, 'confirm'])
        ->name($name . '.confirm')
        ->middleware('permission:' . $name . '.delete');

    Route::post($path . '/delete', [DetalleOrdenes::class, 'destroy'])
        ->name($name . '.delete')
        ->middleware('permission:' . $name . '.delete');
    Route::post($path . '/add', [DetalleOrdenes::class, 'addCantidadEvento'])
    ->name($name . '.add_cantidad')
    ->middleware('permission:' . $name . '.edit');

    Route::get($path . '/status/{id}', [DetalleOrdenes::class, 'status'])
        ->name($name . '.status')
        ->middleware('permission:' . $name . '.status');

    #Impresión del detalle de la orden
    Route::get($path.'/print/detalle/pdf/{id}',[DetalleOrdenes::class,'printDetallePdf'])
        ->name($name.'.printDetallePdf')
        ->middleware('permission:'.$name.'.printDetallePdf');
});
