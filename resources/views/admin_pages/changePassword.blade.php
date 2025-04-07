<!doctype html>
<html lang="en">

<head>
    <title>Change Password | Admin Panel</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="{{ asset('admin_pages/css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('admin_pages/css/all.css') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="h-screen font-sans bg-gray-100">
<div class="container mx-auto h-full flex flex-1 justify-center items-center">
    <div class="w-full max-w-lg">
        <div class="leading-loose">
            <form action="{{ route('admin.changePassword') }}" method="POST" class="max-w-xl m-4 p-10 bg-white rounded shadow-xl">
                @csrf

                <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Change Password</h2>

                <!-- Сообщение об успешной смене пароля -->
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Сообщение об ошибке -->
                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="mb-4">
                    <label class="block text-sm text-gray-600" for="current_password">Current Password</label>
                    <input class="w-full px-5 py-1 text-gray-700 bg-gray-200 rounded" id="current_password" name="current_password" type="password" required placeholder="Current Password">
                </div>

                <div class="mb-4">
                    <label class="block text-sm text-gray-600" for="new_password">New Password</label>
                    <input class="w-full px-5 py-1 text-gray-700 bg-gray-200 rounded" id="new_password" name="new_password" type="password" required placeholder="New Password">
                </div>

                <div class="mb-4">
                    <label class="block text-sm text-gray-600" for="new_password_confirmation">Confirm New Password</label>
                    <input class="w-full px-5 py-1 text-gray-700 bg-gray-200 rounded" id="new_password_confirmation" name="new_password_confirmation" type="password" required placeholder="Confirm New Password">
                </div>

                <div class="mt-6">
                    <button class="w-full px-4 py-2 text-white bg-indigo-600 rounded hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-opacity-50" type="submit">
                        Change Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
</body>

</html>
