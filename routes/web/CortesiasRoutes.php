<?php

use App\Http\Controllers\CortesiasController as CortesiasController; #Usar controllador

use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    $ruta = 'cortesias';
    $nombreRuta = $ruta;

    Route::get($ruta, [CortesiasController::class, 'index'])
        ->name($nombreRuta . '.index')
        ->middleware('permission:' . $ruta . '.index');

    Route::post($ruta, [CortesiasController::class, 'search'])
        ->name($nombreRuta . '.search')
        ->middleware('permission:' . $ruta . '.index');

    Route::get($ruta . '/crear', [CortesiasController::class, 'create'])
        ->name($nombreRuta . '.create')
        ->middleware("permission:" . $ruta . ".create");

    Route::post($ruta . '/store', [CortesiasController::class, 'store'])
        ->name($nombreRuta . '.store')
        ->middleware('permission:' . $ruta . '.create');

    Route::get($ruta . '/edit/{id}', [CortesiasController::class, 'edit'])
        ->name($nombreRuta . '.edit')
        ->middleware("permission:" . $ruta . ".edit");

    Route::post($ruta . '/update', [CortesiasController::class, 'update'])
        ->name($nombreRuta . '.update')
        ->middleware("permission:" . $ruta . ".edit");

    Route::get($ruta . '/confirm/{id}', [CortesiasController::class, 'confirm'])
        ->name($nombreRuta . '.confirm')
        ->middleware("permission:" . $ruta . ".delete");

    Route::post($ruta . '/delete', [CortesiasController::class, 'destroy'])
        ->name($nombreRuta . '.delete')
        ->middleware("permission:" . $ruta . ".delete");
    Route::get($ruta . '/status/{id}', [CortesiasController::class, 'status'])
        ->name($nombreRuta . '.status')
        ->middleware("permission:" . $ruta . ".status");

    Route::get($ruta . '/aplicar/{id}/{origen}', [CortesiasController::class, 'aplicar'])
        ->name($nombreRuta . '.aplicar')
        ->middleware("permission:" . $ruta . ".aplicar");

    Route::get($ruta . '/autorizar', [CortesiasController::class, 'autorizar'])
        ->name($nombreRuta . '.autorizar')
        ->middleware("permission:" . $ruta . ".autorizar");

    Route::get($ruta . '/detalle/{id}', [CortesiasController::class, 'detalle'])
        ->name($nombreRuta . '.detalle')
        ->middleware("permission:" . $ruta . ".aplicar");

    Route::post($ruta . '/autorizar/accion', [CortesiasController::class, 'authAccion'])
        ->name($nombreRuta . '.auth_accion')
        ->middleware("permission:" . $ruta . ".autorizar");

    Route::get($ruta . '/comprobante', [CortesiasController::class, 'comprobante'])
        ->name($nombreRuta . '.comprobante')
        ->middleware("permission:" . $ruta . ".comprobante");
    Route::post($ruta . '/cobro', [CortesiasController::class, 'cobro'])
        ->name($nombreRuta . '.cobro')
        ->middleware("permission:" . $ruta . ".comprobante");

    Route::get($ruta . '/correccion', [CortesiasController::class, 'correccion'])
        ->name($nombreRuta . '.correccion')
        ->middleware("permission:" . $ruta . ".create");

    Route::get($ruta . '/precios', [CortesiasController::class, 'precios'])
        ->name($nombreRuta . '.precios')
        ->middleware("permission:" . $ruta . ".precios");
    Route::get($ruta . '/reporte', [CortesiasController::class, 'reporte'])
        ->name($nombreRuta . '.reporte')
        ->middleware("permission:" . $ruta . ".reporte");
    Route::post($ruta . '/reporte', [CortesiasController::class, 'reporteOpcion'])
        ->name($nombreRuta . '.reporte_opcion')
        ->middleware("permission:" . $ruta . ".reporte");
});
