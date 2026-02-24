<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::get('/usuarios', [UserController::class, 'index'])->name('users.index');
Route::post('/usuarios', [UserController::class, 'store'])->name('users.store');
Route::get('/usuarios/{user}', [UserController::class, 'edit'])->name('users.edit');
Route::put('/usuarios/{user}', [UserController::class, 'update'])->name('users.update');
Route::get('/usuarios/{user}/details', [UserController::class, 'show'])->name('users.show');
Route::delete('/usuarios/{user}', [UserController::class, 'destroy'])->name('users.destroy');

Route::middleware(['auth'])->group(function () {
    // Perfil do Usuário
    Route::get('/perfil', [UserController::class, 'perfil'])->name('perfil');
    Route::post('/perfil/senha', [UserController::class, 'updatePassword'])->name('perfil.password.update');

    // // Rota Forçada para Primeiro Acesso
    // Route::get('/alterar-senha-obrigatoria', [UserController::class, 'showChangePasswordForm'])->name('password.force_change');
    // Route::post('/alterar-senha-obrigatoria', [UserController::class, 'forceUpdatePassword'])->name('password.force_update');
});
