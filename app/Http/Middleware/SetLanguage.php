<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;

class SetLanguage
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        //App::setLocale($request->language);
        //return $next($request);

        $language = $request->route('language');

        if (in_array($language, ['fr', 'en', 'es', 'de', 'it', 'pt'])) {
            app()->setLocale($language);

            // LA LIGNE MAGIQUE
            URL::defaults(['language' => $language]);
        }
        return $next($request);
    }
}
