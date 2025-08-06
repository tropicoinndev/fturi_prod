<?php

use App\Http\Controllers\OrdenesController as Ordenes; #Usar controllador
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    $path = 'ordenes'; #Ruta
    $name = $path;    #Nombre de ruta

    Route::get($path, [Ordenes::class, 'index'])
        ->name($name . '.index')
        ->middleware('permission:' . $path . '.index');

    Route::post($path, [Ordenes::class, 'search'])
        ->name($name . '.search')
        ->middleware('permission:' . $path . '.index');

    Route::get($path . '/crear', [Ordenes::class, 'create'])
        ->name($name . '.create')
        ->middleware('permission:' . $path . '.create');

    Route::post($path . '/store', [Ordenes::class, 'store'])
        ->name($name . '.store')
        ->middleware('permission:' . $path . '.create');
    Route::post($path . '/evento', [Ordenes::class, 'ordenEvento'])
        ->name($name . '.ordenEvento')
        ->middleware('permission:' . $path . '.create');

    Route::get('/get_clientes', [Ordenes::class, 'getClientes'])
        ->name('get_clientes')
        ->middleware('permission:' . 'index');

    Route::get($path . '/prefacturacion', [Ordenes::class, 'prefacturacion'])
        ->name($name . '.prefacturacion')
        ->middleware('permission:' . $path . '.prefacturacion');

    Route::get($path . '/show/{id}', [Ordenes::class, 'show'])
        ->name($name . '.show')
        ->middleware('permission:' . $path . '.index');

    Route::get($path . '/edit/{id}', [Ordenes::class, 'edit'])
        ->name($name . '.edit')
        ->middleware('permission:' . $path . '.edit');

    Route::post($path . '/update', [Ordenes::class, 'update'])
        ->name($name . '.update')
        ->middleware('permission:' . $path . '.edit');

    Route::post($path . '/update/titular', [Ordenes::class, 'updateTitular'])
        ->name($name . '.updateTitular')
        ->middleware('permission:' . $path . '.edit');

    Route::get($path . '/confirm/{id}', [Ordenes::class, 'confirm'])
        ->name($name . '.confirm')
        ->middleware('permission:' . $path . '.delete');

    Route::post($path . '/delete', [Ordenes::class, 'destroy'])
        ->name($name . '.delete')
        ->middleware('permission:' . $path . '.delete');

    Route::post($path . '/status', [Ordenes::class, 'status'])
        ->name($name . '.status')
        ->middleware('permission:' . $path . '.status');

    Route::post($path . '/comprobante', [Ordenes::class, 'comprobante'])
        ->name($name . '.comprobante')
        ->middleware('permission:' . $path . '.comprobante');
    Route::get($path . '/ordenes/historia', [Ordenes::class, 'historialOrdenes'])
        ->name($name . '.historialOrdenes')
        ->middleware('permission:' . $path . '.index');
    Route::post($path . '/estado/comprobante', [Ordenes::class, 'cambiarComprobante'])
        ->name($name . '.cambiarComprobante')
        ->middleware('permission:' . $path . '.index');

    Route::get($path . '/desbloquear/{id}', [Ordenes::class, 'desbloquear'])
        ->name($name . '.desbloquear')
        ->middleware('permission:' . $path . '.desbloquear');

    Route::get($path . '/bloquear/{id}', [Ordenes::class, 'bloquear'])
        ->name($name . '.bloquear')
        ->middleware('permission:' . $path . '.create');
    Route::post($path . '/precios', [Ordenes::class, 'precio'])
        ->name($name . '.precios')
        ->middleware('permission:' . $path . '.precio');
    Route::get($path . '/anular/orden/{id}', [Ordenes::class, 'anularOrden'])
        ->name($name . '.anular_orden')
        ->middleware('permission:' . $path . '.index');

    #Editar descripcion
    Route::post($path . '/editar/descripcion', [Ordenes::class, 'editarDescripcion'])
        ->name($name . '.editarDescripcion')
        ->middleware('permission:' . $path . '.update');

    Route::get($path . '/impresion/{id}', [Ordenes::class, 'impresion'])
        ->name($name . '.container')
        ->middleware('permission:' . $path . '.index');

    Route::get($path . '/reporte', [Ordenes::class, 'reporteForm'])
        ->name($name . '.reporte')
        ->middleware('permission:' . $path . '.reportes');

    Route::post($path . '/reporte/acciones', [Ordenes::class, 'reporteAcciones'])
        ->name($name . '.reporteAcciones')
        ->middleware('permission:' . $path . '.reportes');
});
