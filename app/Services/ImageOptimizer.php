<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageOptimizer
{
    private const QUALITY_SETTINGS = [
        'featured_image' => 82,
        'logo' => 88,
        'avatar' => 80,
        'thumbnail' => 75,
        'og_image' => 82,
        'favicon' => 90,
        'news_content' => 80,
    ];

    private const RESIZE_DIMENSIONS = [
        'featured_image' => ['width' => 1200, 'height' => 630],
        'logo' => ['width' => 500, 'height' => null],
        'avatar' => ['width' => 300, 'height' => 300],
        'thumbnail' => ['width' => 600, 'height' => 400],
        'og_image' => ['width' => 1200, 'height' => 630],
        'favicon' => ['width' => 32, 'height' => 32],
    ];

    private const MAX_FILE_SIZE = 1048576; // 1MB

    public function optimize(UploadedFile $file, string $type = 'featured_image', string $path = 'images'): string
    {
        try {
            $image = $this->createImageFromFile($file);

            if ($image === null) {
                return $this->storeFallback($file, $path);
            }

            $dimensions = self::RESIZE_DIMENSIONS[$type] ?? null;
            if ($dimensions) {
                $image = $this->resizeImage($image, $dimensions['width'], $dimensions['height']);
            }

            $quality = self::QUALITY_SETTINGS[$type] ?? 82;
            $filename = Str::random(40) . '.webp';
            $fullPath = storage_path('app/public/' . $path);

            if (!is_dir($fullPath)) {
                mkdir($fullPath, 0755, true);
            }

            $outputFile = $fullPath . '/' . $filename;
            $this->saveAsWebp($image, $outputFile, $quality);
            imagedestroy($image);

            return $path . '/' . $filename;

        } catch (\Exception $e) {
            \Log::error('Image optimization failed: ' . $e->getMessage(), [
                'file' => $file->getClientOriginalName(),
                'type' => $type,
            ]);

            return $this->storeFallback($file, $path);
        }
    }

    public function optimizeWithDimensions(
        UploadedFile $file,
        int $width,
        ?int $height = null,
        int $quality = 82,
        string $path = 'images'
    ): string {
        try {
            $image = $this->createImageFromFile($file);

            if ($image === null) {
                return $this->storeFallback($file, $path);
            }

            $image = $this->resizeImage($image, $width, $height);

            $filename = Str::random(40) . '.webp';
            $fullPath = storage_path('app/public/' . $path);

            if (!is_dir($fullPath)) {
                mkdir($fullPath, 0755, true);
            }

            $outputFile = $fullPath . '/' . $filename;
            $this->saveAsWebp($image, $outputFile, $quality);
            imagedestroy($image);

            return $path . '/' . $filename;

        } catch (\Exception $e) {
            \Log::error('Image optimization with dimensions failed: ' . $e->getMessage());
            return $this->storeFallback($file, $path);
        }
    }

    public function delete(string $path): bool
    {
        if ($path && Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->delete($path);
        }
        return false;
    }

    public function getQuality(string $type): int
    {
        return self::QUALITY_SETTINGS[$type] ?? 82;
    }

    public function getDimensions(string $type): ?array
    {
        return self::RESIZE_DIMENSIONS[$type] ?? null;
    }

    private function createImageFromFile(UploadedFile $file): ?\GdImage
    {
        $mime = $file->getMimeType();
        $path = $file->getRealPath();

        return match ($mime) {
            'image/jpeg', 'image/jpg' => @imagecreatefromjpeg($path),
            'image/png' => $this->createFromPngWithAlpha($path),
            'image/gif' => @imagecreatefromgif($path),
            'image/webp' => @imagecreatefromwebp($path),
            'image/bmp', 'image/x-ms-bmp' => @imagecreatefrombmp($path),
            default => null,
        } ?: null;
    }

    private function createFromPngWithAlpha(string $path): ?\GdImage
    {
        $image = @imagecreatefrompng($path);
        if (!$image) {
            return null;
        }
        imagealphablending($image, true);
        imagesavealpha($image, true);
        return $image;
    }

    private function resizeImage(\GdImage $image, int $maxWidth, ?int $maxHeight): \GdImage
    {
        $origWidth = imagesx($image);
        $origHeight = imagesy($image);

        if ($maxHeight === null) {
            if ($origWidth <= $maxWidth) {
                return $image;
            }
            $ratio = $maxWidth / $origWidth;
            $newWidth = $maxWidth;
            $newHeight = (int) round($origHeight * $ratio);
        } else {
            if ($origWidth <= $maxWidth && $origHeight <= $maxHeight) {
                return $image;
            }
            $ratioW = $maxWidth / $origWidth;
            $ratioH = $maxHeight / $origHeight;
            $ratio = min($ratioW, $ratioH);
            $newWidth = (int) round($origWidth * $ratio);
            $newHeight = (int) round($origHeight * $ratio);
        }

        $resized = imagecreatetruecolor($newWidth, $newHeight);
        imagealphablending($resized, false);
        imagesavealpha($resized, true);
        $transparent = imagecolorallocatealpha($resized, 0, 0, 0, 127);
        imagefill($resized, 0, 0, $transparent);

        imagecopyresampled($resized, $image, 0, 0, 0, 0, $newWidth, $newHeight, $origWidth, $origHeight);
        imagedestroy($image);

        return $resized;
    }

    private function saveAsWebp(\GdImage $image, string $outputFile, int $quality): void
    {
        imagewebp($image, $outputFile, $quality);

        // If file exceeds 1MB, reduce quality progressively
        while (filesize($outputFile) > self::MAX_FILE_SIZE && $quality > 30) {
            $quality -= 8;
            imagewebp($image, $outputFile, $quality);
        }
    }

    private function storeFallback(UploadedFile $file, string $path): string
    {
        $filename = Str::random(40) . '.webp';
        return $file->storeAs($path, $filename, 'public');
    }
}
