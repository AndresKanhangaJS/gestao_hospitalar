<?php

use App\Http\Controllers\PacienteController;
use App\Http\Controllers\EpisodioController;
use App\Http\Controllers\NotaClinicaController;
use App\Http\Controllers\TipoAtendimentoController;

// Pacientes
Route::prefix('pacientes')->group(function () {
    Route::get('/', [PacienteController::class, 'index'])->name('pacientes.index');
    Route::get('/registar', [PacienteController::class, 'create'])->name('pacientes.create');
    Route::post('/store', [PacienteController::class, 'store'])->name('pacientes.store');
    Route::get('/{paciente}/editar', [PacienteController::class, 'edit'])->name('pacientes.edit');
    Route::get('/{paciente}/detalhes', [PacienteController::class, 'show'])->name('pacientes.show');
    Route::put('/{paciente}/actualizar', [PacienteController::class, 'update'])->name('pacientes.update');
    Route::delete('/{paciente}', [PacienteController::class, 'destroy'])->name('pacientes.destroy');
});
// Episódios (Atendimentos)
Route::prefix('episodios')->group(function () {
    Route::get('/', [EpisodioController::class, 'index'])->name('episodios.index');
    Route::get('/novo/{paciente}', [EpisodioController::class, 'create'])->name('episodios.create');
    Route::post('/store', [EpisodioController::class, 'store'])->name('episodios.store');
    Route::get('/{episodio}/detalhes', [EpisodioController::class, 'show'])->name('episodios.show');
    Route::delete('/{episodio}/eliminar', [EpisodioController::class, 'destroy'])->name('episodios.destroy');
    Route::put('/{episodio}/finalizar', [EpisodioController::class, 'finalizar'])->name('episodios.finalizar');
});

// Notas Clínicas (Prontuário)
Route::prefix('notas-clinicas')->group(function () {
    Route::post('/registar', [NotaClinicaController::class, 'store'])->name('notas_clinicas.store');
    Route::get('/{id}/editar', [NotaClinicaController::class, 'edit'])->name('notas_clinicas.edit');
    Route::put('/{id}/actualizar', [NotaClinicaController::class, 'update'])->name('notas_clinicas.update');
});
