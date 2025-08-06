<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientesPersonasController as Controller;

Route::middleware(['auth'])->group(function(){
    $path = 'clientes/personas';
    $name = 'clientes_personas';

    Route::post($path.'/api/store',[Controller::class,'apiStore'])
        ->name($name.'.apiStore')
        ->middleware('permission:'.$path.'.apiStore');

    Route::post($path.'/api/update',[Controller::class,'apiUpdate'])
        ->name($name.'.apiUpdate')
        ->middleware('permission:'.$path.'.apiUpdate');

    Route::post($path.'/api/destroy',[Controller::class,'apiDestroy'])
        ->name($name.'.apiDestroy')
        ->middleware('permission:'.$path.'.apiDestroy');
});
