<!doctype html>
<html lang="en">

<head>
    <title>Login | Tailwind Admin</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <script src="https://www.google.com/recaptcha/api.js?hl={{ app()->getLocale() }}" async defer></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('admin/css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/css/all.css') }}">

    <style>
        .login {
            background-image: url('/img/beach.jpg');
            background-size: cover;
            background-position: center;
        }
    </style>
</head>

<body class="h-screen font-sans login bg-cover">
<div class="flex items-center justify-center h-full">
    <div class="bg-white p-8 rounded-lg shadow-lg max-w-md w-full">
        <h2 class="text-2xl font-bold text-gray-800 text-center mb-6">Login</h2>
        <form action="{{ route('admin.login') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-600" for="username">Username</label>
                <input class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md focus:ring focus:ring-indigo-200 focus:border-indigo-300 bg-gray-50" id="username" name="username" type="text" required placeholder="User Name">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600" for="password">Password</label>
                <input class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md focus:ring focus:ring-indigo-200 focus:border-indigo-300 bg-gray-50" id="password" name="password" type="password" required placeholder="********">
            </div>
            <div class="flex items-center justify-between">
                <button class="px-4 py-2 bg-indigo-600 text-white font-semibold rounded-md hover:bg-indigo-700 focus:outline-none focus:ring focus:ring-indigo-200" type="submit">Login</button>
            </div>

            <div class="flex flex-col items-center bg-yellow-500 p-4 rounded shadow-md">
                <label class="mb-2 text-lg font-bold text-gray-700">
                    Captcha
                </label>
                <div class="mb-4">
                        <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>
                </div>
            </div>

        </form>
    </div>
</div>
</body>

</html>

