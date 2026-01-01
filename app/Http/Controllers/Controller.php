<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\View;
use Illuminate\Routing\Controller as BaseController;

abstract class Controller  extends BaseController
{
    public function __construct()
    {
        // is site a demo site?
        //$isDemoSiteConfig = app('fireflyconfig')->get('is_demo_site', config('firefly.configuration.is_demo_site', false));
        //$isDemoSite       = (bool) $isDemoSiteConfig->data;
        //View::share('IS_DEMO_SITE', $isDemoSite);
        //View::share('DEMO_USERNAME', config('firefly.demo_username'));
        //View::share('DEMO_PASSWORD', config('firefly.demo_password'));
        //View::share('FF_VERSION', config('firefly.version'));
        //View::share('FF_BUILD_TIME', config('firefly.build_time'));
        View::share('FF_BUILD_TIME', 1765863630);

        $this->middleware(
            function ($request, $next): mixed {
                // Définir le mode sombre par défaut sur "browser"
                $darkMode                = 'browser';
                View::share('darkMode', $darkMode);

                return $next($request);
            }
        );

    }
}
