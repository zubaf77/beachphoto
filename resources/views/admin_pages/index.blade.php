<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="keywords" content="tailwind,tailwindcss,tailwind css,css,starter template,free template,admin templates, admin template, admin dashboard, free tailwind templates, tailwind example">
    <!-- Css -->
    <link rel="stylesheet" href="{{ asset('admin/css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/css/all.css') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Beach Photo Admin's Page</title>
</head>

<body>
<!--Container -->
<div class="mx-auto bg-grey-400">
    <!--Screen-->
    <div class="min-h-screen flex flex-col">
        <!--Header Section Starts Here-->
        <header class="bg-nav header-container">
            <div class="flex justify-between">
                <div class="p-1 mx-3 inline-flex items-center">
                    <i class="fas fa-bars pr-2 text-white" id="sidebarToggle"></i>
                    <h1 class="text-white p-2">Logo</h1>
                </div>
                <div class="p-1 flex flex-row items-center">


                    <img onclick="profileToggle()" class="inline-block h-8 w-8 rounded-full" src="{{ asset('admin/images/anon.png') }}" alt="">
                    <a href="#" onclick="profileToggle()" class="text-white p-2 no-underline block">{{Auth::guard('admin')->user()->username}}</a>
                    <div id="ProfileDropDown" class="rounded hidden shadow-md bg-white absolute pin-t mt-12 mr-1 pin-r">
                        <ul class="list-reset">
                            <li><a href="{{ route('admin.showChangePasswordForm') }}" class="no-underline px-4 py-2 block text-black hover:bg-grey-light">Change Password</a></li>
                            <li><hr class="border-t mx-2 border-grey-light"></li>
                            <li><form action="{{ route('admin.logout') }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="no-underline px-4 py-2 block text-black hover:bg-grey-light w-full text-left">
                                        Logout
                                    </button>

                                </form></li>
                        </ul>
                    </div>
                </div>
            </div>
        </header>
        <!--/Header-->

        <div class="flex flex-1">
            <!--Sidebar-->

            <aside id="sidebar" class="bg-side-nav w-1/2 md:w-1/6 lg:w-1/6 border-r border-side-nav md:block lg:block">

                <ul class="list-reset flex flex-col">
                    <li class=" w-full h-full py-3 px-2 border-b border-light-border bg-white">
                        <a href="{{route('admin.dashboard')}}"
                           class="font-sans font-hairline hover:font-normal text-sm text-nav-item no-underline">
                            <i class="fas fa-tachometer-alt float-left mx-2"></i>
                            Dashboard
                            <span><i class="fas fa-angle-right float-right"></i></span>
                        </a>
                    </li>
                    <li class="w-full h-full py-3 px-2 border-b border-light-border">
                        <a href="{{route('admin.banList')}}"
                           class="font-sans font-hairline hover:font-normal text-sm text-nav-item no-underline">
                            <i class="fab fa-ban float-left mx-2"></i>
                            Заблокированные IP
                            <span><i class="fa fa-angle-right float-right"></i></span>
                        </a>
                    </li>

                    <li class="w-full h-full py-3 px-2 border-b border-light-border">
                        <a href="{{route('admin.adminList')}}"
                           class="font-sans font-hairline hover:font-normal text-sm text-nav-item no-underline">
                            <i class="fas fa-user-shield float-left mx-2"></i>
                            Admins
                            <span><i class="fa fa-angle-right float-right"></i></span>
                        </a>
                    </li>

                </ul>

            </aside>

            <!--/Sidebar-->
            <!--Main-->
            <main class="bg-white-300 flex-1 p-3 overflow-hidden">

                <div class="flex flex-col">
                    <!-- Stats Row Starts Here -->
                    <div class="flex flex-1 flex-col md:flex-row lg:flex-row mx-2">
                        <div class="shadow-lg bg-red-vibrant border-l-8 hover:bg-red-vibrant-dark border-red-vibrant-dark mb-2 p-2 md:w-1/4 mx-2">
                            <div class="p-4 flex flex-col">
                                <a href="#" class="no-underline text-white text-2xl">
                                    {{ $albumCount }}
                                </a>
                                <a href="#" class="no-underline text-white text-lg">
                                    Колличество Альбомов
                                </a>
                            </div>
                        </div>

                        <div class="shadow bg-info border-l-8 hover:bg-info-dark border-info-dark mb-2 p-2 md:w-1/4 mx-2">
                            <div class="p-4 flex flex-col">
                                <a href="#" class="no-underline text-white text-2xl">
                                    {{ $photoCount }}
                                </a>
                                <a href="#" class="no-underline text-white text-lg">
                                    Колличество Фото
                                </a>
                            </div>
                        </div>

                        <div class="shadow bg-warning border-l-8 hover:bg-warning-dark border-warning-dark mb-2 p-2 md:w-1/4 mx-2">
                            <div class="p-4 flex flex-col">
                                <a href="#" class="no-underline text-white text-2xl">
                                    {{ $diskFreeSpace }}
                                </a>
                                <a href="#" class="no-underline text-white text-lg">
                                    Свободного места на Сервере
                                </a>
                            </div>
                        </div>
                    </div>
                    <!--Profile Tabs-->

                    <div class="container mx-auto px-4 py-8">
                        <h1 class="text-2xl font-semibold mb-4">Dashboard</h1>
                        <div class="flex flex-1 flex-col md:flex-row lg:flex-row mx-2">
                            <div class="mb-2 border-solid border-gray-300 rounded border shadow-sm w-full">
                                <div class="bg-gray-200 px-2 py-3 border-solid border-gray-200 border-b">
                                    Album Information
                                </div>
                                <div class="p-3">
                                    <table class="table-auto w-full rounded">
                                        <thead>
                                        <tr>
                                            <th class="border px-4 py-2">IP Address</th>
                                            <th class="border px-4 py-2">Upload Time</th>
                                            <th class="border px-4 py-2">Number of Photos</th>
                                            <th class="border px-4 py-2">Album Password</th>
                                            <th class="border px-4 py-2">Views</th>
                                            <th class="border px-4 py-2">Delete After Views</th>
                                            <th class="border px-4 py-2">Actions</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($albums as $album)
                                            <tr>
                                                <td class="border px-4 py-2">{{ $album->ip_address }}</td>
                                                <td class="border px-4 py-2">{{ $album->created_at }}</td>
                                                <td class="border px-4 py-2">{{ $album->photos->count() }}</td>
                                                <td class="border px-4 py-2">
                                                    {{ $album->password ? 'Yes' : 'No' }}
                                                </td>
                                                <td class="border px-4 py-2">{{ $album->views }}</td>
                                                <td class="border px-4 py-2">{{ $album->delete_after_views ?? 'N/A' }}</td>
                                                <td class="border px-4 py-2">
                                                    <a href="{{ route('albums.show', ['secure_token' => $album->secure_token]) }}" class="bg-teal-300 cursor-pointer rounded p-1 mx-1 text-white">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="#" class="bg-red-500 cursor-pointer rounded p-1 mx-1 text-white"
                                                       onclick="event.preventDefault();
                                    document.getElementById('delete-album-{{ $album->id }}').submit();">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                    <form id="delete-album-{{ $album->id }}" action="{{ route('albums.destroy', $album->id) }}" method="POST" style="display: none;">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                    <a href="#" class="bg-purple-500 cursor-pointer rounded p-1 mx-1 text-white"
                                                       onclick="event.preventDefault();
                                    document.getElementById('ban-ip-{{ $album->id }}').submit();">
                                                        <i class="fas fa-ban"></i>
                                                    </a>
                                                    <form id="ban-ip-{{ $album->id }}" action="{{ route('admin.banIp', ['ip_address' => $album->ip_address]) }}" method="POST" style="display: none;">
                                                        @csrf
                                                        @method('POST')
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>


                    <form action="{{ route('admin.banIp') }}" method="POST" class="w-full max-w-lg">
                        @csrf <!-- Laravel CSRF защита -->
                        <div class="md:flex md:items-center mb-2 py-8">
                            <div class="md:w-1/4">
                                <label class="block text-gray-500 font-regular md:text-right mb-1 md:mb-0 pr-4"
                                       for="inline-ip-address">
                                    IP Address
                                </label>
                            </div>
                            <div class="md:w-3/4">
                                <input class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                       id="inline-ip-address" name="ip_address" type="text" placeholder="Enter IP address to ban">
                            </div>
                        </div>

                        <div class="md:flex md:items-center">
                            <div class="md:w-1/4"></div>
                            <div class="md:w-3/4">
                                <button class="shadow bg-purple-500 hover:bg-purple-400 focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded"
                                        type="submit">
                                    Ban IP
                                </button>
                            </div>
                        </div>
                    </form>

                    <div class="relative w-full">
                        <button data-modal="deletePostsModal" id="openDeletePostsModal" class="modal-trigger bg-red-500 text-white font-bold py-2 px-4 rounded-lg shadow-lg absolute right-0 bottom-0 mb-4 mr-4">
                            Удалить посты за период
                        </button>
                    </div>

                    <!-- Модальное окно -->
                    <div id="deletePostsModal" class="modal-wrapper fixed z-10 inset-0 overflow-y-auto hidden">
                        <div class="flex items-center justify-center min-h-screen px-4">
                            <div class="bg-white rounded-lg shadow-lg w-full max-w-lg">
                                <div class="p-5">
                                    <h3 class="text-lg font-semibold">Удалить посты</h3>
                                    <p class="text-sm text-gray-500">Выберите временной диапазон для удаления постов:</p>
                                    <form id="deletePostsForm" action="{{ route('admin.deletePosts') }}" method="POST">
                                        @csrf
                                        <div class="mt-4">
                                            <label for="start_date" class="block text-sm">Начальная дата</label>
                                            <input type="date" id="start_date" name="start_date" class="w-full p-2 border rounded">
                                        </div>
                                        <div class="mt-4">
                                            <label for="start_time" class="block text-sm">Начальное время</label>
                                            <input type="time" id="start_time" name="start_time" class="w-full p-2 border rounded">
                                        </div>
                                        <div class="mt-4">
                                            <label for="end_date" class="block text-sm">Конечная дата</label>
                                            <input type="date" id="end_date" name="end_date" class="w-full p-2 border rounded">
                                        </div>
                                        <div class="mt-4">
                                            <label for="end_time" class="block text-sm">Конечное время</label>
                                            <input type="time" id="end_time" name="end_time" class="w-full p-2 border rounded">
                                        </div>
                                        <div class="mt-4 flex justify-between">
                                            <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded">Удалить</button>
                                            <button type="button" class="close-modal bg-gray-300 px-4 py-2 rounded">Отмена</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!--/Profile Tabs-->
                </div>
            </main>
            <!--/Main-->
        </div>
        <!--Footer-->
        <footer class="bg-grey-darkest text-white p-2">
            <div class="flex flex-1 mx-auto">&copy; BeachPhoto</div>
        </footer>
        <!--/footer-->

    </div>

</div>
<script src="{{ asset('admin/js/main.js') }}"></script>
</body>

</html>
