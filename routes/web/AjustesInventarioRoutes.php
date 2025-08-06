<?php

use App\Http\Controllers\AjustesInventarioController as controller;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function(){
    $path = 'ajustes_inventarios';
    $name = 'ajustes_inventarios';

    Route::get($path,[controller::class,'index'])
        ->name($name.'.index')
        ->middleware('permission:' . $path . '.index');

    Route::get($path.'/create',[controller::class,'create'])
        ->name($name.'.create')
        ->middleware('permission:' . $path . '.create');
    Route::post('solicitud/store',[controller::class,'store'])
        ->name('solicitud.store')
        ->middleware('permission:' . $path . '.store');
    Route::get('solicitud/detalle/{id}',[controller::class,'detalle'])
        ->name('solicitud.detalle')
        ->middleware('permission:' . $path . '.detalle');

    Route::get('autorizar/detalle/{id}',[controller::class,'autorizarDetalle'])
        ->name('autorizar.detalle')
        ->middleware('permission:' . $path . '.detalle');

    Route::get($path.'/solicitados',[controller::class,'solicitados'])
        ->name($name.'.solicitados')
        ->middleware('permission:' . $path . '.solicitados');
    Route::get($path.'/negados',[controller::class,'negados'])
        ->name($name.'.negados')
        ->middleware('permission:' . $path . '.negados');
    Route::get($path.'/autorizar',[controller::class,'autorizar'])
        ->name($name.'.autorizar')
        ->middleware('permission:' . $path . '.autorizar');

    Route::get($path.'/historial',[controller::class,'historial'])
        ->name($name.'.historial')
        ->middleware('permission:' . $path . '.historial');



    Route::post($path.'/search',[controller::class,'search'])
        ->name($name.'.search')
        ->middleware('permission:' . $path . '.index');

    Route::get($path.'/show/{id}',[controller::class,'show'])
        ->name($name.'.show')
        ->middleware('permission:'.$path.'.show');

    Route::get($path.'/edit/{id}',[controller::class,'edit'])
        ->name($name.'.edit')
        ->middleware('permission:'.$path.'.edit');
    Route::post($path.'/update',[controller::class,'update'])
        ->name($name.'.update')
        ->middleware('permission:'.$path.'.edit');

    Route::post($path.'/completarSolicitud',[controller::class,'completarSolicitud'])
        ->name($name.'.completarSolicitud')
        ->middleware('permission:'.$path.'.create');

    Route::post($path.'/find/lote',[controller::class,'findLote'])
        ->name($name.'.findLote')
        ->middleware('permission:'.$path.'.index');
    Route::post($path.'/save/solicitud',[controller::class,'saveSolicitud'])
        ->name($name.'.saveSolicitud')
        ->middleware('permission:'.$path.'.create');

    Route::post($path.'/edit/solicitud',[controller::class,'editSolicitud'])
        ->name($name.'.editSolicitud')
        ->middleware('permission:'.$path.'.edit');

    Route::post($path.'/auth/action',[controller::class,'authAction'])
        ->name($name.'.authAction')
        ->middleware('permission:'.$path.'.autorizar');

    Route::get($path.'/reporte',[controller::class,'reporte'])
        ->name($name.'.reporte')
        ->middleware('permission:'.$path.'.reporte');

    Route::post($path.'/reporte',[controller::class,'reporteOpcion'])
        ->name($name.'.reporteOpcion')
        ->middleware('permission:'.$path.'.reporte');

    Route::get($path.'/reporte/existencias/bodega',[controller::class,'reporteExistenciasByBodega'])
        ->name($name.'.reporteExistenciasByBodega')
        ->middleware('permission:'.$path.'.reportExisByBodegaSearch');
    Route::get($path.'/reporte/existencias/producto',[controller::class,'reporteExistenciasByProducto'])
        ->name($name.'.reporteExistenciasByProducto')
        ->middleware('permission:'.$path.'.index');
    Route::post($path.'/reporte/existencias/bodega/search',[controller::class,'reportExisByBodegaSearch'])
        ->name($name.'.reportExisByBodegaSearch')
        ->middleware('permission:'.$path.'.index');
    Route::get($path . '/reporte/existencias/bodega/{bodegaId}/{bodega}/{producto}', [controller::class, 'getReporteBodegaExistenciaPDF'])
        ->name($name . '.reporte_existencia_bodega_pdf')
        ->middleware('permission:' . $path . '.index');

    Route::get($path.'/imprimir/acta/{id}',[controller::class,'imprimirActa'])
        ->name($name.'.imprimirActa')
        ->middleware('permission:'.$path.'.print');

    Route::post($path.'/edit/solicitante',[controller::class,'editSolicitante'])
        ->name($name.'.editSolicitante')
        ->middleware('permission:'.$path.'.edit');

    Route::get($path . '/confirm/{id}', [controller::class,'confirm'])
        ->name($name.'.confirm')
        ->middleware('permission:'.$path.'.delete');

    Route::post($path.'/delete', [controller::class,'destroy'])
        ->name($name.'.delete')
        ->middleware('permission:'.$path.'.delete');

    Route::get('/rediseno/dte',[controller::class,'redisenoDte']);
});
