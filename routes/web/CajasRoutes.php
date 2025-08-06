<?php

use App\Http\Controllers\BodegaCajasController;
use App\Http\Controllers\CajasComprobantesController as CajasComprobantesController;
use App\Http\Controllers\CajasController as Cajas; #Usar controllador
use App\Http\Controllers\ComandasController;
use App\Http\Middleware\isLoginCaja;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', isLoginCaja::class])->group(
    function () {
        $path = 'cajas'; #Ruta
        $name = $path;  #Nombre de ruta
        Route::get($path . '/my', [Cajas::class, 'my'])
            ->name($name . '.my')
            ->middleware('permission:' . $path . '.caja');

        Route::get($path . '/comandas/credito', [Cajas::class, 'comandas'])
            ->name($name . '.comandas_creditos')
            ->middleware('permission:' . $path . '.caja');

        Route::get($path . '/pospago', [Cajas::class, 'hospedajePospago'])
            ->name($name . '.pospago')
            ->middleware('permission:' . $path . '.caja');

        Route::get($path . '/menu', [Cajas::class, 'menu'])
            ->name($name . '.menu')
            ->middleware('permission:' . $path . '.caja');

        Route::get($path . '/cierre', [Cajas::class, 'cierreTurno'])
            ->name($name . '.cierre')
            ->middleware('permission:' . $path . '.edit');

        Route::get($path . '/cierre/store', [Cajas::class, 'cierreTurnoStore'])
            ->name($name . '.cierre_store')
            ->middleware('permission:' . $path . '.caja');
        Route::get($path . '/cuentas/activas', [Cajas::class, 'cuentasActivas'])
            ->name('cajas.cuentas');
    }
);
Route::middleware(['auth'])->group(function () {
    $path = 'cajas'; #Ruta
    $name = $path;  #Nombre de ruta

    Route::get($path, [Cajas::class, 'index'])
        ->name($name . '.index')
        ->middleware('permission:' . $path . '.index');

    #MOVIL
    Route::get('/app/cajas/logout', [Cajas::class, 'logoutCajasApp'])
        ->name('cajas.logout.app')
        ->middleware('permission:' . $path . '.caja');

    Route::get('/app/cajas/login', [Cajas::class, 'loginCajasApp'])
        ->name('cajas.login.app')
        ->middleware('permission:' . $path . '.index');

    Route::post('app/cajas/auth', [Cajas::class, 'authApp'])
        ->name('cajas.auth.app')
        ->middleware('permission:' . $path . '.caja');
    #---

    Route::get($path . '/api/list', [Cajas::class, 'index_api'])
        ->name($name . '.index_api')
        ->middleware("permission:" . $path . ".index");

    Route::post($path . '/api/store', [Cajas::class, 'store_apiUsuario'])
        ->name($name . '.store_apiUsuario')
        ->middleware("permission:" . $path . ".create");
    Route::post($path . '/api/store/turnos', [Cajas::class, 'store_apiTurnos'])
        ->name($name . '.store_apiTurnos')
        ->middleware("permission:" . $path . ".create");
    Route::post($path . '/usuarios/list', [Cajas::class, 'list_usuarios'])
        ->name($name . '.list_usuarios')
        ->middleware("permission:" . $path . ".create");
    Route::post($path . '/turnos/list', [Cajas::class, 'list_opcionTurnos'])
        ->name($name . '.list_opcionTurnos')
        ->middleware("permission:" . $path . ".create");
    Route::post($path . '/precios/list', [Cajas::class, 'list_precios'])
        ->name($name . '.list_precios')
        ->middleware("permission:" . $path . ".create");
    Route::post($path . '/api/store/precios', [Cajas::class, 'store_apiPrecio'])
        ->name($name . '.store_apiPrecio')
        ->middleware("permission:" . $path . ".create");

    Route::get($path . '/panelCajas', [Cajas::class, 'panelCajas'])
        ->name($name . '.panelCajas')
        ->middleware('permission:' . $path . '.panelCajas');

    Route::post($path, [Cajas::class, 'search'])
        ->name($name . '.search')
        ->middleware('permission:' . $path . '.index');

    Route::get($path . '/api/search', [Cajas::class, 'apiSearch'])
        ->name($name . '.apiSearch')
        ->middleware('permission:' . $path . '.index');
    Route::get($path . '/api/searchUsuario', [Cajas::class, 'apiSearchUsuario'])
        ->name($name . '.apiSearchUsuario')
        ->middleware('permission:' . $path . '.index');

    Route::get($path . '/api/Pin', [Cajas::class, 'apiPin'])
        ->name($name . '.apiPin')
        ->middleware("permission:" . $path . ".index");

    Route::get($path . '/crear', [Cajas::class, 'create'])
        ->name($name . '.create')
        ->middleware('permission:' . $path . '.create');


    Route::post($path . '/store', [Cajas::class, 'store'])
        ->name($name . '.store')
        ->middleware('permission:' . $path . '.create');

    Route::get($path . '/show/{id}', [Cajas::class, 'show'])
        ->name($name . '.show')
        ->middleware('permission:' . $path . '.index');

    Route::get($path . '/edit/{id}', [Cajas::class, 'edit'])
        ->name($name . '.edit')
        ->middleware('permission:' . $path . '.edit');

    Route::post($path . '/update', [Cajas::class, 'update'])
        ->name($name . '.update')
        ->middleware('permission:' . $path . '.edit');

    Route::get($path . '/confirm/{id}', [Cajas::class, 'confirm'])
        ->name($name . '.confirm')
        ->middleware('permission:' . $path . '.delete');

    Route::post($path . '/delete', [Cajas::class, 'destroy'])
        ->name($name . '.delete')
        ->middleware('permission:' . $path . '.delete');
    Route::post($path . '/delete/api', [Cajas::class, 'destroy_api'])
        ->name($name . '.destroy_api')
        ->middleware("permission:" . $path . ".delete");

    Route::get($path . '/status/{id}', [Cajas::class, 'status'])
        ->name($name . '.bloquear')
        ->middleware('permission:' . $path . '.status');
    Route::get($path . '/comprobantes/status/{id}', [Cajas::class, 'statusComprobanteCajas'])
        ->name($name . '.statusComprobanteCajas')
        ->middleware('permission:' . $path . '.status');

    Route::get($path . '/hospedaje/{id}', [Cajas::class, 'hospedaje'])
        ->name($name . '.hospedaje')
        ->middleware('permission:' . $path . '.create');



    Route::get($path . '/login', [Cajas::class, 'login'])
        ->name($name . '.login')
        ->middleware('permission:' . $path . '.caja');

    Route::post($path . '/auth', [Cajas::class, 'auth'])
        ->name($name . '.auth')
        ->middleware('permission:' . $path . '.caja');

    Route::get($path . '/logout', [Cajas::class, 'logout'])
        ->name($name . '.logout')
        ->middleware('permission:' . $path . '.caja');

    Route::get($path . '/cierre/print/{id}', [Cajas::class, 'pdf'])
        ->name($name . '.cierre_print')
        ->middleware('permission:' . $path . '.caja');

    Route::get($path . '/cierre/impresion/{id}', [Cajas::class, 'impresion'])
        ->name($name . '.cierre_container')
        ->middleware('permission:' . $path . '.caja');

    Route::get($path . '/turnos/reporte', [Cajas::class, 'turnos_reporte'])
        ->name($name . '.turnos_reporte')
        ->middleware('permission:' . $path . '.caja');

    Route::post($path . '/turnos/reporte/', [Cajas::class, 'turnos_reporte'])
        ->name($name . '.turnos_reporte_search')
        ->middleware('permission:' . $path . '.caja');

    Route::get($path . '/turnos/reporte/{fecha_inicio}/{fecha_fin}/{caja}', [Cajas::class, 'getReportePDF'])
        ->name($name . '.turnos_reporte_pdf')
        ->middleware('permission:' . $path . '.caja');

    Route::get($path . '/ventas', [Cajas::class, 'venta_reportes'])
        ->name($name . '.ventas_reporte')
        ->middleware('permission:' . $path . '.venta_reportes');

    Route::post($path . '/ventas', [Cajas::class, 'venta_reportes'])
        ->name($name . '.venta_reporte_search')
        ->middleware('permission:' . $path . '.ventas_reporte');

    Route::get($path . '/venta/print/{id}', [Cajas::class, 'venta_pdf'])
        ->name($name . '.venta_print')
        ->middleware('permission:' . $path . '.caja');

    Route::get($path . '/venta/reporte/{fecha_inicio}/{fecha_fin}/{caja}/{turnos}', [Cajas::class, 'getReporteVentaPDF'])
        ->name($name . '.venta_reporte_pdf')
        ->middleware('permission:' . $path . '.caja');

    Route::post($path . '/api/bodegas', [BodegaCajasController::class, 'store'])
        ->name('cajas.bodega')
        ->middleware('permission:' . $path . '.show');
    Route::post($path . '/api/comprobantes/cajas', [CajasComprobantesController::class, 'store'])
        ->name('cajas.comprobantes_cajas')
        ->middleware('permission:' . $path . '.show');
    Route::get($path . '/panelReportes', [Cajas::class, 'panelReportes']) #1
        ->name($name . '.panel_reportes')
        ->middleware('permission:' . $path . '.panelReportes');
    Route::get($path . '/panelReportesSubMenu', [Cajas::class, 'panelReportesSubMenu'])
        ->name($name . '.panel_reportes_sub_menu')
        ->middleware('permission:' . $path . '.panelReportes');
    Route::post($path . '/panel/turno', [Cajas::class, 'reportesTurnosByCajas']) #2
        ->name('cajas.panel_turnos')
        ->middleware('permission:' . $path . '.reporte');

    Route::post($path . '/send/aletas/cajas', [Cajas::class, 'alertas'])
        ->name('cajas.send_alertas')
        ->middleware('permission:' . $path . '.show');
    Route::get($path . '/panel/Venta', [Cajas::class, 'panelVentaReporte'])
        ->name($name . '.panel_reporte_venta')
        ->middleware('permission:' . $path . '.panelReportes');
    Route::post($path . '/panel/venta', [Cajas::class, 'reportesVentasByCajas'])
        ->name('cajas.panel_ventas')
        ->middleware('permission:' . $path . '.reporte');
    Route::get($path . '/panel/pedido/cocina', [ComandasController::class, 'produccionCocinaByCajas'])
        ->name('cajas.panel_cocina')
        ->middleware('permission:' . $path . '.panelReportes');
    Route::post($path . '/api/panel/cocina', [ComandasController::class, 'getPedidoCocinaByCajas'])
        ->name('cajas.getPedidoCocinaCaja')
        ->middleware('permission:' . $path . '.reporte');
    Route::get($path . '/panel/bar/pedido', [ComandasController::class, 'produccionBarByCajas'])
        ->name('cajas.panel_bar')
        ->middleware('permission:' . $path . '.panelReportes');
    Route::post($path . '/api/panel/bar', [ComandasController::class, 'getPedidoBarByCajas'])
        ->name('cajas.getPedidoBarCaja')
        ->middleware('permission:' . $path . '.reporte');

    #---Reporte de ventas por rubro---
    Route::get($path . '/venta/rubros', [Cajas::class, 'ventasRubrosForm'])
        ->name($path . '.ventaRubros')
        ->middleware('permission:' . $path . '.ventaRubros');

    Route::post($path . '/venta/rubros', [Cajas::class, 'ventaRubros'])
        ->name($path . '.ventaRubrosSearch')
        ->middleware('permission:' . $path . '.ventaRubros');

    Route::post($path . '/venta/rubros', [Cajas::class, 'ventaRubrosAcciones'])
        ->name($path . '.ventaRubrosAcciones')
        ->middleware('permission:' . $path . '.ventaRubros');

    Route::get($path . '/comandas/activas', [Cajas::class, 'comandasActivas'])
        ->name($path . '.comandasActivas')
        ->middleware('permission:' . $path . '.comandas_activas');

    Route::post($path . '/comandas/activas', [Cajas::class, 'comandasActivasAcciones'])
        ->name($path . '.comandasActivasAcciones')
        ->middleware('permission:' . $path . '.comandas_activas');

    Route::get($path . '/ventas/habitaciones', [Cajas::class, 'ventasHabitaciones'])
        ->name($path . '.ventasHabitaciones')
        ->middleware('permission:' . $path . '.ventas_habitaciones');

    Route::post($path . '/ventas/habitaciones', [Cajas::class, 'ventasHabitacionesAcciones'])
        ->name($path . '.ventasHabitacionesAcciones')
        ->middleware('permission:' . $path . '.ventas_habitaciones');

    Route::get($path . '/reporte/turnos/diarios', [Cajas::class, 'cajasTurnosForm'])
        ->name($path . '.reporteTurnosDiarios')
        ->middleware('permission:' . $path . '.caja');

    Route::post($path . '/reporte/acciones/turnos_diarios', [Cajas::class, 'cajasTurnosAcciones'])
        ->name($path . '.reporteTurnosDiariosAcciones')
        ->middleware('permission:' . $path . '.caja');

    Route::get($path . '/buscar/cuentas', [Cajas::class, 'buscarCuentas'])
        ->name($path . '.buscar_cuentas')
        ->middleware('permission:' . $path . '.buscar_cuentas');

    Route::post($path . '/buscar/cuentas/resultado', [Cajas::class, 'buscarCuentasResultado'])
        ->name($path . '.buscar_cuentas_resultado')
        ->middleware('permission:' . $path . '.buscar_cuentas');

    Route::get($path . '/estado/cuentas/{tipo}/{id}', [Cajas::class, 'estadoCuentas'])
        ->name($path . '.estado_cuentas')
        ->middleware('permission:' . $path . '.estado_cuentas');
});
