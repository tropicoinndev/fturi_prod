<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdministrarHabitacionesController as AdminHabitaciones;#Usar controllador

Route::middleware(['auth'])->group(function(){
    $path = 'administrar/habitaciones';#Ruta
    $name = 'administrar_habitaciones';#Nombre de ruta

    Route::get($path,[AdminHabitaciones::class,'index'])
        ->name($name.'.index')
        ->middleware('permission:'.$path.'.index');

    Route::post($path,[AdminHabitaciones::class,'search'])
        ->name($name.'.search')
        ->middleware('permission:'.$path.'.index');

    Route::get($path.'/crear',[AdminHabitaciones::class,'create'])
        ->name($name.'.create')
        ->middleware('permission:'.$path.'.create');

    Route::post($path.'/store',[AdminHabitaciones::class,'store'])
        ->name($name.'.store')
        ->middleware('permission:'.$path.'.create');

    Route::get($path.'/show/{id}',[AdminHabitaciones::class,'show'])
        ->name($name.'.show')
        ->middleware('permission:'.$path.'.show');
    
    Route::get($path.'/edit/{id}',[AdminHabitaciones::class,'edit'])
        ->name($name.'.edit')
        ->middleware('permission:'.$path.'.edit');

    Route::post($path.'/update',[AdminHabitaciones::class,'update'])
        ->name($name.'.update')
        ->middleware('permission:'.$path.'.edit');

    Route::get($path.'/confirm/{id}',[AdminHabitaciones::class,'confirm'])
        ->name($name.'.confirm')
        ->middleware('permission:'.$path.'.delete');
    
    Route::post($path.'/delete',[AdminHabitaciones::class,'destroy'])
        ->name($name.'.delete')
        ->middleware('permission:'.$path.'.delete');

    Route::get($path.'/delete/cama/{id}',[AdminHabitaciones::class,'deleteCama'])
        ->name($name.'.deleteCama')
        ->middleware('permission:'.$path.'.delete');

    Route::post($path.'/store/cama/habitacion',[AdminHabitaciones::class,'apiStoreCamaHabitacion'])
        ->name($name.'.apiStoreCamaHabitacion')
        ->middleware(['permission:'.$path.'.create']);
});
