<?php

use App\Http\Controllers\ExameController;
use App\Http\Controllers\LaboratorioController;

// Exames
Route::prefix('exames')->group(function () {
    Route::post('/requisicao', [ExameController::class, 'requisicaoExameStore'])->name('requisicoes_exames.store');
});

Route::get('/laboratorio', [LaboratorioController::class, 'index'])->name('laboratorio.index');
Route::get('/laboratorio/lancar/{id}', [LaboratorioController::class, 'lancarResultados'])->name('laboratorio.lancar');
Route::post('/laboratorio/guardar', [LaboratorioController::class, 'storeResultados'])->name('laboratorio.guardar');
Route::get('laboratorio/{id}/imprimir', [LaboratorioController::class, 'imprimirResultadosExames'])->name('laboratorio.imprimir');

