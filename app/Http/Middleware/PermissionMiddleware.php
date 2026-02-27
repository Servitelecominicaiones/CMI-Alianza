<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PermissionMiddleware
{
    public function handle(Request $request, Closure $next, string $permission)
    {
        $user = auth()->user();

        if (!$user) {
            abort(403, 'No autenticado');
        }

        if (!$user->tienePermiso($permission)) {
            abort(403, 'No tienes permiso');
        }

        return $next($request);
    }
}
