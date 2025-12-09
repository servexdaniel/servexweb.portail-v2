<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Servex\ContactController;

Route::middleware(['guest:contact', 'PreventBackHistory'])->group(function () {
    //Route::view('/login', 'dashboard.contact.login')->name('login');
    Route::get('forget-password', [ContactController::class, 'showForgetPasswordForm'])->name('password.request');
    Route::post('forget-password', [ContactController::class, 'submitForgetPasswordForm'])->name('forget.password.post');
    Route::get('reset-password/{token}', [ContactController::class, 'showResetPasswordForm'])->name('reset.password.get')->middleware('signed');
    Route::post('reset-password', [ContactController::class, 'submitResetPasswordForm'])->name('reset.password.post');

    // Page d'inscription
    Route::get('register', [ContactController::class, 'create'])->name('register');

    // Traitement du formulaire
    Route::post('/register', [ContactController::class, 'store'])->name('register.store');

    // Page de connexion
    Route::get('/login', [ContactController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [ContactController::class, 'login'])->name('login.store');
});

Route::middleware(['auth:contact', 'PreventBackHistory'])->group(function () {
    Route::get('/dashboard', [ContactController::class, 'dashboard'])->name('dashboard')->middleware('validate.settings');
    Route::get('/profile', [ContactController::class, 'profile'])->name('profile');
    Route::post('logout', [ContactController::class, 'logout'])->name('logout');
});

