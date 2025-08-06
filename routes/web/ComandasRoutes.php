<?php

use App\Http\Controllers\ComandaDetallesController;
use App\Http\Controllers\ComandasController as Comandas; #Usar controllador

use App\Http\Middleware\isLoginCaja;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', isLoginCaja::class])->group(function () {
    $ruta       = 'comandas';
    $nombreRuta = $ruta;

    Route::get($ruta, [Comandas::class, 'index'])
        ->name($nombreRuta . '.index')
        ->middleware('permission:' . $ruta . '.index');


    #---MOVIL---
    #Listar comandas/mesas
    Route::get("/app/comandas", [Comandas::class, 'getComandasApp'])
        ->name('app.comandas')
        ->middleware('permission:' . $ruta . '.movil_index');
    #Productos por comanda/mesa
    Route::get("/app/comandas/productos/{id}", [Comandas::class, 'getComandasProductosApp'])
        ->name('app.comandas.productos')
        ->middleware('permission:' . $ruta . '.index');
    #Validar si comanda/Mesa existe
    Route::post('/comandas/store/app/validate', [Comandas::class, 'validarSiComandaExiste'])
        ->name($nombreRuta . '.store.app.validate')
        ->middleware('permission:' . $ruta . '.create');
    #Crear comanda/Mesa
    Route::post('/comandas/store/app', [Comandas::class, 'storeApp'])
        ->name($nombreRuta . '.store.app')
        ->middleware('permission:' . $ruta . '.create');
    #Solicitar comprobante
    Route::post('/comandas/comprobanteApp', [Comandas::class, 'comprobanteApp'])
        ->name($nombreRuta . '.comprobanteApp')
        ->middleware('permission:' . $ruta . '.create');
    Route::post('/app/comandas/detalle', [Comandas::class, 'getComandaDetalleApp'])
        ->name($nombreRuta . '.detalle.app')
        ->middleware('permission:' . $ruta . '.index');
    #Lock
    Route::get($ruta . '/lock', [Comandas::class, 'lock'])
        ->name($nombreRuta . '.lock')
        ->middleware('permission:' . $ruta . '.index');
    #---


    Route::get($ruta . "/{id}", [Comandas::class, 'index'])
        ->name($nombreRuta . '.index_id')
        ->middleware('permission:' . $ruta . '.index');
    Route::post('/comandas/store', [Comandas::class, 'store'])
        ->name($nombreRuta . '.store')
        ->middleware('permission:' . $ruta . '.create');
    Route::post('/comandas/evento', [Comandas::class, 'comandaEvento'])
        ->name($nombreRuta . '.comandaEvento')
        ->middleware('permission:' . $ruta . '.create');

    Route::post('/comandas/api/get/detalle', [Comandas::class, 'getDetalle'])
        ->name($nombreRuta . '.api_get_detalle')
        ->middleware('permission:' . $ruta . '.create');

    Route::get($ruta . "/confirmacion/{id}", [Comandas::class, 'confirm'])
        ->name($nombreRuta . '.confirm')
        ->middleware('permission:' . $ruta . '.index');

    Route::post('/comandas/destroy', [Comandas::class, 'destroy'])
        ->name($nombreRuta . '.delete')
        ->middleware('permission:' . $ruta . '.delete');

    Route::post('/comandas/anulacion/productos', [ComandaDetallesController::class, 'anulacionProducto'])
        ->name($nombreRuta . '.anulacion_producto')
        ->middleware('permission:' . $ruta . '.anulacion');

    Route::post('/comandas/separar/productos', [ComandaDetallesController::class, 'separarProducto'])
        ->name($nombreRuta . '.separar_producto')
        ->middleware('permission:' . $ruta . '.separar');
    Route::post('/comandas/separar/productos/eventos', [ComandaDetallesController::class, 'separarProductoEvento'])
        ->name($nombreRuta . '.separar_producto_evento')
        ->middleware('permission:' . $ruta . '.separar');
    Route::get('/comandas/aplicar/cortesia/{id}', [ComandaDetallesController::class, 'descargoComanda'])
        ->name($nombreRuta . '.descargo_comanda')
        ->middleware('permission:' . $ruta . '.separar');

    Route::post('/comandas/clientes/titular', [Comandas::class, 'clientes'])
        ->name($nombreRuta . '.clientes')
        ->middleware('permission:' . $ruta . '.create');

    Route::get('/comandas/comprobante/{id}', [Comandas::class, 'comprobante'])
        ->name($nombreRuta . '.comprobante')
        ->middleware('permission:' . $ruta . '.create');

    Route::get('/comandas/desbloquear/{id}', [Comandas::class, 'desbloquear'])
        ->name($nombreRuta . '.desbloquear')
        ->middleware('permission:' . $ruta . '.create');
    Route::get('/comandas/bloquear/{id}', [Comandas::class, 'bloquear'])
        ->name($nombreRuta . '.bloquear')
        ->middleware('permission:' . $ruta . '.create');


    Route::get('/comandas/cambiar/{tipo}/{id}', [Comandas::class, 'tipoComanda'])
        ->name($nombreRuta . '.tipo_comanda')
        ->middleware('permission:' . $ruta . '.create');
    Route::post('comandas/solicitar/productos', [Comandas::class, 'produccion'])
        ->name('comandas.solicitar')
        ->middleware('permission:' . $ruta . '.create');

    Route::post('/comandas/api/get/detalle/selected', [Comandas::class, 'getDetalleSelected'])
        ->name($nombreRuta . '.api_get_detalle_selected')
        ->middleware('permission:' . $ruta . '.create');

    Route::get('/produccion/cocina', [Comandas::class, 'panelProduccionCocina'])
        ->name('comandas.produccion.cocina')
        ->middleware('permission:produccion.cocina');

    Route::post('/api/produccion/cocina/get/all', [Comandas::class, 'getAllProduccionCocina'])
        ->name('comandas.produccion_cocina')
        ->middleware('permission:produccion.cocina');

    Route::post('/api/produccion/bar/get/all', [Comandas::class, 'getAllProduccionBar'])
        ->name('comandas.produccion_bar')
        ->middleware('permission:produccion.bar');

    Route::get('/produccion/bar', [Comandas::class, 'panelProduccionBar'])
        ->name('comandas.produccion.bar')
        ->middleware('permission:produccion.bar');

    Route::post('/api/produccion/set/timer', [Comandas::class, 'produccionTimer'])
        ->name('comandas.produccion.timer')
        ->middleware('permission:produccion');
    Route::post('/api/produccion/set/completo', [Comandas::class, 'produccionCompleto'])
        ->name('comandas.produccion.completo')
        ->middleware('permission:produccion');
    Route::post('/api/produccion/set/negado', [Comandas::class, 'produccionNegado'])
        ->name('comandas.produccion.negado')
        ->middleware('permission:produccion');
    Route::post('/api/produccion/set/minutos', [Comandas::class, 'setMinutosExtra'])
        ->name('comandas.produccion.set_minutos')
        ->middleware('permission:produccion');

    //Rutas para reportes de produccion
    Route::get('produccion/reporte/cocina', [Comandas::class, 'viewReporteCocina'])
        ->name('comandas.produccion.view_reporte_cocina')
        ->middleware('permission:produccion');
    Route::post('produccion/reporte/cocina', [Comandas::class, 'searchReporteCocina'])
        ->name('comandas.produccion.search_reporte_cocina')
        ->middleware('permission:produccion');

    Route::get('produccion/reporte/bar', [Comandas::class, 'viewReporteBar'])
        ->name('comandas.produccion.view_reporte_bar')
        ->middleware('permission:produccion');
    Route::post('produccion/reporte/bar', [Comandas::class, 'searchReporteBar'])
        ->name('comandas.produccion.search_reporte_bar')
        ->middleware('permission:produccion');
    Route::post('/comandas/api/add/cantidad', [ComandaDetallesController::class, 'edicionCantidad'])
        ->name($nombreRuta . '.edicion_cantidad_detalle')
        ->middleware('permission:' . $ruta . '.cantidad');

    Route::get('/comandas/print/{id}', [ComandaDetallesController::class, 'comandasPrint'])
        ->name('comandas.print')
        ->middleware('permission:' . $ruta . '.create');

    Route::post('/comandas/precios/update', [Comandas::class, 'precio'])
        ->name('comandas.precios')
        ->middleware('permission:comandas.precio');

    Route::post('/comandas/set/credito', [Comandas::class, 'setCredito'])
        ->name('comandas.setCredito')
        ->middleware('permission:comandas.credito');
});

