<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // 1. Vérifier si l'utilisateur est connecté
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // 2. Vérifier si le rôle est autorisé ET si le compte est actif
        if (in_array($user->role, $roles) && $user->is_active) {
            return $next($request);
        }

        // 3. Sinon, interdire l'accès
        abort(403, 'Action non autorisée ou compte désactivé.');
    }
}