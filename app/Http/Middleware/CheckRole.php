<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        $userRole = strtolower($user->rol_nombre ?? '');

        $allowedRoles = array_map('strtolower', $roles);

        if (!in_array($userRole, $allowedRoles, true)) {
            // Redirect user to their role-appropriate home route
            if ($userRole === 'administrador') {
                return redirect()->route('admin.dashboard');
            } elseif ($userRole === 'domiciliario') {
                return redirect()->route('domiciliario.dashboard');
            } else {
                return redirect()->route('cliente.dashboard');
            }
        }

        return $next($request);
    }
}
