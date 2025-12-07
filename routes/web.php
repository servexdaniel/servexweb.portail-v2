<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;

/*
Route::redirect('/', app()->getLocale());

Route::group([
    'prefix' => '{language}/',
    'middleware' => \App\Http\Middleware\EnsureValidTenantDomain::class

], function () {
    //require __DIR__.'/auth.php';
    //require __DIR__.'/servex/contact.php';
    //require __DIR__.'/user.php';


    Route::get('/', function () {
        return view('welcome');
        //require __DIR__ . '/servex/welcome.php';
    })->name('home');

    Route::view('dashboard', 'dashboard')
        ->middleware(['auth', 'verified'])
        ->name('dashboard');

    Route::middleware(['auth'])->group(function () {
        Route::redirect('settings', 'settings/profile');

        Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
        Volt::route('settings/password', 'settings.password')->name('user-password.edit');
        Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');

        Volt::route('settings/two-factor', 'settings.two-factor')
            ->middleware(
                when(
                    Features::canManageTwoFactorAuthentication()
                        && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                    ['password.confirm'],
                    [],
                ),
            )
            ->name('two-factor.show');
    });
});
*/


/*
Route::get('/', function () {
    return view('welcome');
    //require __DIR__ . '/servex/welcome.php';
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('user-password.edit');
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');

    Volt::route('settings/two-factor', 'settings.two-factor')
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');
});
*/



// 2. NOUVEAU : Toutes les routes de l'application dans un groupe avec {language} + middleware tenant
// 1. Redirection racine → /fr (ou langue par défaut)
Route::redirect('/', app()->getLocale())->name('root');
Route::group([
    'prefix' => '{language}/',
    'where' => ['language' => 'fr|en|es|de|it|pt'], // langues autorisées
    'middleware' => [
        \App\Http\Middleware\SetLanguage::class,              // définit app()->setLocale()
        \App\Http\Middleware\EnsureValidTenantDomain::class,  // identifie et active le tenant
    ],
], function () {

    // Toutes les routes suivantes ont :
    // - la langue définie
    // - le tenant actif (ou 404 si invalide)
    // - accès via /fr/dashboard, /en/dashboard, etc.

    Route::get('/', fn() => view('welcome'))->name('home');

    Route::view('dashboard', 'dashboard')
        ->middleware(['auth', 'verified'])
        ->name('dashboard');

    Route::middleware(['auth'])->group(function () {
        Route::redirect('settings', 'settings/profile');

        Volt::route('settings/profile', 'settings.profile')
            ->name('profile.edit');

        Volt::route('settings/password', 'settings.password')
            ->name('user-password.edit');

        Volt::route('settings/appearance', 'settings.appearance')
            ->name('appearance.edit');

        Volt::route('settings/two-factor', 'settings.two-factor')
            ->middleware(
                when(
                    Features::canManageTwoFactorAuthentication()
                        && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                    ['password.confirm'],
                    []
                )
            )
            ->name('two-factor.show');
    });

    // Tu peux ajouter ici toutes tes autres routes (API, admin, etc.)
    // Elles seront automatiquement protégées par langue + tenant
});
