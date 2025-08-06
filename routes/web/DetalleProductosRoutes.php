<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DetalleProductosController as Detalle_productos;#Usar controllador

Route::middleware(['auth'])->group(function(){
    $path = 'detalle_productos';#Ruta
    $name = $path;          #Nombre de ruta

    Route::get($path,[Detalle_productos::class,'index'])
        ->name($name.'.index')
        ->middleware('permission:'.$path.'.index');

    Route::post($path,[Detalle_productos::class,'search'])
        ->name($name.'.search')
        ->middleware('permission:'.$path.'.index');

    Route::get($path.'/crear',[Detalle_productos::class,'create'])
        ->name($name.'.create')
        ->middleware('permission:'.$path.'.create');

    Route::post($path.'/store',[Detalle_productos::class,'store'])
        ->name($name.'.store')
        ->middleware('permission:'.$path.'.create');
     Route::post($path.'/convertir',[Detalle_productos::class,'convertirMilitrosAOnzas'])
        ->name($name.'.convertirMilitrosAOnzas')
        ->middleware('permission:'.$path.'.create');
    
    Route::get($path.'/edit/{id}',[Detalle_productos::class,'edit'])
        ->name($name.'.edit')
        ->middleware('permission:'.$path.'.edit');

    Route::post($path.'/update',[Detalle_productos::class,'update'])
        ->name($name.'.update')
        ->middleware('permission:'.$path.'.edit');

    Route::get($path.'/confirm/{id}',[Detalle_productos::class,'confirm'])
        ->name($name.'.confirm')
        ->middleware('permission:'.$path.'.delete');
    
    Route::post($path.'/delete',[Detalle_productos::class,'destroy'])
        ->name($name.'.delete')
        ->middleware('permission:'.$path.'.delete');
});
