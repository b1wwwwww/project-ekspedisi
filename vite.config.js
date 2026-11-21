import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/pages/paket-create.js',
                'resources/js/pages/paket-index.js',
                'resources/js/pages/penugasan-kurir.js',
            ],
            refresh: true,
        }),
    ],
});
