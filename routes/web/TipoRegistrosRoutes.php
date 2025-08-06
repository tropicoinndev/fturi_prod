<?php

use App\Http\Controllers\TipoRegistrosController as TipoRegistros;#Usar controllador
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function(){
    $path = 'tipo_registros';#Ruta
    $name = $path;           #Nombre de ruta

    Route::get($path,[TipoRegistros::class,'index'])
        ->name($name.'.index')
        ->middleware('permission:'.$path.'.index');

    Route::post($path,[TipoRegistros::class,'search'])
        ->name($name.'.search')
        ->middleware('permission:'.$path.'.index');

    Route::get($path.'/crear',[TipoRegistros::class,'create'])
        ->name($name.'.create')
        ->middleware('permission:'.$path.'.create');

    Route::post($path.'/store',[TipoRegistros::class,'store'])
        ->name($name.'.store')
        ->middleware('permission:'.$path.'.create');

    Route::get($path.'/edit/{id}',[TipoRegistros::class,'edit'])
        ->name($name.'.edit')
        ->middleware('permission:'.$path.'.edit');

    Route::post($path.'/update',[TipoRegistros::class,'update'])
        ->name($name.'.update')
        ->middleware('permission:'.$path.'.edit');

    Route::get($path.'/confirm/{id}',[TipoRegistros::class,'confirm'])
        ->name($name.'.confirm')
        ->middleware('permission:'.$path.'.delete');

    Route::post($path.'/delete',[TipoRegistros::class,'destroy'])
        ->name($name.'.delete')
        ->middleware('permission:'.$path.'.delete');
});
