<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle($request, Closure $next)
    {
        // Избегаем перенаправления, если текущий маршрут — это страница входа
        if ($request->route()->named('admin.loginForm')) {
            return $next($request);
        }

        if (!Auth::guard('admin')->check()) {
            return redirect()->route('admin.loginForm');  // Перенаправление на страницу логина для админа
        }

        return $next($request);
    }
}
