<?php

use App\Http\Controllers\SucursalesController as Sucursales;#Usar controllador
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function(){
    $path = 'sucursales';#Ruta
    $name = $path;       #Nombre de ruta

    Route::get($path,[Sucursales::class,'index'])
        ->name($name.'.index')
        ->middleware('permission:'.$path.'.index');

    Route::post($path,[Sucursales::class,'search'])
        ->name($name.'.search')
        ->middleware('permission:'.$path.'.index');

    Route::get($path.'/crear',[Sucursales::class,'create'])
        ->name($name.'.create')
        ->middleware('permission:'.$path.'.create');

    Route::post($path.'/store',[Sucursales::class,'store'])
        ->name($name.'.store')
        ->middleware('permission:'.$path.'.create');

    Route::get($path.'/edit/{id}',[Sucursales::class,'edit'])
        ->name($name.'.edit')
        ->middleware('permission:'.$path.'.edit');

    Route::post($path.'/update',[Sucursales::class,'update'])
        ->name($name.'.update')
        ->middleware('permission:'.$path.'.edit');

    Route::get($path.'/confirm/{id}',[Sucursales::class,'confirm'])
        ->name($name.'.confirm')
        ->middleware('permission:'.$path.'.delete');

    Route::post($path.'/delete',[Sucursales::class,'destroy'])
        ->name($name.'.delete')
        ->middleware('permission:'.$path.'.delete');

    Route::get($path.'/show/{id}',[Sucursales::class,'show'])
        ->name($name.'.show')
        ->middleware('permission:'.$path.'.show');
});
