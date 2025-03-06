import {defineConfig} from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            buildDirectory: 'vendor/schedule-manager',
            input: ['resources/css/schedule-manager.css'],
            refresh: true,
        }),
        tailwindcss(),
    ],
});