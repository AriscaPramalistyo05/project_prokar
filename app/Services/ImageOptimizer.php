<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class ImageOptimizer
{
    /**
     * Optimizes an uploaded image (resizes to max width and converts to WebP)
     * and stores it in the given folder on the specified disk.
     *
     * @param UploadedFile|string $file
     * @param string $folder e.g. 'products', 'settings', 'service_images'
     * @param int $maxWidth Max width in pixels (default 1200)
     * @param int $quality WebP quality (1-100, default 80)
     * @param string $disk (default 'public')
     * @return string Stored relative path e.g. 'products/abc123xyz.webp'
     */
    public static function optimizeAndStore(
        UploadedFile|string $file,
        string $folder = 'products',
        int $maxWidth = 1200,
        int $quality = 80,
        string $disk = 'public'
    ): string {
        try {
            // Check if it's a video or non-image
            if ($file instanceof UploadedFile) {
                $mime = $file->getMimeType() ?? '';
                $extension = strtolower($file->getClientOriginalExtension());
                $videoExts = ['mp4', 'mov', 'avi', 'webm'];
                if (in_array($extension, $videoExts) || str_starts_with($mime, 'video/')) {
                    return $file->store($folder, $disk);
                }
            }

            $manager = new ImageManager(new Driver());
            $image = $manager->read($file);

            // Scale down if larger than maxWidth
            if ($image->width() > $maxWidth) {
                $image->scaleDown(width: $maxWidth);
            }

            // Encode to WebP
            $encoded = $image->toWebp($quality);

            $filename = Str::random(40) . '.webp';
            $path = trim($folder, '/') . '/' . $filename;

            Storage::disk($disk)->put($path, (string) $encoded);

            return $path;
        } catch (\Throwable $e) {
            // Fallback to standard store if GD / Intervention fails
            if ($file instanceof UploadedFile) {
                return $file->store($folder, $disk);
            }
            throw $e;
        }
    }
}
