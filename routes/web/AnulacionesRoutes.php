<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AnulacionesController as Anulaciones;#Usar controllador

Route::middleware(['auth'])->group(function(){
    $path = 'anulaciones';#Ruta
    $name = $path;        #Nombre de ruta

    Route::get($path,[Anulaciones::class,'index'])
        ->name($name.'.index')
        ->middleware('permission:'.$path.'.index');

    Route::post($path,[Anulaciones::class,'search'])
        ->name($name.'.search')
        ->middleware('permission:'.$path.'.index');

    Route::get($path.'/crear',[Anulaciones::class,'create'])
        ->name($name.'.create')
        ->middleware('permission:'.$path.'.create');

    Route::post($path.'/store',[Anulaciones::class,'store'])
        ->name($name.'.store')
        ->middleware('permission:'.$path.'.create');
    
    Route::get($path.'/edit/{id}',[Anulaciones::class,'edit'])
        ->name($name.'.edit')
        ->middleware('permission:'.$path.'.edit');

    Route::post($path.'/update',[Anulaciones::class,'update'])
        ->name($name.'.update')
        ->middleware('permission:'.$path.'.edit');

    Route::get($path.'/confirm/{id}',[Anulaciones::class,'confirm'])
        ->name($name.'.confirm')
        ->middleware('permission:'.$path.'.delete');
    
    Route::post($path.'/delete',[Anulaciones::class,'destroy'])
        ->name($name.'.delete')
        ->middleware('permission:'.$path.'.delete');

    Route::get($path.'/status/{id}',[Anulaciones::class,'status'])
        ->name($name.'.status')
        ->middleware('permission:'.$path.'.status');
});
