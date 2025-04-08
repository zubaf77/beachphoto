<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beach Photo Host</title>
    <script src="https://www.google.com/recaptcha/api.js?hl={{ app()->getLocale() }}" async defer></script>



    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .main-header {
            background-image: url('{{ asset('img/beach.jpg') }}');
            background-size: cover;
            background-position: center;
            height: 200px;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
        }

        .masthead {
            color: yellow;
            text-align: left;
            padding: 10px;
            position: relative;
            z-index: 2;
            display: inline-block;
            background-color: rgba(0, 0, 255, 0.5);
            border-radius: 5px;
            margin: 0;
        }

        .language-switcher {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: rgba(255, 0, 0, 0.5);
            padding: 10px;
            border-radius: 5px;
            z-index: 3;
        }

        .language-switcher select {
            color: white;
            background-color: transparent;
            border: none;
            outline: none;
        }


        .input-width {
            width: 100%;
            margin-bottom: 1rem;
        }

        .form-check {
            margin-top: 3rem;
        }

        .btn-primary {
            margin-top: 4rem;
        }

        .modal-wrapper.hidden {
            display: none;
        }

        .modal-wrapper {
            display: flex;
        }

    </style>
</head>
<body class="bg-gray-100 text-gray-500">
<header class="main-header">
    <div class="language-switcher">
        <form action="{{ route('switch') }}" method="POST">
            @csrf
            <select name="locale" onchange="this.form.submit()">
                <option class="text-purple-500" value="ru" {{ app()->getLocale() == 'ru' ? 'selected' : '' }}>Русский
                </option>
                <option class="text-purple-500" value="en" {{ app()->getLocale() == 'en' ? 'selected' : '' }}>English
                </option>
            </select>
        </form>
    </div>
    <h1 class="masthead">
        BeachPhoto <br>
        &emsp;&emsp; Free Photo Hosting
    </h1>
    <h2 style="color: #8b5cf6; font-size: 1.500rem; margin-top: 0.5rem; text-shadow: 0 0 5px red;">without metadata</h2>
</header>

