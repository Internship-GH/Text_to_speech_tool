import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/tabs.js', 
                'resources/js/convert.js', 'resources/js/translate.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],
   
});
