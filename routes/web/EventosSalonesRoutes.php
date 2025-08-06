<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventosSalonesController as EventosSalonesController;#Usar controllador

Route::middleware(['auth'])->group(function(){
    $path = 'eventos_salones';#Ruta
    $name = 'eventos_salones';#Nombre de ruta

    Route::get($path,[EventosSalonesController::class,'index'])
        ->name($name.'.index')
        ->middleware('permission:'.$path.'.index');
    Route::get($path .'/eliminar/salon/{id}',[EventosSalonesController::class,'eliminarSalon'])
        ->name($name.'.eliminar_salon')
        ->middleware('permission:'.$path.'.index');

    Route::post($path,[EventosSalonesController::class,'checkDisponibilidad'])
        ->name($name.'.checkDisponibilidad')
        ->middleware('permission:'.$path.'.index');
    
    Route::get($path.'/crear',[EventosSalonesController::class,'create'])
        ->name($name.'.create')
        ->middleware('permission:'.$path.'.create');

    Route::post($path.'/store',[EventosSalonesController::class,'store'])
        ->name($name.'.store')
        ->middleware('permission:'.$path.'.create');
    Route::post($path.'/salones',[EventosSalonesController::class,'salonesEvento'])
        ->name($name.'.salon_evento')
        ->middleware('permission:'.$path.'.index');

});
