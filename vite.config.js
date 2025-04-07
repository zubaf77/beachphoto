import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: false, // Отключаем автоматическое обновление страницы
        }),
    ],
    resolve: {
        alias: {
            '@': '/resources',
        },
    },
    server: {
        fs: {
            strict: true, // Строгий доступ к файловой системе
        },
        hmr: false, // Отключаем HMR (горячую перезагрузку) на production
    },
    build: {
        sourcemap: false, // Отключаем source maps для защиты исходного кода
        minify: 'terser', // Включаем минификацию кода для уменьшения размера файлов
        rollupOptions: {
            output: {
                entryFileNames: 'assets/[name].[hash].js',
                chunkFileNames: 'assets/[name].[hash].js',
                assetFileNames: 'assets/[name].[ext]',
            },
        },
    },
    logLevel: 'silent', // Отключаем логи в production для повышения безопасности
});
