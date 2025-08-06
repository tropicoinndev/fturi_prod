<?php

use App\Http\Controllers\ConceptosSujetoExcluidosController;
use App\Http\Controllers\DetallesSujetoExcluidosController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\isLoginCaja;
use App\Http\Controllers\SujetoExcluidoController as controller; #Usar controllador

Route::middleware(['auth'])->group(
    function () {
        $path = 'sujeto/excluido';
        $name = 'sujeto_excluido';
        Route::get($path . '/api/reenviar/{id}', [controller::class, 'reenvioDte'])
            ->name($name . '.api_reenviar')
            ->middleware('permission:' . $path . '.fe');
    }
);
Route::middleware(['auth', isLoginCaja::class])->group(function () {
    $path = 'sujeto/excluido';
    $name = 'sujeto_excluido';


    Route::get($path, [controller::class, 'index'])
        ->name($name . '.index')
        ->middleware('permission:' . $name . '.index');

    Route::post($path, [controller::class, 'search'])
        ->name($name . '.search')
        ->middleware('permission:' . $name . '.index');

    Route::get($path . '/crear/', [controller::class, 'create'])
        ->name($name . '.create')
        ->middleware('permission:' . $name . '.create');


    Route::post($path . '/store', [controller::class, 'store'])
        ->name($name . '.store')
        ->middleware('permission:' . $name . '.create');

    Route::get($path . '/detalle/{id}', [controller::class, 'detalle'])
        ->name($name . '.detalles')
        ->middleware('permission:' . $name . '.create');

    Route::get($path . '/completado/{id}', [controller::class, 'completado'])
        ->name($name . '.completado')
        ->middleware('permission:' . $name . '.create');

    Route::post($path . '/detalle/', [DetallesSujetoExcluidosController::class, 'store'])
        ->name($name . '.detalles_store')
        ->middleware('permission:' . $name . '.create');

    Route::post($path . '/autorizar/', [controller::class, 'autorizar'])
        ->name($name . '.autorizar')
        ->middleware('permission:' . $name . '.autorizar');
    Route::post($path . '/editar/clasificacion/', [controller::class, 'editarClasificacion'])
        ->name($name . '.editar_clasificacion')
        ->middleware('permission:' . $name . '.autorizar');


    Route::get($path . '/detalle/delete/{id}', [DetallesSujetoExcluidosController::class, 'destroy'])
        ->name($name . '.detalles_delete')
        ->middleware('permission:' . $name . '.create');

    Route::post($path . '/detalle/search/concepto', [ConceptosSujetoExcluidosController::class, 'search'])
        ->name($name . '.detalles_search_concepto')
        ->middleware('permission:' . $name . '.create');

    Route::get($path . '/resultado/{id}', [controller::class, 'resultado'])
        ->name($name . '.result')
        ->middleware('permission:' . $name . '.create');

    Route::get($path . '/show/{id}', [controller::class, 'show'])
        ->name($name . '.show')
        ->middleware('permission:' . $name . '.index');

    Route::get($path . '/edit/{id}', [controller::class, 'edit'])
        ->name($name . '.edit')
        ->middleware('permission:' . $name . '.edit');

    Route::post($path . '/update', [controller::class, 'update'])
        ->name($name . '.update')
        ->middleware('permission:' . $name . '.edit');

    Route::get($path . '/confirm/{id}', [controller::class, 'confirm'])
        ->name($name . '.confirm')
        ->middleware('permission:' . $name . '.delete');

    Route::post($path . '/delete', [controller::class, 'destroy'])
        ->name($name . '.delete')
        ->middleware('permission:' . $name . '.delete');
});
