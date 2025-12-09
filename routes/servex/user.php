<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Servex\UserController;

Route::middleware(['guest:web', 'PreventBackHistory'])->group(function () {
    Route::get('forget-password', [UserController::class, 'showForgetPasswordForm'])->name('password.request');
    Route::post('forget-password', [UserController::class, 'submitForgetPasswordForm'])->name('password.email');
    Route::get('reset-password/{token}', [UserController::class, 'showResetPasswordForm'])->name('reset.password.get')->middleware('signed');
    Route::post('reset-password', [UserController::class, 'submitResetPasswordForm'])->name('reset.password.post');
    // Page d'inscription
    Route::get('register', [UserController::class, 'create'])->name('register');

    // Traitement du formulaire
    Route::post('/register', [UserController::class, 'store'])->name('register.store');

    // Page de connexion
    Route::get('/login', [UserController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [UserController::class, 'login'])->name('login.store');
});

Route::middleware(['auth:web', 'PreventBackHistory'])->group(function () {
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard')->middleware('validate.settings');
    Route::get('/profile', [UserController::class, 'profile'])->name('profile');
    Route::post('logout', [UserController::class, 'logout'])->name('logout');
});

