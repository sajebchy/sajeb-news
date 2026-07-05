<?php

if (!function_exists('storage_image_url')) {
    /**
     * Resolve storage image URL, handling CDN webp conversion.
     */
    function storage_image_url(?string $path): ?string
    {
        if (!$path) {
            return null;
        }

        // If path already ends with .webp, return as-is
        if (str_ends_with(strtolower($path), '.webp')) {
            return \Storage::url($path);
        }

        // Check if original file exists
        if (\Storage::disk('public')->exists($path)) {
            return \Storage::url($path);
        }

        // Try .webp version (CDN may have converted the file)
        $webpPath = preg_replace('/\.(jpe?g|png|gif|bmp)$/i', '.webp', $path);
        if ($webpPath !== $path && \Storage::disk('public')->exists($webpPath)) {
            return \Storage::url($webpPath);
        }

        // Fallback to original path
        return \Storage::url($path);
    }
}
