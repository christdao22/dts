import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import PluginInspect from 'vite-plugin-inspect';

export default defineConfig({
    plugins: [
        PluginInspect(),
        laravel({
            input: [
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
});
