<head>

    <script src="https://www.google.com/recaptcha/api.js?hl={{ app()->getLocale() }}" async defer></script>

@vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body class="relative">

<div class="absolute inset-0 bg-gradient-to-r from-purple-800 to-purple-900 z-0"></div>

<!-- Добавляем полупрозрачное фоновое изображение -->
<div class="absolute inset-0 z-0 bg-cover bg-center opacity-50" style="background-image: url('/img/beach.jpg');"></div>

<div class="flex items-center justify-center min-h-screen relative z-10">

    <div class="container max-w-md mx-auto p-8 rounded-xl shadow-xl bg-gradient-to-r from-blue-500 via-purple-500 to-blue-500 bg-size-200 bg-pos-0 animate-gradient">
        <h2 class="text-center text-white text-2xl font-bold mb-6">{{__("show.type_passwd")}}</h2>

        @if ($errors->has('captcha'))
            <div class="bg-red-600 text-white p-3 rounded mb-4 animate-pulse">
                {{ $errors->first('captcha') }}
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-600 text-white p-3 rounded mb-4 animate-pulse">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('albums.checkPassword', ['secure_token' => $secure_token]) }}" method="POST" class="space-y-6">
            @csrf
            <div class="mb-4">
                <label for="password" class="block text-white text-sm font-semibold mb-2">{{__("show.password")}}</label>
                <input
                    type="password"
                    name="password"
                    id="password"
                    class="w-full px-4 py-3 border border-purple-700 rounded-md bg-purple-800 text-white placeholder-purple-400 focus:outline-none focus:ring-2 focus:ring-purple-500 transition duration-300 ease-in-out"
                    placeholder={{__("show.password_input")}}
                    required>

                <div class="flex flex-col items-center bg-yellow-500 p-4 rounded shadow-md mt-4">
                    <label class="mb-2 text-lg font-bold text-gray-700">
                        Captcha
                    </label>
                    <div class="mb-4">
                            <div class="flex flex-col items-center bg-cyan-500 p-4 rounded shadow-md">
                                <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>
                            </div>
                    </div>
                </div>

                <div class="mt-6">
                    <button type="submit" class="w-full bg-purple-600 hover:bg-purple-700 text-white font-bold py-3 px-4 rounded-md shadow-lg transform hover:scale-105 transition-transform duration-200 ease-in-out">
                        {{__("show.submit")}}
                    </button>
                </div>

            </div>

        </form>
    </div>

</div>

</body>
