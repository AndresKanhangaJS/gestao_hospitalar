<?php

use App\Http\Controllers\PacienteController;
use App\Http\Controllers\EpisodioController;
use App\Http\Controllers\NotaClinicaController;
use App\Http\Controllers\TipoAtendimentoController;

// Pacientes
Route::get('/pacientes', [PacienteController::class, 'index'])->name('pacientes.index');
Route::get('/pacientes/criar', [PacienteController::class, 'create'])->name('pacientes.create');
Route::post('/pacientes/guardar', [PacienteController::class, 'store'])->name('pacientes.store');
Route::get('/pacientes/{id}/editar', [PacienteController::class, 'edit'])->name('pacientes.edit');
Route::put('/pacientes/{id}/atualizar', [PacienteController::class, 'update'])->name('pacientes.update');
Route::delete('/pacientes/{id}', [PacienteController::class, 'destroy'])->name('pacientes.destroy');

// Episódios (Atendimentos)
Route::get('/episodios', [EpisodioController::class, 'index'])->name('episodios.index');
Route::post('/episodios/abrir', [EpisodioController::class, 'store'])->name('episodios.store');
Route::get('/episodios/{id}', [EpisodioController::class, 'show'])->name('episodios.show');
Route::patch('/episodios/{id}/fechar', [EpisodioController::class, 'fechar'])->name('episodios.fechar');

// Notas Clínicas (Prontuário)
Route::post('/notas-clinicas/registar', [NotaClinicaController::class, 'store'])->name('notas.store');
Route::get('/notas-clinicas/{id}/editar', [NotaClinicaController::class, 'edit'])->name('notas.edit');
Route::put('/notas-clinicas/{id}/atualizar', [NotaClinicaController::class, 'update'])->name('notas.update');

