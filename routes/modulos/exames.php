<?php

use App\Http\Controllers\ExameController;

// Exames
Route::prefix('exames')->group(function () {
    Route::post('/requisicao', [ExameController::class, 'requisicaoExameStore'])->name('requisicoes_exames.store');
});

