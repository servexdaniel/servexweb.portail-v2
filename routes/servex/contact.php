<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::get('contact', function () {
        return view('contact');
    })->name('contact.index');

    Route::post('contact', [\App\Http\Controllers\ContactController::class, 'store'])
        ->name('contact.store');
});
