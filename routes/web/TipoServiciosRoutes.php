<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TipoServiciosController as TipoServicios;#Usar controllador

Route::middleware(['auth'])->group(function(){
    $path = 'tipo_servicios';#Ruta
    $name = $path;           #Nombre de ruta

    Route::get($path,[TipoServicios::class,'index'])
        ->name($name.'.index')
        ->middleware('permission:'.$path.'.index');

    Route::post($path,[TipoServicios::class,'search'])
        ->name($name.'.search')
        ->middleware('permission:'.$path.'.index');

    Route::get($path.'/crear',[TipoServicios::class,'create'])
        ->name($name.'.create')
        ->middleware('permission:'.$path.'.create');

    Route::post($path.'/store',[TipoServicios::class,'store'])
        ->name($name.'.store')
        ->middleware('permission:'.$path.'.create');
    
    Route::get($path.'/edit/{id}',[TipoServicios::class,'edit'])
        ->name($name.'.edit')
        ->middleware('permission:'.$path.'.edit');

    Route::post($path.'/update',[TipoServicios::class,'update'])
        ->name($name.'.update')
        ->middleware('permission:'.$path.'.edit');

    Route::get($path.'/confirm/{id}',[TipoServicios::class,'confirm'])
        ->name($name.'.confirm')
        ->middleware('permission:'.$path.'.delete');
    
    Route::post($path.'/delete',[TipoServicios::class,'destroy'])
        ->name($name.'.delete')
        ->middleware('permission:'.$path.'.delete');

    Route::get($path.'/status/{id}',[TipoServicios::class,'status'])
        ->name($name.'.status')
        ->middleware('permission:'.$path.'.status');
});
