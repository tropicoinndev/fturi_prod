<?php

use App\Http\Controllers\ApiMhController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\isLoginCaja;
use App\Http\Controllers\ComprobantesController as Comprobantes; #Usar controllador
use App\Http\Controllers\DtesController;

Route::middleware(['auth'])->group(
    function () {
        $path = 'comprobantes'; #Ruta
        $name = $path;
        Route::get('login/api/mh', [ApiMhController::class, 'loginMh'])
            ->name('mh.login')
            ->middleware('permission:comprobantes.admin');

        Route::get($path . '/api/reenviar/{id}', [Comprobantes::class, 'reenvioDte'])
            ->name($name . '.api_reenviar')
            ->middleware('permission:' . $path . '.fe');


        Route::get($path . '/mail/{id}', [Comprobantes::class, 'reenviarMail'])
            ->name($name . '.api_sendMail')
            ->middleware('permission:' . $path . '.fe');

        Route::get($path . '/pdf/{id}', [Comprobantes::class, 'pdfDte'])
            ->name($name . '.api_pdfDte')
            ->middleware('permission:' . $path . '.fe');

        Route::post($path . '/reenviar/dte', [Comprobantes::class, 'sendMail'])
            ->name($name . '.api_sendMailNotRegister')
            ->middleware('permission:' . $path . '.fe');

        Route::get($path . '/admin/turnos', [Comprobantes::class, 'cambioTurnos'])
            ->name($name . '.cambioTurnos')
            ->middleware('permission:' . $path . '.admin_turnos');

        Route::post($path . '/admin/turnos/seach', [Comprobantes::class, 'cambioTurnosSearch'])
            ->name($name . '.cambioTurnosSearch')
            ->middleware('permission:' . $path . '.admin_turnos');

        Route::post($path . '/admin/turnos/store', [Comprobantes::class, 'cambioTurnosStore'])
            ->name($name . '.api_cambioTurnoStore')
            ->middleware('permission:' . $path . '.admin_turnos');

        Route::get($path . '/admin/apertura/turnos', [Comprobantes::class, 'aperturaTurnos'])
            ->name($name . '.AperturaTurnos')
            ->middleware('permission:' . $path . '.admin_turnos');

        Route::post($path . '/admin/apertura/turnos/seach', [Comprobantes::class, 'aperturaTurnosSearch'])
            ->name($name . '.AperturaTurnosSearch')
            ->middleware('permission:' . $path . '.admin_turnos');

        Route::post($path . '/admin/apertura/turnos/store', [Comprobantes::class, 'aperturaTurnosStore'])
            ->name($name . '.api_aperturaTurnoStore')
            ->middleware('permission:' . $path . '.admin_turnos');

        Route::get('dtes/resultado/{id}', [DtesController::class, 'resultado'])
            ->name('dtes.resultado')
            ->middleware('permission:' . $path . '.create');

        Route::get($path . '/show/{id}', [Comprobantes::class, 'show'])
            ->name($name . '.show')
            ->middleware('permission:' . $path . '.index');

        Route::post($path . '/forma/pago/editar', [Comprobantes::class, 'comprobantesFormaPago'])
            ->name($name . '.editar_forma_pago')
            ->middleware('permission:' . $path . '.admin');

        Route::post($path . '/aplicar/anticipo', [Comprobantes::class, 'aplicarAnticipo'])
            ->name($name . '.aplicar_anticipo')
            ->middleware('permission:' . $path . '.admin');

        Route::get($path . '/desaplicar/anticipo/{id}', [Comprobantes::class, 'desaplicarAnticipo'])
            ->name($name . '.desaplicar_anticipo')
            ->middleware('permission:' . $path . '.admin');

        Route::post($path . '/editar/registro', [Comprobantes::class, 'updateRegistro'])
            ->name($name . '.updateRegistro')
            ->middleware('permission:' . $path . '.admin');

        Route::get($path . '/clear/registro/{id}', [Comprobantes::class, 'clearRegistro'])
            ->name($name . '.clearRegistro')
            ->middleware('permission:' . $path . '.admin');

        Route::get($path, [Comprobantes::class, 'index'])
            ->name($name . '.index')
            ->middleware('permission:' . $path . '.consulta');

        Route::post($path, [Comprobantes::class, 'search'])
            ->name($name . '.search')
            ->middleware('permission:' . $path . '.consulta');

        Route::get($path . '/detalles/{id}', [Comprobantes::class, 'detalle'])
            ->name($name . '.detalles')
            ->middleware('permission:' . $path . '.consulta');

        Route::post($path . "/activar", [Comprobantes::class, 'activarComprobante'])
            ->name($name . '.activarComprobante')
            ->middleware('permission:' . $path . '.admin');
    }
);
Route::middleware(['auth', isLoginCaja::class])->group(function () {
    $path = 'comprobantes'; #Ruta
    $name = $path;         #Nombre de ruta



    Route::get($path . '/crear/{id}/{tipo}/{tipo_comprobante}', [Comprobantes::class, 'create'])
        ->name($name . '.create')
        ->middleware('permission:' . $path . '.create');

    Route::get($path . '/crear/{id}', [Comprobantes::class, 'cobro'])
        ->name($name . '.cobro')
        ->middleware('permission:' . $path . '.create');

    Route::post($path . '/store', [Comprobantes::class, 'store'])
        ->name($name . '.store')
        ->middleware('permission:' . $path . '.create');

    Route::post($path . '/descuento', [Comprobantes::class, 'descuento'])
        ->name($name . '.descuento')
        ->middleware('permission:' . $path . '.create');

    Route::get($path . '/resultado/{id}/{recibe}', [Comprobantes::class, 'resultado'])
        ->name($name . '.resultado')
        ->middleware('permission:' . $path . '.create');

    Route::get($path . '/resultado/{id}', [Comprobantes::class, 'resultado'])
        ->name($name . '.result')
        ->middleware('permission:' . $path . '.create');



    Route::get($path . '/edit/{id}', [Comprobantes::class, 'edit'])
        ->name($name . '.edit')
        ->middleware('permission:' . $path . '.edit');

    Route::post($path . '/update', [Comprobantes::class, 'update'])
        ->name($name . '.update')
        ->middleware('permission:' . $path . '.edit');

    Route::get($path . '/confirm/{id}', [Comprobantes::class, 'confirm'])
        ->name($name . '.confirm')
        ->middleware('permission:' . $path . '.delete');

    Route::post($path . '/delete', [Comprobantes::class, 'destroy'])
        ->name($name . '.delete')
        ->middleware('permission:' . $path . '.delete');

    Route::post($path . '/cliente/update', [Comprobantes::class, 'clientes'])
        ->name($name . '.cliente_update')
        ->middleware('permission:' . $path . '.clientes');


    Route::get($path . '/nota/credito/{id}', [Comprobantes::class, 'createNotaCredito'])
        ->name($name . '.nota_credito_create')
        ->middleware('permission:' . $path . '.nota_credito');

    Route::post($path . 'store/nota/credito/', [Comprobantes::class, 'storeNotaCredito'])
        ->name($name . '.nota_credito_store')
        ->middleware('permission:' . $path . '.nota_credito');


    /* Route::get($path . '/status/{id}', [Comprobantes::class, 'status'])
        ->name($name . '.status')
        ->middleware('permission:' . $path . '.status');*/
});
