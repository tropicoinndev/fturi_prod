<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UbicacionHabitacionesController as UbicacionHabitaciones;#Usar controllador

Route::middleware(['auth'])->group(function(){
    $path = 'ubicacion/habitaciones';#Ruta
    $name = 'ubicacion_habitaciones';#Nombre de ruta

    Route::get($path,[UbicacionHabitaciones::class,'index'])
        ->name($name.'.index')
        ->middleware('permission:'.$path.'.index');

    Route::post($path,[UbicacionHabitaciones::class,'search'])
        ->name($name.'.search')
        ->middleware('permission:'.$path.'.index');

    Route::get($path.'/crear',[UbicacionHabitaciones::class,'create'])
        ->name($name.'.create')
        ->middleware('permission:'.$path.'.create');

    Route::post($path.'/store',[UbicacionHabitaciones::class,'store'])
        ->name($name.'.store')
        ->middleware('permission:'.$path.'.create');
    
    Route::get($path.'/edit/{id}',[UbicacionHabitaciones::class,'edit'])
        ->name($name.'.edit')
        ->middleware('permission:'.$path.'.edit');

    Route::post($path.'/update',[UbicacionHabitaciones::class,'update'])
        ->name($name.'.update')
        ->middleware('permission:'.$path.'.edit');

    Route::get($path.'/confirm/{id}',[UbicacionHabitaciones::class,'confirm'])
        ->name($name.'.confirm')
        ->middleware('permission:'.$path.'.delete');
    
    Route::post($path.'/delete',[UbicacionHabitaciones::class,'destroy'])
        ->name($name.'.delete')
        ->middleware('permission:'.$path.'.delete');
});
