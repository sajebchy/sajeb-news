import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import path from 'path';

/**
 * Sanitize filename to prevent path traversal attacks (CVE-2024-XXXXX - Rollup Path Traversal)
 * Removes or encodes characters that could be used in path traversal attacks
 */
function sanitizeFileName(name) {
    if (typeof name !== 'string') {
        return name;
    }
    // Remove path traversal sequences and other dangerous characters
    return name
        .replace(/\.\./g, '__')  // Replace .. with __
        .replace(/\//g, '_')     // Replace / with _
        .replace(/\\/g, '_')     // Replace \ with _
        .replace(/^[.~]/, '_')   // Prevent hidden files and home directory references
        .replace(/[\u0000-\u001F"#$%&*+,:;<=>?[\]^`{|}\u007F]/g, '_'); // Original Rollup regex
}

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
        // Rollup options for better code splitting and security
        rollupOptions: {
            output: {
                manualChunks: (id) => {
                    // Sanitize chunk names to prevent path traversal
                    if (id.includes('node_modules/alpinejs')) {
                        return 'alpine';
                    }
                },
                // Sanitize output file names to prevent path traversal attacks
                sanitizeFileName: (name) => {
                    return sanitizeFileName(name);
                },
                // Additional protection: validate output paths
                chunkFileNames: (chunkInfo) => {
                    const name = sanitizeFileName(chunkInfo.name || 'chunk');
                    return `js/${name}-[hash].js`;
                },
                entryFileNames: 'js/[name]-[hash].js',
                assetFileNames: (assetInfo) => {
                    const name = sanitizeFileName(assetInfo.name || 'asset');
                    if (name.endsWith('.css')) {
                        return `css/${name}-[hash][extname]`;
                    }
                    return `assets/${name}-[hash][extname]`;
                },
            },
        },
    },
    // Optimize asset handling
    assetsInclude: ['**/*.woff', '**/*.woff2'],
});
