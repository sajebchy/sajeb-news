import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    build: {
        // Optimize build output for better performance
        minify: 'terser',
        terserOptions: {
            compress: {
                drop_console: true,
            },
        },
        // Enable CSS code splitting for better caching
        cssCodeSplit: true,
        // Increase chunk size limit for better bundling
        chunkSizeWarningLimit: 1000,
        // Rollup options for better code splitting
        rollupOptions: {
            output: {
                manualChunks: {
                    // Separate vendor libraries for better caching
                    'alpine': ['alpinejs'],
                },
            },
        },
    },
    // Optimize asset handling
    assetsInclude: ['**/*.woff', '**/*.woff2'],
});
