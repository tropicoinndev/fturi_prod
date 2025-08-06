<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CorrelativoSucursalController as CoSucursal;

Route::middleware(['auth'])->group(function(){
    $path = 'correlativo/sucursal';
    $name = 'correlativo_sucursal';

    Route::post($path.'/get/data',[CoSucursal::class,'getData'])
        ->name($name.'.getData')
        ->middleware('permission:'.$name.'.index');

    Route::post($path.'/store',[CoSucursal::class,'store'])
        ->name($name.'.store')
        ->middleware('permission:'.$name.'.create');

    Route::post($path.'/update',[CoSucursal::class,'update'])
        ->name($name.'.update')
        ->middleware('permission:'.$name.'.update');

    /*Route::get($path.'/status/{id}',[CoSucursal::class,'status'])
        ->name($name.'.status')
        ->middleware('permission:'.$name.'.status');*/
    Route::post($path.'/status',[CoSucursal::class,'status'])
        ->name($name.'.status')
        ->middleware('permission:'.$name.'.status');

    /*Route::get($path.'/confirm/{id}',[CoSucursal::class,'confirm'])
        ->name($name.'.confirm')
        ->middleware('permission:'.$name.'.delete');
    Route::post($path.'/delete',[CoSucursal::class,'destroy'])
        ->name($name.'.delete')
        ->middleware('permission:'.$name.'.delete');*/
    Route::post($path.'/delete',[CoSucursal::class,'destroy'])
        ->name($name.'.delete')
        ->middleware('permission:'.$name.'.delete');

    /*Route::get($path.'/restart/{id}',[CoSucursal::class,'restart'])
        ->name($name.'.restart')
        ->middleware('permission:'.$name.'.restart');*/
    Route::post($path.'/restart',[CoSucursal::class,'restart'])
        ->name($name.'.restart')
        ->middleware('permission:'.$name.'.restart');

    #Solo para prueba, debe eliminarse
    Route::get('/formato/dte/ccf',[CoSucursal::class,'formatoDteCcf'])
        ->name('formatoDteCcf')
        ->middleware('permission:formatoDTE');
});
