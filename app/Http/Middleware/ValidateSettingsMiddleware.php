<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Route;
use App\Servex\Traits\UsesDomainTrait;

class ValidateSettingsMiddleware
{
    use UsesDomainTrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::guard('contact')->check()) {

            //Valider si la route courrante, 'est pas cachée (via les configurations)
            $routename = Route::currentRouteName();
            $client = $this->getCurrentTenant();
            $HiddenRoutes = $client->menuLabels->pluck('route');
            $isRouteHidden = $HiddenRoutes->contains(function ($value, $key) use ($routename) {
                return $value == $routename;
            });

            if ($isRouteHidden) {
                abort(Response::HTTP_FORBIDDEN);
            }
        }
        return $next($request);
    }
}
