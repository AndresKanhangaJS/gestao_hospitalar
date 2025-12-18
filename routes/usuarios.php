<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::get('/usuarios', [UserController::class, 'index'])->name('users.index');
Route::post('/usuarios', [UserController::class, 'store'])->name('users.store');
Route::get('/usuarios/{user}', [UserController::class, 'edit'])->name('users.edit');
Route::put('/usuarios/{user}', [UserController::class, 'update'])->name('users.update');
Route::get('/usuarios/{user}/details', [UserController::class, 'show'])->name('users.show');
Route::delete('/usuarios/{user}', [UserController::class, 'destroy'])->name('users.destroy');

