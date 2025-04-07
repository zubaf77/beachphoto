<?php
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Foundation\Application;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up'
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            \App\Http\Middleware\LocaleMiddleware::class,
            \App\Http\Middleware\ValidateAlbumToken::class,
            //\App\Http\Middleware\CheckBannedIp::class,
           // \App\Http\Middleware\AdminMiddleware::class,
        ]);
        $middleware->alias([
            'check.banned' => \App\Http\Middleware\CheckBannedIp::class,
            'check.admin' => \App\Http\Middleware\AdminMiddleware::class,
        ]);
    })
    ->withExceptions(function ($exceptions) {
        // Пример обработки исключений
        $exceptions->reportable(function (Throwable $e) {
            // Логирование исключений
        });

        $exceptions->renderable(function (Throwable $e, $request) {
            //sombd
        });
    })
    ->create();
