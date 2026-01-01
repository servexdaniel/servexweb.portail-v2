<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                if ($guard == 'contact') {
                    if (Auth::guard('contact')->user()->CcIsManager) {
                        return redirect()->route('admin.dashboard', ['language' => app()->getLocale()]);
                    } else {
                        return redirect()->route('contact.dashboard', ['language' => app()->getLocale()]);
                    }
                }
                if ($guard == 'web') {
                    return redirect()->route('dashboard', ['language' => app()->getLocale()]);
                }
            }
        }

        return $next($request);
    }
}
