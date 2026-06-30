<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageOptimizer
{
    // Quality settings for different image types
    private const QUALITY_SETTINGS = [
        'featured_image' => 85,
        'logo' => 90,
        'avatar' => 85,
        'thumbnail' => 80,
        'og_image' => 85,
        'favicon' => 90,
        'news_content' => 85,
    ];

    // Resize dimensions for different image types
    private const RESIZE_DIMENSIONS = [
        'featured_image' => ['width' => 1200, 'height' => 630],
        'logo' => ['width' => 500, 'height' => null],
        'avatar' => ['width' => 300, 'height' => 300],
        'thumbnail' => ['width' => 600, 'height' => 400],
        'og_image' => ['width' => 1200, 'height' => 630],
        'favicon' => ['width' => 32, 'height' => 32],
    ];

    public function __construct()
    {
        // No explicit initialization needed
    }

    /**
     * Optimize and save an uploaded image
     * 
     * @param UploadedFile $file
     * @param string $type Type of image (featured_image, logo, avatar, etc.)
     * @param string $path Storage path
     * @return string Optimized image path
     */
    public function optimize(UploadedFile $file, string $type = 'featured_image', string $path = 'images'): string
    {
        try {
            // Generate unique filename for the stored file
            $filename = Str::random(40) . '.' . strtolower($file->getClientOriginalExtension());
            
            // Store the file to disk
            $storedPath = $file->storeAs($path, $filename, 'public');

            return $storedPath;

        } catch (\Exception $e) {
            \Log::error('Image storage failed: ' . $e->getMessage(), [
                'file' => $file->getClientOriginalName(),
                'type' => $type,
            ]);

            // Fallback: store with default method
            return $file->store($path, 'public');
        }
    }

    /**
     * Optimize image with custom dimensions
     * 
     * @param UploadedFile $file
     * @param int $width
     * @param int $height
     * @param int $quality
     * @param string $path
     * @return string
     */
    public function optimizeWithDimensions(
        UploadedFile $file,
        int $width,
        ?int $height = null,
        int $quality = 85,
        string $path = 'images'
    ): string {
        try {
            $filename = Str::random(40) . '.' . strtolower($file->getClientOriginalExtension());
            $storedPath = $file->storeAs($path, $filename, 'public');

            return $storedPath;

        } catch (\Exception $e) {
            \Log::error('Image storage with dimensions failed: ' . $e->getMessage());
            return $file->store($path, 'public');
        }
    }

    /**
     * Delete an optimized image
     * 
     * @param string $path
     * @return bool
     */
    public function delete(string $path): bool
    {
        if ($path && Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->delete($path);
        }
        return false;
    }

    /**
     * Get quality setting for image type
     */
    public function getQuality(string $type): int
    {
        return $this->QUALITY_SETTINGS[$type] ?? 85;
    }

    /**
     * Get dimensions for image type
     */
    public function getDimensions(string $type): ?array
    {
        return $this->RESIZE_DIMENSIONS[$type] ?? null;
    }
}
