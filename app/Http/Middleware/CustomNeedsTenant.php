<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use \App\Servex\Traits\UsesCustomTenantModel;
use Spatie\Multitenancy\Exceptions\NoCurrentTenant;

class CustomNeedsTenant
{
    use UsesCustomTenantModel;

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $this->getTenantModel()::checkCurrent()) {
            return $this->handleInvalidRequest();
        }
        return $next($request);
    }

    public function handleInvalidRequest()
    {
        throw NoCurrentTenant::make();
    }
}
