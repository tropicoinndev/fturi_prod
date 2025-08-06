<?php

use App\Http\Controllers\LibrosContablesController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(
    function () {
        Route::get('libros/contribuyentes', [LibrosContablesController::class, 'contribuyentes'])
            ->name('libros.contribuyentes')
            ->middleware('permission:libros.contribuyentes');

        Route::post('libros/contribuyentes', [LibrosContablesController::class, 'getReportContribuyentes'])
            ->name('libros.contribuyentes_report')
            ->middleware('permission:libros.contribuyentes');

        Route::get('libros/consumidor/final', [LibrosContablesController::class, 'consumidor'])
            ->name('libros.consumidor')
            ->middleware('permission:libros.consumidor');

        Route::post('libros/consumidor/final', [LibrosContablesController::class, 'getReportConsumidor'])
            ->name('libros.consumidor_report')
            ->middleware('permission:libros.consumidor');


        Route::get('anexos/contribuyentes', [LibrosContablesController::class, 'anexosContribuyentes'])
            ->name('anexos.contribuyentes')
            ->middleware('permission:anexos.contribuyentes');

        Route::post('anexos/contribuyentes', [LibrosContablesController::class, 'anexosContribuyentesAccion'])
            ->name('anexos.contribuyentes_accion')
            ->middleware('permission:anexos.contribuyentes');

        Route::get('anexos/consumidor', [LibrosContablesController::class, 'anexosConsumidor'])
            ->name('anexos.consumidor')
            ->middleware('permission:anexos.consumidor');

        Route::post('anexos/consumidor', [LibrosContablesController::class, 'anexosConsumidorAccion'])
            ->name('anexos.consumidor_accion')
            ->middleware('permission:anexos.consumidor');

        Route::get('anexos/invalidados', [LibrosContablesController::class, 'anexosInvalidados'])
            ->name('anexos.invalidados')
            ->middleware('permission:anexos.invalidados');

        Route::post('anexos/invalidados', [LibrosContablesController::class, 'anexosInvalidadosAccion'])
            ->name('anexos.invalidados_accion')
            ->middleware('permission:anexos.invalidados');

        Route::get('anexos/sujetos/excluidos', [LibrosContablesController::class, 'anexosSujetosExcluidos'])
            ->name('anexos.sujetos')
            ->middleware('permission:anexos.sujetos_excluidos');

        Route::post('anexos/sujetos/excluidos', [LibrosContablesController::class, 'anexosSujetosExcluidosAccion'])
            ->name('anexos.sujetos_accion')
            ->middleware('permission:anexos.sujetos_excluidos');
    }
);
