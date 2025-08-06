<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HabitacionCamasController as HabitacionCama;#Usar controllador

Route::middleware(['auth'])->group(function(){
    $path = 'habitacion_camas';#Ruta
    $name = $path;  #Nombre de ruta

    Route::get($path,[HabitacionCama::class,'index'])
        ->name($name.'.index')
        ->middleware('permission:'.$path.'.index');

    Route::post($path,[HabitacionCama::class,'search'])
        ->name($name.'.search')
        ->middleware('permission:'.$path.'.index');

    Route::get($path.'/crear',[HabitacionCama::class,'create'])
        ->name($name.'.create')
        ->middleware('permission:'.$path.'.create');

    Route::post($path.'/store',[HabitacionCama::class,'store'])
        ->name($name.'.store')
        ->middleware('permission:'.$path.'.create');
    
    Route::get($path.'/edit/{id}',[HabitacionCama::class,'edit'])
        ->name($name.'.edit')
        ->middleware('permission:'.$path.'.edit');

    Route::post($path.'/update',[HabitacionCama::class,'update'])
        ->name($name.'.update')
        ->middleware('permission:'.$path.'.edit');

    Route::get($path.'/confirm/{id}',[HabitacionCama::class,'confirm'])
        ->name($name.'.confirm')
        ->middleware('permission:'.$path.'.delete');
    
    Route::post($path.'/delete',[HabitacionCama::class,'destroy'])
        ->name($name.'.delete')
        ->middleware('permission:'.$path.'.delete');

    Route::get($path.'/status/{id}',[HabitacionCama::class,'status'])
        ->name($name.'.status')
        ->middleware('permission:'.$path.'.status');
});
