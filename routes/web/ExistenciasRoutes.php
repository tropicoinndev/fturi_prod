<?php

use App\Http\Controllers\ExistenciasController as Existencias; #Usar controllador
use App\Http\Middleware\isLoginBodega;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', isLoginBodega::class])->group(function () {
    $path = 'existencias'; #Ruta
    $name = $path; #Nombre de ruta

    Route::get($path . '/{existenciasId}', [Existencias::class, 'index'])
        ->name($name . '.index')
        ->middleware('permission:' . $path . '.index');
    Route::post($path . '/productos/existencias', [Existencias::class, 'productos'])
        ->name($name . '.productos')
        ->middleware('permission:' . $path . '.index');

    Route::get($path . '/crear', [Existencias::class, 'create'])
        ->name($name . '.create')
        ->middleware('permission:' . $path . '.create');

    Route::post($path . '/store', [Existencias::class, 'store'])
        ->name($name . '.store')
        ->middleware('permission:' . $path . '.create');
    Route::post($path . '/api/search/existencias', [Existencias::class, 'productosExistencias'])
        ->name($name . '.productosExistencias')
        ->middleware('permission:' . $path . '.index');
    Route::get($path . '/reporte/existencias', [Existencias::class, 'reporte_existencias'])
        ->name($name . '.existencias_reporte')
        ->middleware('permission:' . $path . '.index');
    Route::post($path . '/reportes/', [Existencias::class, 'reporte_existencias'])
        ->name($name . '.existencias_reporte_search')
        ->middleware('permission:' . $path . '.index');
    Route::get($path . '/reportes/existencias/{bodegaId}/{bodega}/{productosId}', [Existencias::class, 'getReportePDF'])
        ->name($name . '.existencias_reporte_pdf')
        ->middleware('permission:' . $path . '.index');
    Route::get($path . '/bodega/existencias', [Existencias::class, 'reporte_bodega_existencia'])
        ->name($name . '.reporte_existencia_bodega')
        ->middleware('permission:' . $path . '.index');
    Route::post($path . '/bodega/', [Existencias::class, 'reporte_bodega_existencia'])
        ->name($name . '.reporte_bodega_existencia_search')
        ->middleware('permission:' . $path . '.index');
    Route::get($path . '/bodega/existencias/{bodegaId}/{bodega}', [Existencias::class, 'getReporteBodegaExistenciaPDF'])
        ->name($name . '.reporte_existencia_bodega_pdf')
        ->middleware('permission:' . $path . '.index');

    Route::get($path . '/reporte/vencimiento', [Existencias::class, 'reportesVencimientos'])
        ->name($name . '.reporte_vencimiento')
        ->middleware('permission:' . $path . '.vencimiento');

    Route::post($path . '/reporte/vencimiento/acciones', [Existencias::class, 'getReporteVencimiento'])
        ->name($name . '.reporte_vencimiento_acciones')
        ->middleware('permission:' . $path . '.vencimiento');
});
