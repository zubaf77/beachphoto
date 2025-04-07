<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class AdminLoginController extends Controller
{
    public function showLoginForm()
    {
        return view('admin_pages.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $recaptchaResponse = $request->input('g-recaptcha-response');
        $recaptchaSecret = config('services.recaptcha.secret_key');

       $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
           'secret' => $recaptchaSecret,
           'response' => $recaptchaResponse,
       ]);

       $responseData = $response->json();

       if (!$responseData['success']) {
           return redirect()->back()->withErrors(['captcha' => 'Неверно введена капча.']);
       }

        if (Auth::guard('admin')->attempt([
            'username' => $request->username,
            'password' => $request->password,
        ])) {
            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors([
            'message' => 'Неправильное имя пользователя или пароль.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');

    }
}
