<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Servex\ContactController;

Route::middleware(['guest:contact', 'PreventBackHistory'])->group(function () {
    //Route::view('/login', 'dashboard.contact.login')->name('login');
    Route::get('forget-password', [ContactController::class, 'showForgetPasswordForm'])->name('forget.password.get');
    Route::post('forget-password', [ContactController::class, 'submitForgetPasswordForm'])->name('forget.password.post');
    Route::get('reset-password/{token}', [ContactController::class, 'showResetPasswordForm'])->name('reset.password.get')->middleware('signed');
    Route::post('reset-password', [ContactController::class, 'submitResetPasswordForm'])->name('reset.password.post');
});

Route::middleware(['auth:contact', 'PreventBackHistory'])->group(function () {
    Route::view('/home', 'dashboard.contact.home')->name('home')->middleware('validate.settings');
    Route::view('/profil', 'dashboard.contact.profil')->name('profil');
    Route::post('logout', [ContactController::class, 'logout'])->name('logout');
});

