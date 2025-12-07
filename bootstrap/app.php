<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [
            \App\Http\Middleware\SetLanguage::class,
            \App\Http\Middleware\EnsureValidTenantDomain::class,
        ]);

        // Les middlewares personnalisés (équivalent de $routeMiddleware)
        $middleware->alias([

            'auth'               => \App\Http\Middleware\Authenticate::class,
            'auth.basic'         => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
            'cache.headers'      => \Illuminate\Http\Middleware\SetCacheHeaders::class,
            'can'                => \Illuminate\Auth\Middleware\Authorize::class,
            'guest'              => \App\Http\Middleware\RedirectIfAuthenticated::class,
            'password.confirm'   => \Illuminate\Auth\Middleware\RequirePassword::class,
            'signed'             => \Illuminate\Routing\Middleware\ValidateSignature::class,
            'throttle'           => \Illuminate\Routing\Middleware\ThrottleRequests::class,
            'verified'           => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,

            'tenants' => [
                \App\Http\Middleware\CustomNeedsTenant::class,
                \App\Http\Middleware\CustomEnsureValidTenantSession::class,
            ],

            // ←←← VOS MIDDLEWARES CUSTOM ←←←
            'PreventBackHistory' => \App\Http\Middleware\PreventBackHistory::class,
            'validate.settings'  => \App\Http\Middleware\ValidateSettingsMiddleware::class,
            'clearsessions'      => \App\Http\Middleware\ClearSessionMiddleware::class,
            'sessiontimeout'     => \App\Http\Middleware\SessionTimeout::class,

        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
