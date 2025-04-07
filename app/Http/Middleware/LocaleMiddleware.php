<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class LocaleMiddleware
{
    public function handle($request, Closure $next)
    {
        $languages = Config::get('languages');
        Log::info('Текущий язык сессии: ' . Session::get('locale'));

        if (Session::has('locale') && is_array($languages) && array_key_exists(Session::get('locale'), $languages)) {
            App::setLocale(Session::get('locale'));
            Log::info('Язык установлен: ' . Session::get('locale'));
        } else {
            App::setLocale(config('app.fallback_locale'));
            Log::info('Язык по умолчанию установлен: ' . config('app.fallback_locale'));
        }

        return $next($request);
    }
}
