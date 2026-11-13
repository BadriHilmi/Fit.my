<?php

namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;


class CheckUserType
{
    public function handle($request, Closure $next, $userType): Response|RedirectResponse
    {
        if (auth()->check() && auth()->user()->user_type === $userType) {
            return $next($request);
        }

        if ($request->expectsJson()) {
            abort(403);
        }

        return redirect()->route('login');
    }


}
