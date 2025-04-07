<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Log;

class LanguageController extends Controller
{
    public function switchLang(Request $request)
    {
        $target = $request->post('locale');


        $languages = ['en' => 'English', 'ru' => 'Russian'];


        if (array_key_exists($target, $languages)) {
            Log::info('Locale found: ' . $target);
            Session::put('locale', $target);
            App::setLocale($target);
        } else {
            Log::warning('Unsupported locale: ' . $target);
        }

        return Redirect::back();
    }
}
