<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TipoPagosController as TipoPagos;#Usar controllador

Route::middleware(['auth'])->group(function(){
    $path = 'tipo_pagos';#Ruta
    $name = $path;       #Nombre de ruta

    Route::get($path,[TipoPagos::class,'index'])
        ->name($name.'.index')
        ->middleware('permission:'.$path.'.index');

    Route::post($path,[TipoPagos::class,'search'])
        ->name($name.'.search')
        ->middleware('permission:'.$path.'.index');

    Route::get($path.'/crear',[TipoPagos::class,'create'])
        ->name($name.'.create')
        ->middleware('permission:'.$path.'.create');

    Route::post($path.'/store',[TipoPagos::class,'store'])
        ->name($name.'.store')
        ->middleware('permission:'.$path.'.create');
    
    /* Route::get($path.'/edit/{id}',[TipoPagos::class,'edit'])
        ->name($name.'.edit')
        ->middleware('permission:'.$path.'.edit');

    Route::post($path.'/update',[TipoPagos::class,'update'])
        ->name($name.'.update')
        ->middleware('permission:'.$path.'.edit'); */

    Route::get($path.'/confirm/{id}',[TipoPagos::class,'confirm'])
        ->name($name.'.confirm')
        ->middleware('permission:'.$path.'.delete');
    
    Route::post($path.'/delete',[TipoPagos::class,'destroy'])
        ->name($name.'.delete')
        ->middleware('permission:'.$path.'.delete');
});
