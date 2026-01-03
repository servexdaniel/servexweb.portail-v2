<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Servex\ContactController;


Route::middleware(['auth:contact', 'PreventBackHistory'])->group(function () {
    Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [HomeController::class, 'profile'])->name('profile');
});

/*
Route::middleware(['auth:contact', 'PreventBackHistory', 'validate.settings'])->group(function () {
    Route::view('/calls/columns', 'settings.calls.columns')->name('calls.columns.settings');
    Route::view('/products/columns', 'settings.products.columns')->name('products.columns.settings');
    Route::view('/calls/historydate', 'settings.calls.history-date')->name('calls.historydate.settings');
    Route::view('/design/logo-colors', 'settings.design.logo-colors')->name('design.logo-colors.settings');
    Route::view('/display/menu', 'settings.display.menu')->name('display.menu.settings');
    Route::view('/chart', 'settings.chart.chart')->name('chart.settings');
    Route::view('/administrator', 'settings.administrator.administrator')->name('administrator.settings');
    Route::view('/traductions', 'settings.traductions.labels')->name('traductions.settings');
});
*/


