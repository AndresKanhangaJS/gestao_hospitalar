<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TipoAtendimentoController;
use App\Http\Controllers\SeguradoraController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\ExameController;

// EMPRESAS
Route::prefix('empresas')->group(function () {
    Route::get('/', [EmpresaController::class, 'index'])->name('empresas.index');
    Route::post('/registrar', [EmpresaController::class, 'store'])->name('empresas.store');
    Route::post('/{id}/atualizar', [EmpresaController::class, 'update'])->name('empresas.update');
});

// Tipos de Atendimentos
Route::prefix('tipos-atendimentos')->group(function () {
    Route::get('/', [TipoAtendimentoController::class, 'index'])->name('tipos_atendimentos.index');
    Route::post('/registrar', [TipoAtendimentoController::class, 'store'])->name('tipos_atendimentos.store');
    Route::post('/{id}/atualizar', [TipoAtendimentoController::class, 'update'])->name('tipos_atendimentos.update');
});

// Exames
Route::prefix('exames')->group(function () {
    Route::get('/', [ExameController::class, 'index'])->name('exames.index');
    Route::post('/registrar', [ExameController::class, 'store'])->name('exames.store');
    Route::post('/{id}/atualizar', [ExameController::class, 'update'])->name('exames.update');

    // Rota para buscar os itens via AJAX ao abrir o modal
    Route::get('/{id}/itens', [ExameController::class, 'getItens'])->name('exames.itens');

    // Rotas para Categorias de Exames
    Route::prefix('categorias')->group(function () {
        Route::post('/registrar', [ExameController::class, 'storeExameCat'])->name('exame_categorias.store');
    });
});

// CONVÉNIOS E SEGURADORAS
Route::prefix('convenios')->group(function () {
    Route::get('/', [SeguradoraController::class, 'index'])->name('seguradoras.index');
    Route::post('/registrar', [SeguradoraController::class, 'store'])->name('seguradoras.store');
    Route::post('/{id}/atualizar', [SeguradoraController::class, 'update'])->name('seguradoras.update');
});

