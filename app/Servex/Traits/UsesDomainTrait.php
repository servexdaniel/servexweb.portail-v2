<?php

namespace App\Servex\Traits;

use App\Models\Domain;
use Illuminate\Support\Arr;
use App\Models\Customer;
use App\Servex\Utils\CustomDomainTenantFinder;

trait UsesDomainTrait
{
    public function getDomains()
    {
        $domains = Domain::all();
        //Si la liste des domaines est vide
        if (!(count($domains) > 0)) {
            $domains = $this->syncDomains();
        }
        return $domains;
    }

    function syncDomains()
    {
        $status = "ACTIVE";
        $key         = config('app.GODADDYprodKey');
        $secret      = config('app.GODADDYprodSecret');

        $url = "https://api.godaddy.com/v1/domains?statuses=" . $status;
        $header = array(
            'Authorization: sso-key ' . $key . ':' . $secret
        );

        //open connection
        $ch = curl_init();
        $timeout = 60;

        //set the url and other options for curl
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');  //values: GET, POST, PUT
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        //Execute call and return response data
        $result = curl_exec($ch);
        //Close curl connection
        curl_close($ch);
        //Decode the json response
        $data = json_decode($result, true);
        if (isset($data['code'])) {
            $msg = $data['message'];

            throw new \Exception($msg);
        }

        $domains = Arr::pluck($data, 'domain');

        foreach ($domains as $domain) {
            $domain = Domain::firstOrCreate(array('domain' => $domain));
            $domain->save();
        }
        return Domain::all();
    }

    function getCurrentTenant()
    {
        if (Customer::checkCurrent()) {
            $tenant = app('currentTenant');
        } else {
            $tenantFinder = app(CustomDomainTenantFinder::class);
            $tenant = $tenantFinder->findForRequest(request());
        }
        return $tenant;
    }
}
