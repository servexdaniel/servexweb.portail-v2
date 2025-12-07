<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            if ($request->routeIs('contact.*')) {
                return route('contact.login', ['language' => app()->getLocale()]);
            }
            if ($request->routeIs('user.*')) {
                return route('user.login', ['language' => app()->getLocale()]);
            }
            return route('contact.login', ['language' => app()->getLocale()]);
        }
    }
}
