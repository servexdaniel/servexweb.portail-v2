<?php

namespace App\Servex\Utils;

use Illuminate\Http\Request;
use App\Models\Customer;
use Spatie\Multitenancy\TenantFinder\TenantFinder;
use Spatie\Multitenancy\Exceptions\NoCurrentTenant;
use App\Servex\Traits\UsesCustomTenantModel;
use App\Servex\Traits\UsesDomainTrait;

class CustomDomainTenantFinder extends TenantFinder
{
    use UsesCustomTenantModel, UsesDomainTrait;

    public function findForRequest(Request $request): ?Customer
    {
        $host      = $request->getHost();               // ex: client1.test.localhost ou prod.client1.mondomaine.fr
        $tenant    = null;

        try {
            // 1. Cas classique : sous-domaine simple (client1.mondomaine.com)
            if ($this->hasSubdomainFormat($host)) {
                $subdomain = $this->extractSubdomain($host);

                $tenant = $this->getTenantModel()::where('domain', $subdomain)->first();

                if (! $tenant) {
                    throw new NoCurrentTenant("Aucun tenant trouvé pour le domaine : {$subdomain}");
                }

                // En environnement local, on accepte même si le domaine complet n'est pas dans la liste config
                if (! $this->isDomainAllowedInProduction($host)) {
                    throw new NoCurrentTenant("Domaine non autorisé : {$host}");
                }

                $tenant->makeCurrent();
                return $tenant;
            }

            // 2. Cas particulier de production OVH / staging
            if (app()->environment('production') || app()->environment('staging')) {
                $tenant = $this->resolveTenantForProductionOrStaging($host);
                if ($tenant) {
                    $tenant->makeCurrent();
                    return $tenant;
                }
            }

            throw new NoCurrentTenant("No current tenant : {$host}");
        } catch (NoCurrentTenant $e) {
            throw $e; // On laisse passer l'exception dédiée
        } catch (\Throwable $e) {
            throw new NoCurrentTenant("Erreur lors de la résolution du tenant : " . $e->getMessage());
        }
    }

    /**
     * Vérifie si le host ressemble à un sous-domaine (au moins un point)
     */
    private function hasSubdomainFormat(string $host): bool
    {
        return strpos($host, '.') !== false;
    }

    /**
     * Extrait le sous-domaine (premier segment)
     */
    private function extractSubdomain(string $host): string
    {
        return explode('.', $host)[0];
    }

    /**
     * Vérifie que le domaine complet fait partie de la liste autorisée (config ou DB)
     */
    private function isDomainAllowedInProduction(string $host): bool
    {
        if (app()->environment('local')) {
            return true; // En local on accepte tout tant que le sous-domaine existe dans servex_customers
        }

        $allowedDomains = collect($this->getDomains())->pluck('domain')->toArray();

        return in_array($host, $allowedDomains, true)
            || in_array($this->extractSubdomain($host), $this->getTenantModel()::pluck('domain')->toArray());
    }

    /**
     * Logique spécifique pour les environnements de staging/production OVH
     */
    private function resolveTenantForProductionOrStaging(string $host): ?Customer
    {
        $parts = explode('.', $host);

        // production OVH → client1.mondomaine.fr
        if (count($parts) >= 3) {
            $domainKey = $parts[1] . '.' . $parts[2]; // ex: mondomaine.fr
            return $this->findTenantByDomainKey($domainKey);
        }

        return null;
    }

    /**
     * Recherche un tenant via la liste de domaines configurés (getDomains())
     */
    private function findTenantByDomainKey(string $domainKey): ?Customer
    {
        $domains = collect($this->getDomains())->pluck('domain', 'id')->toArray();

        $tenantId = array_search($domainKey, $domains, true);

        if ($tenantId === false) {
            throw new NoCurrentTenant("Domaine configuré non trouvé : {$domainKey}");
        }

        $tenant = $this->getTenantModel()::find($tenantId);

        if (! $tenant) {
            throw new NoCurrentTenant("Tenant introuvable en base pour l'ID : {$tenantId}");
        }

        return $tenant;
    }
}
