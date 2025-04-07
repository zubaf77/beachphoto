document.addEventListener('DOMContentLoaded', function () {
    // *** Управление выпадающим меню профиля ***
    var profileDropdown = document.getElementById('ProfileDropDown');
    var profileToggleBtn = document.querySelector('img[onclick="profileToggle()"]');

    function profileToggle() {
        if (profileDropdown.classList.contains('hidden')) {
            profileDropdown.classList.remove('hidden');
            profileDropdown.classList.add('block');
        } else {
            profileDropdown.classList.remove('block');
            profileDropdown.classList.add('hidden');
        }
    }

    if (profileToggleBtn && profileDropdown) {
        console.log('Элементы профиля найдены. Готовы к взаимодействию.');

        // Удаляем атрибут onclick, чтобы избежать двойного действия
        profileToggleBtn.removeAttribute('onclick');

        // Добавляем обработчик события клика на аватар
        profileToggleBtn.addEventListener('click', function (event) {
            event.stopPropagation(); // Останавливаем всплытие события
            profileToggle(); // Переключение видимости выпадающего меню профиля
        });

        // Глобальный обработчик для скрытия меню при клике вне его
        window.addEventListener('click', function () {
            if (profileDropdown.classList.contains('block')) {
                profileDropdown.classList.remove('block');
                profileDropdown.classList.add('hidden');
                console.log('Клик вне меню, меню скрыто');
            }
        });

        // Останавливаем скрытие меню при клике внутри самого меню
        profileDropdown.addEventListener('click', function (event) {
            event.stopPropagation(); // Останавливаем всплытие события
            console.log('Клик внутри меню');
        });
    }

    // *** Управление боковой панелью (sidebar) ***
    var sidebar = document.getElementById('sidebar');

    function sidebarToggle() {
        if (sidebar.classList.contains('hidden')) {
            sidebar.classList.remove('hidden');
            sidebar.classList.add('block');
        } else {
            sidebar.classList.remove('block');
            sidebar.classList.add('hidden');
        }
    }

    var sidebarToggleBtn = document.querySelector('#sidebarToggle');
    if (sidebarToggleBtn) {
        sidebarToggleBtn.addEventListener('click', sidebarToggle);
    }

    var logoToggleBtn = document.getElementById('logoToggle');
    if (logoToggleBtn) {
        logoToggleBtn.addEventListener('click', sidebarToggle);
    }

        // *** Управление модальными окнами ***
        function toggleModal(action, elem_trigger) {
            elem_trigger.addEventListener('click', function () {
                let modal_id = action === 'add'
                    ? this.dataset.modal // Получаем id модального окна из data-modal
                    : elem_trigger.closest('.modal-wrapper').getAttribute('id'); // Получаем id окна при закрытии

                if (modal_id) {
                    let modalElement = document.getElementById(modal_id);
                    if (modalElement) {
                        if (action === 'add') {
                            modalElement.classList.remove('hidden'); // Показываем модальное окно
                            modalElement.classList.add('modal-is-open');
                        } else {
                            modalElement.classList.remove('modal-is-open'); // Закрываем модальное окно
                            modalElement.classList.add('hidden');
                        }
                    }
                }
            });
        }

        // Проверяем наличие модальных окон на странице
        if (document.querySelector('.modal-wrapper')) {
            // Открытие модальных окон
            document.querySelectorAll('.modal-trigger').forEach(btn => {
                toggleModal('add', btn);
            });

            // Закрытие модальных окон
            document.querySelectorAll('.close-modal').forEach(btn => {
                toggleModal('remove', btn);
            });
        }
    });
