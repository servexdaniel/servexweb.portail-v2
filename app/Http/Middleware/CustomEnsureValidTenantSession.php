<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Servex\Services\TenantManager;
use App\Servex\Utils\CustomDomainTenantFinder;
use Spatie\Multitenancy\Concerns\UsesMultitenancyConfig;

class CustomEnsureValidTenantSession
{
    use UsesMultitenancyConfig;
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
        $sessionKey = 'ensure_valid_tenant_session_tenant_id';

        if (! $request->session()->has($sessionKey)) {
            $request->session()->put($sessionKey, app($this->currentTenantContainerKey())->id);
            return $next($request);
        }

        /**
         * Si le tenant actuel est différent de celui présent dans la session,
         * et que le nouveau tenant est identifié, on regenere toutes les données de la session,
         */
        if ($request->session()->get($sessionKey) !== app($this->currentTenantContainerKey())->id) {

            $tenantFinder = app(CustomDomainTenantFinder::class);
            $tenant = $tenantFinder->findForRequest(request());

            if ($this->tenantManager->loadTenant($tenant->domain)) {
                $request->session()->invalidate();

                if ($request->routeIs('contact.*')) {
                    return redirect()->route('contact.login', ['language' => app()->getLocale()]);
                }
                if ($request->routeIs('user.*')) {
                    return redirect()->route('user.login', ['language' => app()->getLocale()]);
                }
            } else {
                return $this->handleInvalidTenantSession($request);
            }
        }

        return $next($request);
    }

    protected function handleInvalidTenantSession($request)
    {
        abort(Response::HTTP_UNAUTHORIZED);
    }
}
