<?php

use App\Http\Controllers\RequisicionesController as Requisiciones; #Usar controllador
use App\Http\Middleware\isLoginBodega;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', isLoginBodega::class])->group(function () {
    $path = 'requisiciones'; #Ruta
    $name = $path;          #Nombre de ruta

    Route::get($path, [Requisiciones::class, 'index'])
        ->name($name . '.index')
        ->middleware(isLoginBodega::class);

    Route::post($path, [Requisiciones::class, 'search'])
        ->name($name . '.search')
        ->middleware('permission:' . $path . '.index');

    Route::post($path . '/api/search', [Requisiciones::class, 'apiSearch'])
        ->name($name . '.apiSearch')
        ->middleware('permission:' . $path . '.index');
    Route::get($path . '/api/requisicion', [Requisiciones::class, 'requisicion'])
        ->name($name . '.requisicion')
        ->middleware('permission:' . $path . '.index');
    Route::get($path . '/usuario', [Requisiciones::class, 'requisicionesUsuario'])
        ->name($name . '.requisicionesUsuario')
        ->middleware('permission:' . $path . '.index');
    Route::get($path . '/autorizar/requisiciones', [Requisiciones::class, 'autorizarRequisiciones'])
        ->name($name . '.autorizarRequisiciones')
        ->middleware(isLoginBodega::class);
    Route::post($path . '/autorizar', [Requisiciones::class, 'autorizar'])
        ->name($name . '.autorizar')
        ->middleware('permission:' . $path . '.index');
    Route::post($path . '/negar', [Requisiciones::class, 'negar'])
        ->name($name . '.negar')
        ->middleware('permission:' . $path . '.index');
    Route::post($path . '/mostrar/detalle', [Requisiciones::class, 'mostrarDetalle'])
        ->name($name . '.mostrarDetalle')
        ->middleware('permission:' . $path . '.index');
    Route::post($path . '/visualizar/requisicion', [Requisiciones::class, 'previsualizarRequisicion'])
        ->name($name . '.previsualizarRequisicion')
        ->middleware('permission:' . $path . '.index');
    Route::get($path . '/historial/bodega', [Requisiciones::class, 'historialBodegaRequisiciones'])
        ->name($name . '.historialBodegaRequisiciones')
        ->middleware('permission:' . $path . '.index');

    Route::get($path . '/crear', [Requisiciones::class, 'create'])
        ->name($name . '.create')
        ->middleware('permission:' . $path . '.create');

    Route::post($path . '/store', [Requisiciones::class, 'store'])
        ->name($name . '.store')
        ->middleware('permission:' . $path . '.create');



    Route::get($path . '/confirm/{id}', [Requisiciones::class, 'confirm'])
        ->name($name . '.confirm')
        ->middleware('permission:' . $path . '.delete');


    Route::post($path . '/status', [Requisiciones::class, 'status'])
        ->name($name . '.status')
        ->middleware('permission:' . $path . '.status');
    Route::get($path . '/reporte', [Requisiciones::class, 'requisiciones_reporte'])
        ->name($name . '.requisiciones_reporte')
        ->middleware('permission:' . $path . '.index');
    Route::post($path . '/reportes/', [Requisiciones::class, 'requisiciones_reporte'])
        ->name($name . '.requisiciones_reporte_search')
        ->middleware('permission:' . $path . '.index');
    Route::get($path . '/reportes/requisiciones/{bodegabyFiltro}/{bodega}/{fecha_inicio}/{fecha_fin}', [Requisiciones::class, 'getReportePDF'])
        ->name($name . '.requisiciones_reporte_pdf')
        ->middleware('permission:' . $path . '.bodega');
    Route::get($path . '/imprimir/{id}', [Requisiciones::class, 'printRequisicion'])
        ->name($name . '.printRequisicion')
        ->middleware('permission:' . $path . '.create');

    Route::get($path . '/{ImpreID}', [Requisiciones::class, 'reportebyRequisicion'])
        ->name($name . '.getImpresion')
        ->middleware('permission:' . $path . '.index');

    Route::get($path . '/solicitudes/historia', [Requisiciones::class, 'historialSolicitudesEntrada'])
        ->name($name . '.historia')
        ->middleware('permission:' . $path . '.bodega');

    Route::get($path . '/reporte/activas', [Requisiciones::class, 'reporteActivas'])
        ->name($name . '.reporte_activas')
        ->middleware('permission:' . $path . '.bodega');
});
