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
                    <li class=" w-full h-full py-3 px-2 border-b border-light-border">
                        <a href="{{route('admin.dashboard')}}"
                           class="font-sans font-hairline hover:font-normal text-sm text-nav-item no-underline">
                            <i class="fas fa-tachometer-alt float-left mx-2"></i>
                            Dashboard
                            <span><i class="fas fa-angle-right float-right"></i></span>
                        </a>
                    </li>
                    <li class="w-full h-full py-3 px-2 border-b border-light-border bg-white">
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
                        <h1 class="text-2xl font-semibold mb-4">Ban List</h1>
                        <div class="flex flex-1 flex-col md:flex-row lg:flex-row mx-2">
                            <div class="mb-2 border-solid border-gray-300 rounded border shadow-sm w-full">
                                <div class="bg-gray-200 px-2 py-3 border-solid border-gray-200 border-b">
                                    Заблокированные IP-адреса
                                </div>
                                <div class="p-3">
                                    <table class="table-auto w-full rounded">
                                        <thead>
                                        <tr>
                                            <th class="border px-4 py-2">IP Address</th>
                                            <th class="border px-4 py-2">Date Added</th>
                                            <th class="border px-4 py-2">Actions</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($bannedIps as $bannedIp)
                                            <tr>
                                                <td class="border px-4 py-2">{{ $bannedIp->ip_address }}</td>
                                                <td class="border px-4 py-2">{{ $bannedIp->created_at }}</td>
                                                <td class="border px-4 py-2">
                                                    <form id="unban-ip-{{ $bannedIp->ip_address }}" action="{{ route('admin.unbanIp') }}" method="POST" style="display: inline;">
                                                        @csrf
                                                        <input type="hidden" name="ip" value="{{ $bannedIp->ip_address }}">
                                                        <button type="submit" class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600">
                                                            <i class="fas fa-unlock"></i> Unban
                                                        </button>
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
