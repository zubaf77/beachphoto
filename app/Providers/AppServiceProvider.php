<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\App;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        App::setLocale('ru');
    }

    public function register()
    {
        //
    }
}

