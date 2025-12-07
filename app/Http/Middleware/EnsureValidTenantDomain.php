<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use \App\Servex\Services\CustomDomainTenantFinder;
use App\Servex\Services\TenantManager;

class EnsureValidTenantDomain
{
    /**
     * @var TenantManager
     */
    protected $tenantManager;

    public function __construct(TenantManager $tenantManager)
    {
        $this->tenantManager = $tenantManager;
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $tenantFinder = app(CustomDomainTenantFinder::class);
        $tenant = $tenantFinder->findForRequest(request());

        if ($this->tenantManager->loadTenant($tenant->domain)) {
            return $next($request);
        } else {
            throw new NotFoundHttpException;
        }
    }
}
