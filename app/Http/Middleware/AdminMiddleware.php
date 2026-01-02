<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::guard($guard)->user();

                // Cas spécifique pour le guard 'contact'
                if ($guard === 'contact' || $guard === null) {
                    if ($user->CcIsManager) {
                        return $next($request); // Accès autorisé
                    }
                }

                // Si on arrive ici : accès refusé (pas manager ou mauvais guard)
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
                'message' => 'Accès refusé : vous n\'avez pas les droits d\'administrateur.',
            ], Response::HTTP_FORBIDDEN);
        }

        /*
        // Requête classique : redirection avec message flash (recharge une autre page)
        return redirect()
            ->route('contact.dashboard', ['language' => app()->getLocale()])
            ->with('error', 'Accès refusé : vous n\'avez pas les droits d\'administrateur.');
            */

        // Alternative sans redirection (affiche une page 403 personnalisée) :
        abort(Response::HTTP_FORBIDDEN, 'Accès refusé : droits administrateur requis.');
    }
}
