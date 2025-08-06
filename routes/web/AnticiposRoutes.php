<?php

use App\Http\Controllers\AnticipoReservacionController;
use App\Http\Controllers\AnticiposController as controller; #Usar controllador
use App\Models\anticipo_reservacion;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    $path = 'anticipos';
    $name = $path;

    Route::get($path, [controller::class, 'index'])
        ->name($name . '.index')
        ->middleware('permission:' . $path . '.index');

    Route::post($path, [controller::class, 'search'])
        ->name($name . '.search')
        ->middleware('permission:' . $path . '.index');

    Route::get($path . '/crear', [controller::class, 'create'])
        ->name($name . '.create')
        ->middleware('permission:' . $path . '.create');

    Route::post($path . '/store', [controller::class, 'store'])
        ->name($name . '.store')
        ->middleware('permission:' . $path . '.create');
    Route::post($path . '/anticipo/evento', [controller::class, 'anticipoEventos'])
        ->name($name . '.anticipoEventos')
        ->middleware('permission:' . $path . '.create');

    Route::post($path . '/update', [controller::class, 'update'])
        ->name($name . '.update')
        ->middleware('permission:' . $path . '.edit');

    Route::get($path . '/confirm/{id}', [controller::class, 'confirm'])
        ->name($name . '.confirm')
        ->middleware('permission:' . $path . '.delete');

    Route::get($path . '/edit/{id}', [controller::class, 'edit'])
        ->name($name . '.edit')
        ->middleware('permission:' . $path . '.cliente');

    Route::post($path . '/delete', [controller::class, 'destroy'])
        ->name($name . '.delete')
        ->middleware('permission:' . $path . '.delete');

    Route::post($path . '/api/buscar', [controller::class, 'api_buscar'])
        ->name($name . '.api_buscar')
        ->middleware('permission:' . $path . '.delete');



    Route::get($path . '/anular/{id}', [controller::class, 'anular'])
        ->name($name . '.anular')
        ->middleware('permission:' . $path . '.anular');

    Route::get($path . '/asignacion/{id}', [AnticipoReservacionController::class, 'destroy'])
        ->name($name . '.destroy_asignacion')
        ->middleware('permission:' . $path . '.anular');

    Route::get($path . '/imprimir/{id}', [controller::class, 'print'])
        ->name($name . '.print')
        ->middleware('permission:' . $path . '.create');

    Route::get($path . '/impresion/{id}', [controller::class, 'impresion'])
        ->name($name . '.container')
        ->middleware('permission:' . $path . '.caja');

    Route::get($path . '/anulacion/{id}', [controller::class, 'anularAnticipo'])
        ->name($name . '.anularAnticipo')
        ->middleware("permission:" . $path . ".anular_anticipo");

    Route::post($path . '/api/anulacion', [controller::class, 'anulacionAnticipo'])
        ->name($name . '.anulacion_anticipo')
        ->middleware('permission:' . $path . '.anular_anticipo');

    Route::post($path . '/update/cliente', [controller::class, 'updateClienteAnticipo'])
        ->name($name . '.updateClienteAnticipo')
        ->middleware('permission:' . $path . '.edit');

    Route::get($path . '/api/disponible', [controller::class, 'reporteAnticipo'])
        ->name($name . '.reporte_anticipo')
        ->middleware("permission:" . $path . ".reporte");

    Route::post($path . '/reporte/disponibles', [controller::class, 'reporteAnticiposAuditoria'])
        ->name($name . '.reporte_anticipo_buscar')
        ->middleware("permission:" . $path . ".create");
    Route::get($path . '/reporte/anular', [controller::class, 'anuladoAnticipo'])
        ->name($name . '.anulado_anticipo')
        ->middleware("permission:" . $path . ".disponible");

    Route::post($path . '/reporte/anticipos/anulados', [controller::class, 'reporteAnuladosAnticipos'])
        ->name($name . '.reporte_anulado_buscar')
        ->middleware("permission:" . $path . ".create");

    Route::get($path . '/cambiar/aplicacion/{id}', [controller::class, 'cambiarAplicacion'])
        ->name($name . '.cambiarAplicacion')
        ->middleware("permission:" . $path . ".cambiar_aplicacion");

    Route::get($path . '/separar/{id}', [controller::class, 'separar'])
        ->name($name . '.separar')
        ->middleware("permission:" . $path . ".admin");

    Route::post($path . '/separar/', [controller::class, 'separarStore'])
        ->name($name . '.separarStore')
        ->middleware("permission:" . $path . ".admin");




    Route::post($path . '/cambiar/aplicacion/', [controller::class, 'cambiarAplicacionStore'])
        ->name($name . '.cambiarAplicacionStore')
        ->middleware("permission:" . $path . ".cambiar_aplicacion");

    #Editar forma de pago
    Route::get($path . '/cambiar/forma/pago/{id}', [controller::class, 'cambiarFormaPago'])
        ->name($name . '.cambiarFormaPago')
        ->middleware('permission:' . $path . '.cambiar_forma_pago');

    Route::post($path . '/cambiar/forma/pago', [controller::class, 'cambiarFormaPagoStore'])
        ->name($name . '.cambiarFormaPagoStore')
        ->middleware('permission:' . $path . '.cambiar_forma_pago');

    #Alertas
    Route::get($path . '/alerta', [controller::class, 'alertaAnticipos'])
        ->name($name . '.alertaAnticipos')
        ->middleware('permission:' . $path . '.index');

    #Reportes: Formato 1
    Route::get($path . '/reporte/anticipos/activos/formato1', [controller::class, 'reporteAnticiposForm1'])
        ->name($name . '.reporteAnticiposForm1')
        ->middleware('permission:' . $path . '.reporte');

    Route::post($path . '/reporte/anticipos/activos/formato1', [controller::class, 'reporteAnticiposAcciones1'])
        ->name($name . '.reporteAnticiposAcciones1')
        ->middleware('permission:' . $path . '.reporte');

    #Reportes: Formato 2
    Route::get($path . '/reporte/anticipos/activos/formato2', [controller::class, 'reporteAnticiposForm2'])
        ->name($name . '.reporteAnticiposForm2')
        ->middleware('permission:' . $path . '.reporte');

    Route::post($path . '/reporte/anticipos/activos/formato2', [controller::class, 'reporteAnticiposAcciones2'])
        ->name($name . '.reporteAnticiposAcciones2')
        ->middleware('permission:' . $path . '.reporte');
});
