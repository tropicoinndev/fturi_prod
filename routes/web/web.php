<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Role;

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
    //*/
});
