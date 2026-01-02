<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ContactMiddleware
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
                        //return redirect()->route('admin.dashboard', ['language' => app()->getLocale()]);
                        abort(Response::HTTP_FORBIDDEN);
                    } else {
                        return $next($request);
                    }
                }
                else {
                    abort(Response::HTTP_FORBIDDEN);
                }
            }
        }
        abort(Response::HTTP_FORBIDDEN);
    }
}
