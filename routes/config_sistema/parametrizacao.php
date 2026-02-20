<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TipoAtendimentoController;
use App\Http\Controllers\SeguradoraController;
use App\Http\Controllers\EmpresaController;

// EMPRESAS
Route::prefix('empresas')->group(function () {
    Route::get('/', [EmpresaController::class, 'index'])->name('empresas.index');
    Route::post('/registrar', [EmpresaController::class, 'store'])->name('empresas.store');
    Route::post('/{id}/atualizar', [EmpresaController::class, 'update'])->name('empresas.update');
});

// Tipos de Atendimentos
Route::get('/configuracoes/atendimentos', [TipoAtendimentoController::class, 'index'])->name('atendimentos.index');
Route::post('/configuracoes/atendimentos/guardar', [TipoAtendimentoController::class, 'store'])->name('atendimentos.store');
Route::post('/configuracoes/atendimentos/{id}/atualizar', [TipoAtendimentoController::class, 'update'])->name('atendimentos.update');

// CONVÉNIOS E SEGURADORAS
Route::prefix('convenios')->group(function () {
    Route::get('/', [SeguradoraController::class, 'index'])->name('seguradoras.index');
    Route::post('/registrar', [SeguradoraController::class, 'store'])->name('seguradoras.store');
    Route::post('/{id}/atualizar', [SeguradoraController::class, 'update'])->name('seguradoras.update');
});

