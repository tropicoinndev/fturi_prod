<?php

use App\Http\Controllers\DteContingenciasController;
use App\Http\Controllers\DteLotesController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DtesController as controller; #Usar controllador
use App\Http\Controllers\MhContingenciasController;


Route::middleware(['auth', 'permission:admin'])->group(function () {
    $path = 'dte'; #Ruta
    $name = $path;         #Nombre de ruta

    Route::get($path, [controller::class, 'index'])
        ->name($name . '.index');


    Route::post($path, [controller::class, 'search'])
        ->name($name . '.search');

    Route::get($path . '/documento/{id}', [controller::class, 'documento'])
        ->name($name . '.documento');

    Route::get($path . '/nuevo/correlativo/{id}', [controller::class, 'nuevoCorrelativo'])
        ->name($name . '.nuevoCorrelativo');

    Route::get($path . '/procesados/', [controller::class, 'procesados'])
        ->name($name . '.procesados');

    Route::post($path . '/procesados/', [controller::class, 'procesadosSearch'])
        ->name($name . '.procesados_search');

    Route::get($path . '/comprobantes/', [controller::class, 'comprobantes'])
        ->name($name . '.comprobantes');

    Route::post($path . '/comprobantes/', [controller::class, 'comprobantes'])
        ->name($name . '.comprobantes_search');

    Route::get($path . '/fallidos/', [controller::class, 'fallidos'])
        ->name($name . '.fallidos');

    Route::post($path . '/fallidos/', [controller::class, 'fallidosSearch'])
        ->name($name . '.fallidos_search');

    #Contingencias
    Route::get($path . '/contingencias', [controller::class, 'contingenciasIndex'])
        ->name($name . '.contingenciasIndex');

    Route::post($path . '/contingencias/search', [controller::class, 'contingenciasSearch'])
        ->name($name . '.contingenciasSearch');

    Route::post($path . '/contingencias/store', [controller::class, 'contingenciasStore'])
        ->name($name . '.contingenciasStore');

    Route::get($path . '/contingencias/edit/{id}', [controller::class, 'contingenciasEdit'])
        ->name($name . '.contingenciasEdit');
    Route::post($path . '/contingencias/update', [controller::class, 'contingenciasUpdate'])
        ->name($name . '.contingenciasUpdate');

    Route::get($path . '/contingencias/confirm/{id}', [controller::class, 'contingenciasConfirm'])
        ->name($name . '.contingenciasConfirm');
    Route::post($path . '/contingencias/delete', [controller::class, 'contingenciasDelete'])
        ->name($name . '.contingenciasDelete');

    Route::get($path . '/contingencias/status/{id}', [controller::class, 'contingenciasStatus'])
        ->name($name . '.contingenciasStatus');

    Route::get($path . '/contingencias/config', [DteContingenciasController::class, 'index'])
        ->name($name . '.contingencias_config');

    Route::post($path . '/contingencias/config', [DteContingenciasController::class, 'store'])
        ->name($name . '.contingencias_store');

    Route::get($path . '/create/contingencias/mh/{id}', [MhContingenciasController::class, 'create'])
        ->name($name . '.contingencias_mh');
    Route::get($path . '/contingencias/items/destroy/{id}', [MhContingenciasController::class, 'destroyItem'])
        ->name($name . '.contingencias_items_delete');

    Route::get($path . '/confirm/contingencias/mh/{id}', [MhContingenciasController::class, 'confirm'])
        ->name($name . '.contingencias_confirm');

    Route::post($path . '/destroy/contingencias/mh/', [MhContingenciasController::class, 'destroy'])
        ->name($name . '.contingencias_delete');


    Route::get($path . '/contingencias/mh/list', [MhContingenciasController::class, 'index'])
        ->name('contingencias_mh.index');

    Route::post('mh/contingencias/store', [MhContingenciasController::class, 'store'])
        ->name("mh_contingencias.store");

    Route::get('mh/contingencias/status/{id}', [MhContingenciasController::class, 'status'])
        ->name("mh_contingencias.status");

    Route::get('dte/lotes/{id}', [MhContingenciasController::class, 'lotes'])
        ->name('dte.enviarLote');

    Route::post('dte/lotes_store', [MhContingenciasController::class, 'loteStore'])
        ->name('dte.lote_store');

    Route::get('lotes/dte/list', [DteLotesController::class, 'index'])
        ->name('dte.lotes_index');

    Route::get('lotes/dte/detalles/{id}', [DteLotesController::class, 'show'])
        ->name('dte.lotes_details');

    Route::get('lotes/dte/descargar/procesados/{id}', [DteLotesController::class, 'getProcesados'])
        ->name('dte.lotes_api_procesados');

    Route::get('dte/consultar/procesados/{id}', [controller::class, 'getProcesados'])
        ->name('dte.mh_api_procesados');

    Route::post('dte/actualizar/estado/', [controller::class, 'update'])
        ->name('dte.mh_api_update_consulta');

    Route::get('/dte/agua', [controller::class, 'getDteAgua']);

    Route::get('/dte/test/firmador', [controller::class, 'testFirma'])
        ->name('firmador.hola');

    Route::get('/admin/anticipos/visual', [controller::class, 'anticiposVisual'])
        ->name('anticiposVisual.index');

    Route::post('/admin/anticipos/visual', [controller::class, 'anticiposVisualSearch'])
        ->name('anticiposVisual.search');

    Route::post('/admin/anticipos/visual/desactivar', [controller::class, 'anticiposVisualDesactivar'])
        ->name('anticiposVisual.desactivar');

    Route::get('dte/observaciones', [controller::class, 'observaciones'])
        ->name('dte.observaciones');

    Route::post('dte/observaciones', [controller::class, 'observaciones'])
        ->name('dte.observacionesSearch');
});
Route::middleware(['auth'])->group(function () {
    Route::post('dte/print/ticket/{id}', [controller::class, 'ticket'])
        ->name('dte.print_ticket');

    Route::get('firmador/Status', [controller::class, 'firmadorStatus'])
        ->name('firmador.status');

    Route::post('dte/store/complemento', [controller::class, 'complemento'])
        ->name('dte.complemento');
});
