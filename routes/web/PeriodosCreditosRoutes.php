<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PeriodosCreditosController as Controller;

Route::middleware(['auth'])->group(function(){
    $ruta = 'periodos_creditos';
    $nombreRuta = $ruta;

    Route::get($ruta,[Controller::class,'index'])
        ->name($nombreRuta.'.index')
        ->middleware('permission:'.$ruta.'.index');

    Route::post($ruta,[Controller::class,'search'])
        ->name($nombreRuta.'.search')
        ->middleware('permission:'.$ruta.'.index');

    Route::get($ruta.'/crear', [Controller::class,'create'])
        ->name($nombreRuta.'.create')
        ->middleware("permission:".$ruta.".create");

    Route::post($ruta.'/store',[Controller::class,'store'])
        ->name($nombreRuta.'.store')
        ->middleware('permission:'.$ruta.'.create');
        
    Route::get($ruta.'/edit/{id}',[Controller::class,'edit'])
        ->name($nombreRuta.'.edit')
        ->middleware("permission:".$ruta.".edit");

    Route::post($ruta.'/update', [Controller::class,'update'])
        ->name($nombreRuta.'.update')
        ->middleware("permission:".$ruta.".update");

    Route::get($ruta.'/confirm/{id}',[Controller::class,'confirm'])
        ->name($nombreRuta.'.confirm')
        ->middleware("permission:".$ruta.".delete");

    Route::post($ruta.'/delete',[Controller::class,'destroy'])
        ->name($nombreRuta.'.delete')
        ->middleware("permission:".$ruta.".delete");

    Route::get($ruta.'/status/{id}',[Controller::class,'status'])
        ->name($nombreRuta.'.status')
        ->middleware('permission:'.$ruta.'.status');
});