<div class="container mx-auto p-4">
    <div class="bg-white p-6 rounded-lg shadow-lg">
        <h2 class="text-2xl font-bold mb-4">{{ __('welcome.header') }}</h2>
        @if ($errors->any())
            <div class="bg-red-600 text-white p-3 rounded mb-4">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{ route('albums.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                    {{ __('welcome.name') }}
                </label>
                <input type="text" name="name"
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="description">
                    {{ __('welcome.description') }}
                </label>
                <textarea name="description" rows="3"
                          class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"></textarea>
            </div>

            <div>
            <div class="relative">
                <input type="file" name="photos[]" id="files" multiple class="opacity-0 absolute inset-0 w-full h-full cursor-pointer" />
                <div class="flex items-center justify-center p-4 border-2 border-dashed border-gray-400 bg-gray-50 rounded-md cursor-pointer hover:border-blue-500 hover:bg-blue-50">
                    <span class="text-gray-700 font-medium">{{ __('welcome.draganddrop') }}</span>
                </div>
            </div>
    </div>

            <div id="file-list" class="mt-4 grid grid-cols-3 gap-4"></div>

            <div class="flex items-center mb-4">
                <input type="checkbox" name="delete_after_n_views" id="delete_after_n_views" class="mr-2 leading-tight">
                <label class="block text-gray-700 text-sm font-bold" for="delete_after_n_views">
                    {{ __('welcome.delete') }}
                </label>
            </div>
            <div class="mb-4">
                <input type="number" name="delete_after_views" id="delete_after_views"
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                       placeholder="{{ __('upload.views') }}" disabled>
            </div>
            <button type="button"
                    class="modal-trigger bg-red-500 hover:bg-red-800 text-white font-bold py-2 px-4 rounded-full mb-2"
                    data-modal="modal1"
                    data-header="{{ __('welcome.delete') }}"
                    data-text="{{ __('welcome.description_delete') }}">
                {{ __('welcome.what_is') }}
            </button>
            <div class="mb-2">
                <label class="block text-gray-700 text-sm font-bold" for="password">
                    {{ __('welcome.password') }}
                </label>
                <div class="relative">
                    <input id="password" type="password" name="password"
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                           placeholder="{{ __('upload.password_placeholder') }}">
                    <span toggle="#password" class="fa fa-eye password-icon"></span>
                </div>
            </div>
            <button type="button"
                    class="modal-trigger bg-red-500 hover:bg-red-800 text-white font-bold py-2 px-4 rounded-full mb-2"
                    data-modal="modal2"
                    data-header="{{ __('welcome.password') }}"
                    data-text="{{ __('welcome.description_pass') }}">
                {{ __('welcome.what_is') }}
            </button>
            <div class="mb-4">
                <input type="checkbox" name="resize_image" id="resize_image" class="mr-2 leading-tight">
                <label class="block text-gray-700 text-sm font-bold" for="resize_image">
                    {{ __('welcome.resize') }}
                </label>
            </div>

            <div class="mb-4 flex space-x-2">
                <input type="number" name="width" id="width"
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                       placeholder="{{ __('upload.width') }}" disabled>
                <input type="number" name="height" id="height"
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                       placeholder="{{ __('upload.height') }}" disabled>
            </div>
            <button type="button"
                    class="modal-trigger bg-red-500 hover:bg-red-800 text-white font-bold py-2 px-4 mb-4 rounded-full"
                    data-modal="modal3"
                    data-header="{{ __('welcome.resize') }}"
                    data-text="{{ __('welcome.description_resize') }}">
                {{ __('welcome.what_is') }}
            </button>
            <div class="flex items-center justify-between">
                <button id="submit-all" type="submit"
                        class="mt-8 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    {{ __('welcome.create') }}
                </button>
            </div>
            <div class="flex justify-center mt-4 md:justify-end w-full px-4">
                <div class="flex flex-col items-center bg-yellow-500 p-4 rounded shadow-md">
                    <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>
                </div>
            </div>
        </form>
    </div>

    <div id="modal1" class="modal-wrapper hidden fixed inset-0 flex items-center justify-center z-50">
        <div class="overlay close-modal absolute inset-0 bg-purple-800 opacity-50"></div>
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center mb-4">
                <h2 id="modalHeader" class="text-lg font-bold"></h2>
                <button class="close-modal bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-full px-2 py-1">✖
                </button>
            </div>
            <p id="modalText" class="text-gray-700"></p>
        </div>
    </div>

    <!-- Модальное окно 2 -->
    <div id="modal2" class="modal-wrapper hidden fixed inset-0 flex items-center justify-center z-50">
        <div class="overlay close-modal absolute inset-0 bg-purple-800 opacity-50"></div>
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center mb-4">
                <h2 id="modalHeader" class="text-lg font-bold"></h2>
                <button class="close-modal bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-full px-2 py-1">✖
                </button>
            </div>
            <p id="modalText" class="text-gray-700"></p>
        </div>
    </div>


    <div id="modal3" class="modal-wrapper hidden fixed inset-0 flex items-center justify-center z-50">
        <div class="overlay close-modal absolute inset-0 bg-purple-800 opacity-50"></div>
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center mb-4">
                <h2 id="modalHeader" class="text-lg font-bold"></h2>
                <button class="close-modal bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-full px-2 py-1">✖
                </button>
            </div>
            <p id="modalText" class="text-gray-700"></p>
        </div>
    </div>

    <script>

        const togglePassword = document.querySelector('.fa-eye'); // Ищем элемент с классом 'fa-eye'
        const passwordField = document.getElementById('password'); // Поле ввода пароля

        if (togglePassword) {
            togglePassword.addEventListener('click', function (e) {
                // Переключаем тип поля ввода
                const type = passwordField.type === 'password' ? 'text' : 'password';
                passwordField.type = type;

                // Переключаем иконку между "глазом" и "запрещённым глазом"
                this.classList.toggle('fa-eye');
                this.classList.toggle('fa-eye-slash');
            });
        }

        const fileInput = document.getElementById('files');
        const fileList = document.getElementById('file-list');

        // Функция для отображения превью изображений
        fileInput.addEventListener('change', function () {
            const files = this.files;
            fileList.innerHTML = '';  // Очистить список файлов

            // Перебираем все выбранные файлы
            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                const reader = new FileReader();

                // Создаем элемент для миниатюры
                const previewContainer = document.createElement('div');
                previewContainer.classList.add('relative', 'rounded-md', 'overflow-hidden', 'border', 'border-gray-300', 'shadow-sm');

                // Вешаем событие на загрузку изображения
                reader.onload = function (e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.classList.add('w-full', 'h-full', 'object-cover');  // Устанавливаем размеры и стиль отображения
                    previewContainer.appendChild(img);

                    // Добавление контейнера с изображением в список
                    fileList.appendChild(previewContainer);
                };

                // Чтение файла как data URL для предварительного просмотра
                reader.readAsDataURL(file);
            }
        });

            // Логика открытия модальных окон
            document.querySelectorAll('.modal-trigger').forEach(button => {
                button.addEventListener('click', function () {
                    const modalId = this.getAttribute('data-modal'); // Получаем ID модального окна
                    const modal = document.getElementById(modalId);

                    if (modal) {
                        const modalHeader = modal.querySelector('#modalHeader');
                        const modalText = modal.querySelector('#modalText');

                        // Устанавливаем заголовок и текст
                        modalHeader.textContent = this.getAttribute('data-header') || 'Заголовок по умолчанию';
                        modalText.textContent = this.getAttribute('data-text') || 'Текст по умолчанию';

                        // Показываем модальное окно
                        modal.classList.remove('hidden');
                    }
                });

            // Логика закрытия модальных окон
            document.querySelectorAll('.close-modal, .overlay').forEach(element => {
                element.addEventListener('click', function () {
                    const modal = this.closest('.modal-wrapper');
                    if (modal) {
                        modal.classList.add('hidden'); // Скрываем модальное окно
                    }
                });
            });

            // Логика управления полями ширины и высоты
            const resizeCheckbox = document.getElementById('resize_image');
            const widthInput = document.getElementById('width');
            const heightInput = document.getElementById('height');

            if (resizeCheckbox) {
                resizeCheckbox.addEventListener('change', function () {
                    if (resizeCheckbox.checked) {
                        widthInput.removeAttribute('disabled');
                        heightInput.removeAttribute('disabled');
                    } else {
                        widthInput.setAttribute('disabled', 'true');
                        heightInput.setAttribute('disabled', 'true');
                    }
                });
            }

            // Логика управления полем количества просмотров
            const DafterView = document.getElementById('delete_after_n_views');
            const DelInput = document.getElementById('delete_after_views');

            if (DafterView) {
                DafterView.addEventListener('change', function () {
                    if (DafterView.checked) {
                        DelInput.removeAttribute('disabled');
                    } else {
                        DelInput.setAttribute('disabled', 'true');
                    }
                });
            }
        });

    </script>
</div>
</body>
</html>

