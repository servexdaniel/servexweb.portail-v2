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

        /*
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
        */

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::guard($guard)->user();

                // Cas spécifique pour le guard 'contact'
                if ($guard === 'contact' || $guard === null) {
                    if (!$user->CcIsManager) {
                        return $next($request); // Accès autorisé pour un contact non manager
                    }
                }

                // Si on arrive ici : accès refusé (manager ou mauvais guard)
                return $this->denyAccess($request);
            }
        }
        //abort(Response::HTTP_FORBIDDEN);
        return $this->denyAccess($request);
    }

    /**
     * Retourne une réponse appropriée en cas d'accès refusé.
     * - JSON pour les requêtes AJAX (pas de rechargement).
     * - Redirection avec message pour les requêtes classiques.
     */
    private function denyAccess(Request $request): Response
    {
        if ($request->expectsJson() || $request->ajax()) {
            // Requête AJAX : réponse JSON sans rechargement de page
            return response()->json([
                'message' => 'Accès refusé : vous n\'êtes pas un contact.',
            ], Response::HTTP_FORBIDDEN);
        }

        // Requête classique : redirection avec message flash (recharge une autre page)
        return redirect()
            ->route('admin.dashboard', ['language' => app()->getLocale()])
            ->with('error', 'Accès refusé : vous n\'êtes pas un contact.');

        // Alternative sans redirection (affiche une page 403 personnalisée) :
        // abort(Response::HTTP_FORBIDDEN, 'Accès refusé : droits administrateur requis.');
    }
}
