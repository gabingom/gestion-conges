<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Laisse passer si le rôle de l'utilisateur fait partie de la liste autorisée.
     * Sinon on redirige vers l'espace correspondant (jamais d'erreur 403 brute,
     * et aucune boucle car les routes de destination ne sont pas protégées par
     * le même groupe).
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Les comptes sans rôle (anciens comptes) sont considérés comme admin.
        $role = $user->role ?? 'admin';

        if (in_array($role, $roles, true)) {
            return $next($request);
        }

        return $role === 'employe'
            ? redirect()->route('employe.profil')
            : redirect()->route('dashboard');
    }
}
