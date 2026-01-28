<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // ACL Users
    require __DIR__.'/config_sistema/usuarios.php';

    // ACL Routes
    require __DIR__.'/config_sistema/acl.php';

    // Configurações do Sistema
    require __DIR__.'/config_sistema/parametrizacao.php';

    // Módulos do Sistema
    require __DIR__.'/modulos/pacientes.php';
    require __DIR__.'/modulos/medicos.php';
    require __DIR__.'/modulos/exames.php';
});
