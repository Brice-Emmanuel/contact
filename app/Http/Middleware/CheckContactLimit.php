<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckContactLimit
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        // Si l'utilisateur est connecté et ne peut plus ajouter de contact
        if ($user && !$user->canAddContact()) {
            $limit = $user->getEffectiveContactLimit();

            return redirect()->route('subscription.index')
                ->with('error', "Vous avez atteint votre limite de {$limit} contacts. Passez à un forfait supérieur pour ajouter d'autres contacts.");
        }

        return $next($request);
    }
}