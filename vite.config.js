import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

// Updated: Include all CSS files for production build
export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/admin.css',
                'resources/css/class-detail.css',
                'resources/css/footer.css',
                'resources/css/forgot.css',
                'resources/css/guru.css',
                'resources/css/landing-page.css',
                'resources/css/login.css',
                'resources/css/navbar.css',
                'resources/css/notifications.css',
                'resources/css/questions.css',
                'resources/css/register.css',
                'resources/css/siswa.css',
                'resources/js/app.js'
            ],
            refresh: true,
        }),
    ],
    server: {
        host: '127.0.0.1',
        port: 5173,
    },
});
