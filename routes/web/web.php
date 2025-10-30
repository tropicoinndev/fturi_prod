<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Role;
use App\Events\PedidosCocina;

Route::middleware('auth')->group(function () {

    /*
    Route::get('install_perm', function () {
        $user = Auth::user();
        if (Role::where('name', 'superAdmin')->count() == 0) {
            Role::create(['name' => 'superAdmin']);
        }
        return $user->assignRole('superAdmin');
    })->name('install');

    Route::get('prueba/permisos', function () {
        return 'Ok';
    })
        ->name('install.test')
        ->middleware("permission:install.index");
    /*/

    Route::get('api/time', function () {
        return response()->json(['datetime' => date("Y-m-d H:i:s"), 'time' => date("H:i:s"), 'Y-m-d']);
    })->name('api.time');
    //*
    Route::get('register', function () {
        return 'No disponible';
       
    });

   // Route::get('pedidos_test', function(){
        /**
         * Para futuras pruebas de envios de notificacions a cocina cambiar 
         * $detalleCocina con ID de destalles que esten habilitados y existan. 
         * Esto no muestra una notificacion, esto solo hace que el socket se 
         * actualice y la app busque cambios.
         * Ademas sonara la notificacion por estar en broadcast publico.
         */
       /* $detalleCocina =[119632, 119635];
        try {
            echo 'Realizando pedido...';
            //var_dump(session('caja'));
            event(new PedidosCocina($detalleCocina, session('caja'), Auth::user()->name));
            return 'Pedido realizado.';

        } catch (\Throwable $th) {
            throw $th;
        }*/
        
   // });
    //*/
});
