<?php

use App\Http\Controllers\MedicoController;

// Médicos
Route::prefix('medicos')->group(function () {
    Route::get('/', [MedicoController::class, 'index'])->name('medicos.index');
    Route::get('/registar', [MedicoController::class, 'create'])->name('medicos.create');
    Route::post('/store', [MedicoController::class, 'store'])->name('medicos.store');
    Route::get('/{medico}/editar', [MedicoController::class, 'edit'])->name('medicos.edit');
    Route::get('/{medico}/detalhes', [MedicoController::class, 'show'])->name('medicos.show');
    Route::put('/{medico}/actualizar', [MedicoController::class, 'update'])->name('medicos.update');
    Route::delete('/{id}', [MedicoController::class, 'destroy'])->name('medicos.destroy');
});
