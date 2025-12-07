<?php

namespace App\Servex\Utils;

use Illuminate\Http\Request;
use App\Models\Customer;
use Spatie\Multitenancy\TenantFinder\TenantFinder;
use Spatie\Multitenancy\Exceptions\NoCurrentTenant;
use Illuminate\Support\Arr;
use App\Servex\Traits\UsesDomainTrait;
use App\Servex\Traits\UsesCustomTenantModel;

class CustomDomainTenantFinder extends TenantFinder
{
    use UsesCustomTenantModel, UsesDomainTrait;
    public function findForRequest(Request $request): ?Customer
    {
        $host = $request->getHost();
        $domain = "";
        $subdomain = "";
        $custom_tenant = "";
        try {
            if (strpos($host, ".") !== false) {
                $segments = explode(".", $host);
                $subdomain = $segments[0];
                $custom_tenant = $this->getTenantModel()::whereDomain($subdomain)->first();
                if ($custom_tenant == null) {
                    throw new NoCurrentTenant("The current tenant doesn't match");
                }
            } else {
                throw new NoCurrentTenant("No current tenant");
            }

            $domains = Arr::pluck($this->getDomains(), 'domain');
            $base_url = config('app.url');
            if (strpos($base_url, "http://") !== false) {
                $base_url = str_replace("http://", "", $base_url);
            }

            $env = config('app.env');
            if ($env == 'local') {
                $local_url = $_SERVER['HTTP_HOST'];
                $base_url = explode(":", $local_url)[0];
                $segments = explode(".", $base_url);

                $isSubDomainExist = Customer::select("*")
                    ->where("domain", $subdomain)
                    ->exists();

                if (!$isSubDomainExist) {
                    throw new NoCurrentTenant("Current tenant not valid");
                }
                $custom_tenant->makeCurrent();
                return $custom_tenant;
            } else if ($env == 'production') {
                $host_arr = explode(".", $host);

                if ($host_arr[1] == "test") {
                    //Staging
                    $current_domain = $host_arr[0] . "." . $host_arr[1];
                    $key = array_search($current_domain, $domains);
                    $is_current_domain_valid = (is_numeric($key));
                    if (!$is_current_domain_valid) throw new NoCurrentTenant("Current tenant not valid");

                    $custom_tenant->makeCurrent();
                    return $custom_tenant;
                } else {
                    //Production OVH
                    $current_domain = $host_arr[1] . "." . $host_arr[2];
                    $key = array_search($current_domain, $domains);
                    $is_current_domain_valid = (is_numeric($key));
                    if (!$is_current_domain_valid) throw new NoCurrentTenant("Current tenant not valid");

                    $custom_tenant->makeCurrent();
                    return $custom_tenant;
                }
            }
        } catch (\Exception $ex) {
            throw new NoCurrentTenant($ex->getMessage());
        }
        return null;
    }
}
