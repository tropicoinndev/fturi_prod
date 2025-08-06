<?php

use App\Http\Controllers\DetalleMontajeEventosController;
use App\Http\Controllers\EventosController as EventosController; #Usar controllador
use App\Http\Controllers\EventosSalonesController as EventoSalones;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    $path = 'eventos'; #Ruta
    $name = $path; #Nombre de ruta
    Route::get($path. '/eventos/{id}/{eventoId}', [EventosController::class, 'detalleOrden'])
        ->name($name . '.detalle_orden')
        ->middleware('permission:' . $path . '.index');
    Route::get($path . '/comandas/{id}/{eventoId}', [EventosController::class, 'comandasbyEvento'])
        ->name($name . '.comandasbyEvento')
        ->middleware('permission:' . $path . '.index');

    Route::post($path, [EventosController::class, 'search'])
        ->name($name . '.search')
        ->middleware('permission:' . $path . '.index');
    Route::get($path . '/eventos', [EventosController::class, 'eventos'])
        ->name($name . '.eventos')
        ->middleware('permission:' . $path . '.index');
    Route::get($path . '/crear', [EventosController::class, 'create'])
        ->name($name . '.create')
        ->middleware('permission:' . $path . '.create');

    Route::post($path . '/store', [EventosController::class, 'store'])
        ->name($name . '.store')
        ->middleware('permission:' . $path . '.create');

    Route::get($path . '/edit/{id}', [EventosController::class, 'edit'])
        ->name($name . '.edit')
        ->middleware('permission:' . $path . '.edit');

    Route::post($path . '/update', [EventosController::class, 'update'])
        ->name($name . '.update')
        ->middleware('permission:' . $path . '.edit');

    Route::get($path . '/confirm/{id}', [EventosController::class, 'confirm'])
        ->name($name . '.confirm')
        ->middleware('permission:' . $path . '.delete');
    Route::get($path . '/evento', [EventosController::class, 'evento'])
        ->name($name . '.evento')
        ->middleware('permission:' . $path . '.create');
    Route::get($path . '/detalle/{id}', [EventosController::class, 'detalle'])
        ->name($name . '.detalle')
        ->middleware('permission:' . $path . '.detalle');
    Route::get($path . '/duplicar/{id}', [EventosController::class, 'duplicarEvento'])
        ->name($name . '.duplicar_evento')
        ->middleware('permission:' . $path . '.create');
    Route::get($path . '/comanda/{id}/{eventoId}', [EventosController::class, 'comandaEventos'])
        ->name($name . '.comandaEventos')
        ->middleware('permission:' . $path . '.comanda');

    Route::post($path . '/delete', [EventosController::class, 'destroy'])
        ->name($name . '.delete')
        ->middleware('permission:' . $path . '.delete');
    Route::get($path . '/anticipos/{id}', [EventosController::class, 'anticiposEventos'])
        ->name($name . '.anticiposEventos')
        ->middleware('permission:' . $path . '.create');
    Route::get($path . '/solicita/autorizar/{id}', [EventosController::class, 'solicitarAutorizacionEvento'])
        ->name($name . '.solicitarAutorizacionEvento')
        ->middleware('permission:' . $path . '.create');
    Route::post($path . '/autorizar', [EventosController::class, 'autorizarEvento'])
        ->name($name . '.autorizarEvento')
        ->middleware('permission:' . $path . '.create');
    Route::post($path . '/sonido', [EventosController::class, 'asignarSonido'])
        ->name($name . '.asignarSonido')
        ->middleware('permission:' . $path . '.create');
    Route::post($path . '/asignar/montaje', [EventosController::class, 'asignarMontaje'])
        ->name($name . '.asignar_montaje')
        ->middleware('permission:' . $path . '.create');
    Route::post($path . '/actualizar', [EventosController::class, 'actualizarTipoEvento'])
        ->name($name . '.actualizarTipoEvento')
        ->middleware('permission:' . $path . '.edit');
    Route::post($path . '/encargado', [EventosController::class, 'actualizarEncargado'])
        ->name($path . '.actualizarEncargado')
        ->middleware('permission:' . $path . '.edit');
    Route::post($path . '/observacio/general', [EventosController::class, 'observacionGeneral'])
        ->name($path . '.observacion_general')
        ->middleware('permission:' . $path . '.edit');
    Route::post($path . '/edit/salones', [EventoSalones::class, 'editSalonesEvento'])
        ->name($path . '.edit_salones')
        ->middleware('permission:' . $path . '.edit');
    Route::post($path . '/edit/cliente/evento', [EventosController::class, 'updateClienteEvento'])
        ->name($path . '.edit_cliente')
        ->middleware('permission:' . $path . '.edit');
    Route::post($path . '/add/cliente', [EventosController::class, 'addClienteEvento'])
        ->name($path . '.add_cliente')
        ->middleware('permission:' . $path . '.edit');
    Route::post($path . '/edit/fecha', [EventosController::class, 'updateFechaHoraEvento'])
        ->name($path . '.edit_fecha')
        ->middleware('permission:' . $path . '.edit');
    Route::post($path . '/duplicacion', [EventosController::class, 'duplicacionEvento'])
        ->name($path . '.duplicar')
        ->middleware('permission:' . $path . '.edit');
    Route::get($path . '/imprimir/{id}', [EventosController::class, 'printEvento'])
        ->name($name . '.printEvento')
        ->middleware('permission:' . $path . '.print');

    Route::get($path . '/{impresionEvento}', [EventosController::class, 'reportebyEvento'])
        ->name($name . '.getImpresionEvento')
        ->middleware('permission:' . $path . '.print');
    Route::get($path . '/autorizar/eventos', [EventosController::class, 'panelAutorizacion'])
        ->name($name . '.panelAutorizacion')
        ->middleware('permission:' . $path . '.index');
    Route::get($path . '/autorizar/pendientes/{id}', [EventosController::class, 'detalleAutorizar'])
        ->name($name . '.detalleAutorizar')
        ->middleware('permission:' . $path . '.autorizar');
    Route::get($path . '/pago/{id}', [EventosController::class, 'facturaAnticipada'])
        ->name($name . '.pago_anticipado')
        ->middleware('permission:' . $path . '.index');
    Route::post($path . '/detalle/montaje', [DetalleMontajeEventosController::class, 'store'])
        ->name($path . '.detalle_montaje_evento')
        ->middleware('permission:' . $path . '.create');
    Route::post($path . '/update/sonido/observacion', [EventosController::class, 'updateObservacionSonido'])
        ->name($path. '.sonido_observacion')
        ->middleware('permission:'.$path. '.edit');
    Route::post($path . '/update/montaje', [EventosController::class, 'updateMontaje'])
    ->name($path . '.update_montaje')
    ->middleware('permission:' . $path . '.edit');

    Route::get($path . '/impresiones/reporte', [EventosController::class, 'eventos_reporte'])
    ->name($name . '.eventos_reporte')
    ->middleware('permission:' . $path . '.index');
    Route::post($path . '/impresiones/reporte/search', [EventosController::class, 'eventos_reporte'])
    ->name($name . '.eventos_reporte_search')
    ->middleware('permission:' . $path . '.index');
    Route::get($path . '/impresiones/reporte/{fecha_inicio}/{fecha_fin}', [EventosController::class, 'getReporteEventosPDF'])
    ->name($name . '.eventos_reporte_pdf')
    ->middleware('permission:' . $path . '.index');
    Route::get($path . '/impresiones/sin/precios/reporte/{fecha_inicio}/{fecha_fin}', [EventosController::class, 'getReporteByEventos'])
    ->name($name . '.eventos_reporte_general_pdf')
    ->middleware('permission:' . $path . '.index');
    Route::post($path . '/negar/evento', [EventosController::class, 'negarEvento'])
    ->name($name . '.negar_evento')
    ->middleware('permission:' . $path . '.negacion');
    Route::post($path . '/minimo/maximo', [EventosController::class, 'updateCantidadPersonas'])
    ->name($name . '.cantidad_personas')
    ->middleware('permission:' . $path . '.edit');
    Route::post($path . '/ob/factura', [EventosController::class, 'observacionFactura'])
    ->name($name . '.observacion_factura')
    ->middleware('permission:' . $path . '.create');
    Route::get($path . '/reporte/sonidos/', [EventosController::class, 'reporteSonidos'])
    ->name($name . '.reporte_sonidos')
    ->middleware('permission:' . $path . '.reportes');
    Route::post($path . '/reporte/sonidos/', [EventosController::class, 'reporteSonidos'])
    ->name($name . '.reporte_sonidos_buscar')
    ->middleware('permission:' . $path . '.reportes');
    Route::get($path . '/reporte/montajes/', [EventosController::class, 'reporteMontajes'])
    ->name($name . '.reporte_montajes')
    ->middleware('permission:' . $path . '.reportes');
    Route::post($path . '/reporte/montajes/', [EventosController::class, 'reporteMontajes'])
    ->name($name . '.reporte_montajes_buscar')
    ->middleware('permission:' . $path . '.reportes');
    Route::get($path . '/reporte/ventas/', [EventosController::class, 'reporteVenta'])
    ->name($name . '.reporte_venta')
    ->middleware('permission:' . $path . '.reportes');
    Route::post($path . '/reporte/ventas/', [EventosController::class, 'reporteVentas'])
    ->name($name . '.reporte_ventas_buscar')
    ->middleware('permission:' . $path . '.reportes');
    Route::get($path . '/cuentas/reporte/descargo/', [EventosController::class, 'descargo'])
    ->name($name . '.reporte_descargo')
    ->middleware('permission:' . $path . '.reportes');
    Route::post($path. '/cuentas/reporte/', [EventosController::class, 'reporteDescargo'])
    ->name($name. '.reporte_descargo_buscar')
    ->middleware('permission:' .$path. '.reportes');
    Route::get($path . '/produccion/cuentas/cocina/reporte/', [EventosController::class, 'cocina'])
    ->name($name . '.cocina')
    ->middleware('permission:' . $path . '.reportes');
    Route::post($path . '/produccion/cuentas/cocina/productos/', [EventosController::class, 'cocinaDescargo'])
    ->name($name . '.cocina_buscar')
    ->middleware('permission:' . $path . '.reportes');
    Route::get($path . '/reservacion/{id}', [EventosController::class, 'reservacionEvento'])
    ->name($name . '.reservacionEvento')
    ->middleware('permission:' . $path . '.create');
    Route::post($path . '/desbloquear/evento', [EventosController::class, 'desbloquearEvento'])
    ->name($name . '.desbloquear_evento')
    ->middleware('permission:' . $path . '.desbloquear');

});
