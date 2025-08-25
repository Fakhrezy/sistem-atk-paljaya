<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!$request->user() || $request->user()->role !== $role) {
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
}
