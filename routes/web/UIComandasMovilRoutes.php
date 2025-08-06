<?php
    use Illuminate\Support\Facades\Route;

    Route::middleware(['auth'])->group(function(){
        Route::view('/listar/comandas','phone.listarComandas')->name('listar.comandas');
        Route::view('/productos/comandas','phone.productosComandas')->name('productos.comandas');
        Route::view('/listar/categorias/precios','phone.listarCategoriasPrecios')->name('listar.categorias.precios');
        Route::view('/listar/precios','phone.listarPrecios')->name('listar.precios');
        Route::view('/listar/categorias','phone.listarCategorias')->name('listar.categorias');
        Route::view('/listar/cajas','phone.listarCajas')->name('listar.cajas');
    });
