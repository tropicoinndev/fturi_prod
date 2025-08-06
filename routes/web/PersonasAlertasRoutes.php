<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PersonasAlertasController as Controller;

Route::middleware(['auth'])->group(function () {
    $path = 'personas/alertas';
    $name = 'personas_alertas';

    Route::get($path, [Controller::class, 'index'])
        ->name($name . '.index')
        ->middleware('permission:' . $path . '.index');

    Route::post($path, [Controller::class, 'search'])
        ->name($name . '.search')
        ->middleware('permission:' . $path . '.index');

    Route::get($path . '/crear', [Controller::class, 'create'])
        ->name($name . '.create')
        ->middleware('permission:' . $path . '.create');

    Route::post($path . '/store', [Controller::class, 'store'])
        ->name($name . '.store')
        ->middleware('permission:' . $path . '.create');

    Route::get($path . '/edit/{id}', [Controller::class, 'edit'])
        ->name($name . '.edit')
        ->middleware('permission:' . $path . '.edit');

    Route::post($path . '/update', [Controller::class, 'update'])
        ->name($name . '.update')
        ->middleware('permission:' . $path . '.edit');

    Route::get($path . '/confirm/{id}', [Controller::class, 'confirm'])
        ->name($name . '.confirm')
        ->middleware('permission:' . $path . '.delete');

    Route::post($path . '/delete', [Controller::class, 'destroy'])
        ->name($name . '.delete')
        ->middleware('permission:' . $path . '.delete');

    Route::get($path.'/ilicita/{id}',[Controller::class,'ilicita'])
        ->name($name.'.ilicita')
        ->middleware('permission:'.$path.'.index');

    Route::get($path.'/peps/{id}',[Controller::class,'peps'])
        ->name($name.'.peps')
        ->middleware('permission:'.$path.'.index');

    Route::get($path.'/status/{id}',[Controller::class,'status'])
        ->name($name.'.status')
        ->middleware('permission:'.$path.'.index');

    Route::get($path.'/descargar/formato/{format}',[Controller::class,'descargarFormato'])
        ->name($name.'.descargarFormato')
        ->middleware('permission:'.$path.'.index');

    Route::post($path.'/import/data',[Controller::class,'importData'])
        ->name($name.'.importData')
        ->middleware('permission:'.$path.'.index');

    Route::get($path.'/export/data/{extention}',[Controller::class,'exportData'])
        ->name($name.'.exportData')
        ->middleware('permission:'.$path.'.index');

    /**
     *Path de pruebas de APIS
     *
    Route::get('alertas/nombre/api/{busqueda}', [Controller::class, 'apiNombres']);
    Route::get('alertas/identificacion/api/{busqueda}', [Controller::class, 'apiIdentificaciones']);
     */

    Route::get($path.'/nombre/api/{busqueda}',[Controller::class,'apiNombres'])
        ->name($name.'.apiNombres')
        ->middleware('permission:'.$path.'.index');

    Route::get($path.'/identificacion/api/{busqueda}',[Controller::class,'apiIdentificaciones'])
        ->name($name.'.apiIdentificaciones')
        ->middleware('permission:'.$path.'.index');
});
