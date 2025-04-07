<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Album;
use Illuminate\Support\Facades\Log;

class ValidateAlbumToken
{
    public function handle(Request $request, Closure $next)
    {
        $secure_token = $request->route('secure_token');

        Log::info('Middleware: Checking album token: ' . $secure_token);

        // Проверяем, находится ли пользователь уже на корневом маршруте или нет токена
        if (!$secure_token) {
            Log::info('Middleware: No secure token provided.');
            return $next($request); // Продолжить выполнение, если токена нет (например, для корневого маршрута)
        }

        // Проверяем, существует ли альбом с таким токеном
        if (!Album::where('secure_token', $secure_token)->exists()) {
            Log::warning('Middleware: Invalid album token detected, redirecting to root.');

            // Если текущий путь уже корневой, не делаем редирект
            if ($request->path() !== '/') {
                return redirect('/')->with('error', 'Invalid album token.');
            }

            // Если уже на корне, просто продолжаем выполнение, чтобы избежать бесконечного редиректа
            return $next($request);
        }

        Log::info('Middleware: Valid token, continuing request.');
        return $next($request);
    }
}
