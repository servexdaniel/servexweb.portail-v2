<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;

// Toutes les routes de l'application dans un groupe avec {language} + middleware tenant
// 1. Redirection racine → /fr (ou langue par défaut)
Route::redirect('/', app()->getLocale())->name('root');
Route::group([
    'prefix' => '{language}/',
    'where' => ['language' => 'fr|en|es|de|it|pt'], // langues autorisées
    'middleware' => [
        \App\Http\Middleware\SetLanguage::class,              // définit app()->setLocale()
        //\App\Http\Middleware\EnsureValidTenantDomain::class,  // identifie et active le tenant
    ],
], function () {

    // Toutes les routes suivantes ont :
    // - la langue définie
    // - le tenant actif (ou 404 si invalide)
    // - accès via /fr/dashboard, /en/dashboard, etc.

    require __DIR__ . '/servex/auth.php';

    Route::get('/', fn() => view('welcome'))->name('home');

    Route::view('dashboard', 'dashboard')
        ->middleware(['auth', 'verified'])
        ->name('dashboard');

    Route::view('profile', 'profile')
        ->middleware(['auth'])
        ->name('profile');

    // Tu peux ajouter ici toutes tes autres routes (API, admin, etc.)
    // Elles seront automatiquement protégées par langue + tenant

    /**
     * Guard "contact" pour les contacts des clients
     */
    Route::prefix('contact')->name('contact.')->group(function () {
        require __DIR__ . '/servex/contact.php';
    });
});
