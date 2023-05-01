import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import.meta.glob([ '../images/**', ]);

export default defineConfig({
    plugins: [
        laravel([
            'resources/css/app.css',
            'resources/js/app.js',
        ]),
    ]
});