Route::middleware(['auth'])->group(
    function () {

        Route::get('/reporte/comadas/ventas/usuarios', [ComandaDetallesController::class, 'reporteVentas'])
            ->name('comandas.reporteVentas')
            ->middleware('permission:comandas.reporte_venta');
        Route::post('/reporte/comadas/ventas/usuarios', [ComandaDetallesController::class, 'reporteVentasAcciones'])
            ->name('comandas.reporteVentasAcciones')
            ->middleware('permission:comandas.reporte_venta');

        Route::get('/reporte/comadas/anulaciones', [Comandas::class, 'reporteAnulaciones'])
            ->name('comandas.reporteAnulaciones')
            ->middleware('permission:comandas.reporte_anulaciones');

        Route::post('/reporte/comadas/anulaciones/acciones', [Comandas::class, 'reporteAnulacionesAcciones'])
            ->name('comandas.reporteAnulacionesAcciones')
            ->middleware('permission:comandas.reporte_anulaciones');

        Route::get('/reporte/comadas/creditos', [Comandas::class, 'reporteCreditos'])
            ->name('comandas.reporteCreditos')
            ->middleware('permission:clientes.credito');

        Route::post('/reporte/comadas/creditos/acciones', [Comandas::class, 'reporteCreditosAcciones'])
            ->name('comandas.reporteCreditosAcciones')
            ->middleware('permission:clientes.credito');

        Route::get('comadas/add/credito', [Comandas::class, 'addCredito']);
    }
);
