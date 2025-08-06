<?php

use App\Http\Controllers\RecepcionesController as controller; #Usar controllador

use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    $ruta       = 'recepciones';
    $nombreRuta = $ruta;

    Route::get($ruta, [controller::class, 'index'])
        ->name($nombreRuta . '.index')
        ->middleware('permission:' . $ruta . '.index');

    Route::post($ruta, [controller::class, 'search'])
        ->name($nombreRuta . '.search')
        ->middleware('permission:' . $ruta . '.index');

    Route::get($ruta . '/crear/{id}', [controller::class, 'create'])
        ->name($nombreRuta . '.create')
        ->middleware("permission:" . $ruta . ".create");

    Route::post($ruta . '/store', [controller::class, 'store'])
        ->name($nombreRuta . '.store')
        ->middleware('permission:' . $ruta . '.create');

    Route::post($ruta . '/cambiar/tarifas', [controller::class, 'cambiarTarifa'])
        ->name($nombreRuta . '.cambiarTarifa')
        ->middleware('permission:' . $ruta . '.create');

    Route::post($ruta . '/anulacion/justificada', [controller::class, 'anulacion'])
        ->name($nombreRuta . '.anulacion')
        ->middleware('permission:' . $ruta . '.create');

    Route::post($ruta . '/store/from/reserva', [controller::class, 'store_reserva'])
        ->name($nombreRuta . '.create_reserva')
        ->middleware('permission:' . $ruta . '.create');

    Route::get($ruta . '/show/{id}', [controller::class, 'show'])
        ->name($nombreRuta . '.show')
        ->middleware("permission:" . $ruta . ".create");

    Route::post($ruta . '/huespedes/store', [controller::class, 'addHuespedRecepcion'])
        ->name($nombreRuta . '.huesped')
        ->middleware("permission:" . $ruta . ".create");

    Route::get($ruta . '/huespedes/destroy/{id}', [controller::class, 'delHuespedRecepcion'])
        ->name($nombreRuta . '.delete_huesped')
        ->middleware("permission:" . $ruta . ".create");

    Route::get($ruta . '/impresion/{id}', [controller::class, 'imprimir'])
        ->name($nombreRuta . '.imprimir')
        ->middleware("permission:" . $ruta . ".create");

    Route::get($ruta . '/salida/{id}', [controller::class, 'salida'])
        ->name($nombreRuta . '.salida')
        ->middleware("permission:" . $ruta . ".create");

    Route::get($ruta . '/salida/confirmada/{id}', [controller::class, 'salidaConfirm'])
        ->name($nombreRuta . '.salida_confirm')
        ->middleware("permission:" . $ruta . ".create");

    Route::get($ruta . '/comprobante/{id}', [controller::class, 'comprobante'])
        ->name($nombreRuta . '.comprobante')
        ->middleware("permission:" . $ruta . ".create");





    Route::get($ruta . '/edit/{id}', [controller::class, 'edit'])
        ->name($nombreRuta . '.edit')
        ->middleware("permission:" . $ruta . ".edit");

    Route::post($ruta . '/update', [controller::class, 'update'])
        ->name($nombreRuta . '.update')
        ->middleware("permission:" . $ruta . ".edit");

    Route::post($ruta . '/update/fecha', [controller::class, 'updateFechaSalida'])
        ->name($nombreRuta . '.update_fecha_salida')
        ->middleware("permission:" . $ruta . ".edit");

    Route::post($ruta . '/update/clientes', [controller::class, 'updateCliente'])
        ->name($nombreRuta . '.update_cliente')
        ->middleware("permission:" . $ruta . ".update_cliente");

    Route::get($ruta . '/impresion/registro/{id}', [controller::class, 'container'])
        ->name($nombreRuta . '.container')
        ->middleware("permission:" . $ruta . ".create");

    Route::get($ruta . '/print/registro/{id}', [controller::class, 'print'])
        ->name($nombreRuta . '.print')
        ->middleware("permission:" . $ruta . ".create");

    Route::get($ruta . '/checkout/', [controller::class, 'checkout'])
        ->name($nombreRuta . '.checkout')
        ->middleware("permission:" . $ruta . ".create");

    Route::post($ruta . '/checkout/store', [controller::class, 'checkoutStore'])
        ->name($nombreRuta . '.checkoutStore')
        ->middleware("permission:" . $ruta . ".create");

    Route::get($ruta . '/confirm/{id}', [controller::class, 'confirm'])
        ->name($nombreRuta . '.confirm')
        ->middleware("permission:" . $ruta . ".delete");



    Route::post($ruta . '/delete', [controller::class, 'destroy'])
        ->name($nombreRuta . '.delete')
        ->middleware("permission:" . $ruta . ".delete");



    Route::post($ruta . '/api/ingreso/validate', [controller::class, 'isValidRecepcion'])
        ->name($nombreRuta . '.api_validate')
        ->middleware("permission:" . $ruta . ".create");

    Route::get($ruta . '/desbloquear/{id}', [controller::class, 'desbloquear'])
        ->name($nombreRuta . '.desbloquear')
        ->middleware("permission:" . $ruta . ".desbloquear");
    Route::post($ruta . '/salida/anticipada/', [controller::class, 'salidaAnticipada'])
        ->name($nombreRuta . '.salida_anticipada')
        ->middleware("permission:" . $ruta . ".salida_anticipada");
    Route::post($ruta . '/salida/pospago/', [controller::class, 'salidaSinCobro'])
        ->name($nombreRuta . '.salida_sin_cobro')
        ->middleware("permission:" . $ruta . ".salida_sin_cobro");

    Route::get($ruta . '/sucursal/{id}', [controller::class, 'sucursal'])
        ->name($nombreRuta . '.sucursal')
        ->middleware("permission:" . $ruta . ".create");

    Route::get($ruta . '/clear/sucursal/', [controller::class, 'sucursalClear'])
        ->name($nombreRuta . '.sucursal_clear')
        ->middleware("permission:" . $ruta . ".create");

    Route::get($ruta . '/ticket/container/', [controller::class, 'containerTicket'])
        ->name($nombreRuta . '.ticket_container')
        ->middleware("permission:" . $ruta . ".ticket");
    Route::get($ruta . '/ticket/imprimir/', [controller::class, 'imprimirTicket'])
        ->name($nombreRuta . '.ticket')
        ->middleware("permission:" . $ruta . ".ticket");

    Route::get($ruta . '/reporte/estadia', [controller::class, 'reporteEstadia'])
        ->name($nombreRuta . '.reporte_estadia')
        ->middleware("permission:" . $ruta . ".reportes");

    Route::post($ruta . '/reporte/estadia', [controller::class, 'reporteEstadia'])
        ->name($nombreRuta . '.reporte_estadia_buscar')
        ->middleware("permission:" . $ruta . ".reportes");

    Route::post($ruta . '/precios', [controller::class, 'precios'])
        ->name($nombreRuta . '.precios')
        ->middleware("permission:" . $ruta . ".precio");
    Route::get($ruta . '/reporte/huespedes', [controller::class, 'reporteHuespedes'])
        ->name($nombreRuta . '.reporte_huesped')
        ->middleware("permission:" . $ruta . ".reportes");

    Route::match(['get', 'post'], $ruta . '/reporte/huesped', [controller::class, 'reporteHuespedes'])
        ->name($nombreRuta . '.reporte_huesped_buscar')
        ->middleware("permission:" . $ruta . ".reportes");
    Route::get($ruta . '/reporte/data/tours', [controller::class, 'reporteDataTours'])
        ->name($nombreRuta . '.reporte_data_tours')
        ->middleware("permission:" . $ruta . ".reportes");
    Route::post($ruta . '/reporte/data', [controller::class, 'reporteDataTours'])
        ->name($nombreRuta . '.reporte_tours_buscar')
        ->middleware("permission:" . $ruta . ".reportes");

    #--REPORTE BITÁCORA RECEPCIONES---
    Route::get($ruta . '/bitacora/recepciones', [controller::class, 'bitacoraRecepciones'])
        ->name($nombreRuta . '.bitacoraRecepciones')
        ->middleware('permission:' . $ruta . '.recepcion.bitacora_anulaciones');

    Route::post($ruta . '/bitacora/recepciones', [controller::class, 'bitacoraRecepcionesSearch'])
        ->name($nombreRuta . '.bitacoraRecepcionesSearch')
        ->middleware('permission:' . $ruta . '.recepcion.bitacora_anulaciones');

    Route::get($ruta . '/bitacora/recepciones/show/{id}', [controller::class, 'bitacoraRecepcionesShow'])
        ->name($nombreRuta . '.bitacoraRecepcionesShow')
        ->middleware('permission:' . $ruta . '.recepcion.bitacora_anulaciones');



    Route::get($ruta . '/anulaciones/{id}', [controller::class, 'anulacionesView'])
        ->name($nombreRuta . '.anulaciones_view')
        ->middleware('permission:' . $ruta . '.anulaciones_pospago');

    Route::post($ruta . '/anulaciones/', [controller::class, 'anulacionesStore'])
        ->name($nombreRuta . '.anulaciones')
        ->middleware('permission:' . $ruta . '.anulaciones_pospago');


    Route::get($ruta . '/salidas', [controller::class, 'salidas'])
        ->name($nombreRuta . '.salidas')
        ->middleware('permission:' . $ruta . '.create');

    Route::post($ruta . '/salidas', [controller::class, 'salidasAcciones'])
        ->name($nombreRuta . '.salidasAcciones')
        ->middleware('permission:' . $ruta . '.create');


    Route::get($ruta . '/reporte/pospago', [controller::class, 'formPospago'])
        ->name($nombreRuta . '.reporte_pospago')
        ->middleware('permission:' . $ruta . '.reporte_pospago');

    Route::post($ruta . '/reporte/pospago/acciones', [controller::class, 'pospagoAcciones'])
        ->name($nombreRuta . '.reporte_pospago_acciones')
        ->middleware('permission:' . $ruta . '.reporte_pospago');

    #---Defensoria del consumidor---
    Route::get('defensoria', [controller::class, 'defensoriaIndex'])
        ->name('defensoria_index')
        ->middleware('permission:recepciones.index');

    Route::post('defensoria/acciones', [controller::class, 'defensoriaAcciones'])
        ->name('defensoriaAcciones')
        ->middleware('permission:recepciones.index');
    #-------------------------------
});
