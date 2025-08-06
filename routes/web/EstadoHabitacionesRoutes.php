<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EstadoHabitacionesController as EstadoHabitaciones;#Usar controllador

Route::middleware(['auth'])->group(function(){
    $path = 'estado/habitaciones';#Ruta
    $name = 'estado_habitaciones';#Nombre de ruta

    Route::get($path,[EstadoHabitaciones::class,'index'])
        ->name($name.'.index')
        ->middleware('permission:'.$path.'.index');

    Route::post($path,[EstadoHabitaciones::class,'search'])
        ->name($name.'.search')
        ->middleware('permission:'.$path.'.index');

    Route::get($path.'/crear',[EstadoHabitaciones::class,'create'])
        ->name($name.'.create')
        ->middleware('permission:'.$path.'.create');

    Route::post($path.'/store',[EstadoHabitaciones::class,'store'])
        ->name($name.'.store')
        ->middleware('permission:'.$path.'.create');
    
    Route::get($path.'/edit/{id}',[EstadoHabitaciones::class,'edit'])
        ->name($name.'.edit')
        ->middleware('permission:'.$path.'.edit');

    Route::post($path.'/update',[EstadoHabitaciones::class,'update'])
        ->name($name.'.update')
        ->middleware('permission:'.$path.'.edit');

    Route::get($path.'/confirm/{id}',[EstadoHabitaciones::class,'confirm'])
        ->name($name.'.confirm')
        ->middleware('permission:'.$path.'.delete');
    
    Route::post($path.'/delete',[EstadoHabitaciones::class,'destroy'])
        ->name($name.'.delete')
        ->middleware('permission:'.$path.'.delete');
});
