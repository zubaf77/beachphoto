<?php

use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\LanguageController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AlbumController;
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\AdminDashboardController;

// Главная страница доступна всем без ограничений
Route::get('/', function () {
    return view('welcome');
});//->withoutMiddleware('check.admin');

// Группа маршрутов для админ-панели, защищенная middleware
Route::prefix('admin')->middleware('check.banned')->middleware('check.admin')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/ban_list',[AdminDashboardController::class, 'banList'])->name('admin.banList');
    Route::post('/unban_ip',[AdminDashboardController::class, 'unbanIp'])->name('admin.unbanIp');
    Route::post('/banIp',[AdminDashboardController::class, 'banIp'])->name('admin.banIp');
    Route::post('/delete-posts', [AdminDashboardController::class, 'deletePosts'])->name('admin.deletePosts');
    Route::post('/logout', [AdminLoginController::class, 'logout'])->name('admin.logout');
    Route::post('/change-password', [AdminDashboardController::class, 'changePassword'])->name('admin.changePassword');
    Route::get('/change-password', [AdminDashboardController::class, 'showChangePasswordForm'])->name('admin.showChangePasswordForm');
    Route::get('/admins', [AdminDashboardController::class, 'adminList'])->name('admin.adminList');
    Route::post('/admins/store', [AdminDashboardController::class, 'addAdmin'])->name('admin.store');
    Route::delete('/admins/{id}', [AdminDashboardController::class, 'removeAdmin'])->name('admin.destroy');

});

Route::get('/admin/login', [AdminLoginController::class, 'showLoginForm'])->name('admin.loginForm')->middleware('check.banned');

Route::post('/admin/login', [AdminLoginController::class, 'login'])->name('admin.login')->middleware('check.banned');

// Маршруты для AlbumController, доступные для просмотра всем, но создание ограничено middleware
Route::prefix('albums')->name('albums.')->group(function () {
    Route::get('/', function () {
        abort(404);
    });
    Route::get('/create', [AlbumController::class, 'create'])->name('create')->middleware('check.banned'); // Страница создания альбома
    Route::get('/{secure_token}', [AlbumController::class, 'show'])->name('show'); // Показать альбом по токену
    Route::post('/{secure_token}/check', [AlbumController::class, 'checkPassword'])->name('checkPassword');
    Route::get('/{secure_token}/check', function () {
        abort(404);
    });
    Route::post('/', [AlbumController::class, 'store'])->name('store')->middleware('check.banned'); // Сохранить новый альбом

    Route::delete('/{id}', [AlbumController::class, 'destroy'])->name('destroy')->middleware('check.banned');
});

// Маршруты для PhotoController, доступные для просмотра всем, но создание ограничено middleware
Route::prefix('photos')->name('photos.')->group(function () {
    Route::get('/create', [PhotoController::class, 'create'])->name('create')->middleware('check.banned');
    Route::get('/{photo}', [PhotoController::class, 'show'])->name('show');
    Route::post('/', [PhotoController::class, 'store'])->name('store')->middleware('check.banned');
});

// Маршрут для переключения языка доступен всем
Route::post('/lang/switch', [LanguageController::class, 'switchLang'])->name('lang.switch');
Route::post('/switch-lang', [LanguageController::class, 'switchLang'])->name('switch');


