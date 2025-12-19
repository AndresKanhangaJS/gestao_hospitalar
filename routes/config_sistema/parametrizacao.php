<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TipoAtendimentoController;

// Tipos de Atendimentos
Route::get('/configuracoes/atendimentos', [TipoAtendimentoController::class, 'index'])->name('atendimentos.index');
Route::post('/configuracoes/atendimentos/guardar', [TipoAtendimentoController::class, 'store'])->name('atendimentos.store');
Route::put('/configuracoes/atendimentos/{id}/atualizar', [TipoAtendimentoController::class, 'update'])->name('atendimentos.update');

