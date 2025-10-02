<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!$request->user()) {
            return redirect()->route('login');
        }

        // Check if user's role is in the allowed roles
        if (!in_array($request->user()->role, $roles)) {
            // If AJAX request, return JSON error instead of redirect
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access',
                    'redirect' => $this->getRedirectRoute($request->user()->role)
                ], 403);
            }

            // Otherwise redirect based on user role
            if ($request->user()->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }
            if ($request->user()->role === 'user') {
                return redirect()->route('user.dashboard');
            }
            return redirect()->route('login');
        }

        return $next($request);
    }

    private function getRedirectRoute(string $role): string
    {
        return match ($role) {
            'admin' => route('admin.dashboard'),
            'user' => route('user.dashboard'),
            default => route('login')
        };
    }
}
