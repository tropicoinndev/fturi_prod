<?php

use App\Http\Controllers\TipoComprobantesController as TipoComprobantes;#Usar controllador
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function(){
    $path = 'tipo_comprobantes';#Ruta
    $name = $path;              #Nombre de ruta

    Route::get($path,[TipoComprobantes::class,'index'])
        ->name($name.'.index')
        ->middleware('permission:'.$path.'.index');

    Route::post($path,[TipoComprobantes::class,'search'])
        ->name($name.'.search')
        ->middleware('permission:'.$path.'.index');

    Route::get($path.'/crear',[TipoComprobantes::class,'create'])
        ->name($name.'.create')
        ->middleware('permission:'.$path.'.create');

    Route::post($path.'/store',[TipoComprobantes::class,'store'])
        ->name($name.'.store')
        ->middleware('permission:'.$path.'.create');

     Route::get($path.'/edit/{id}',[TipoComprobantes::class,'edit'])
        ->name($name.'.edit')
        ->middleware('permission:'.$path.'.edit');

    Route::post($path.'/update',[TipoComprobantes::class,'update'])
        ->name($name.'.update')
        ->middleware('permission:'.$path.'.edit');

    Route::get($path.'/confirm/{id}',[TipoComprobantes::class,'confirm'])
        ->name($name.'.confirm')
        ->middleware('permission:'.$path.'.delete');

    Route::post($path.'/delete',[TipoComprobantes::class,'destroy'])
        ->name($name.'.delete')
        ->middleware('permission:'.$path.'.delete');
});
