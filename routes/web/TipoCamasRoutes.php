<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TipoCamasController as TipoCamas;#Usar controllador

Route::middleware(['auth'])->group(function(){
    $path = 'tipo/camas';#Ruta
    $name = 'tipo_camas';#Nombre de ruta

    Route::get($path,[TipoCamas::class,'index'])
        ->name($name.'.index')
        ->middleware('permission:'.$path.'.index');

    Route::post($path,[TipoCamas::class,'search'])
        ->name($name.'.search')
        ->middleware('permission:'.$path.'.index');

    Route::get($path.'/crear',[TipoCamas::class,'create'])
        ->name($name.'.create')
        ->middleware('permission:'.$path.'.create');

    Route::post($path.'/store',[TipoCamas::class,'store'])
        ->name($name.'.store')
        ->middleware('permission:'.$path.'.create');
    
    Route::get($path.'/edit/{id}',[TipoCamas::class,'edit'])
        ->name($name.'.edit')
        ->middleware('permission:'.$path.'.edit');

    Route::post($path.'/update',[TipoCamas::class,'update'])
        ->name($name.'.update')
        ->middleware('permission:'.$path.'.edit');

    Route::get($path.'/confirm/{id}',[TipoCamas::class,'confirm'])
        ->name($name.'.confirm')
        ->middleware('permission:'.$path.'.delete');
    
    Route::post($path.'/delete',[TipoCamas::class,'destroy'])
        ->name($name.'.delete')
        ->middleware('permission:'.$path.'.delete');
});
