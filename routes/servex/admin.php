<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Servex\ContactController;


Route::middleware(['auth:contact', 'PreventBackHistory'])->group(function () {
    Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard')->middleware('validate.settings');
    Route::get('/profile', [HomeController::class, 'profile'])->name('profile');
});


