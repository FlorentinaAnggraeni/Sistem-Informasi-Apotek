<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class ImageHelper
{
    /**
     * Get image URL with fallback
     */
    public static function getImageUrl($path, $default = null)
    {
        if (!$path) {
            return $default ?? asset('images/no-image.png');
        }

        // Check if file exists
        if (Storage::disk('public')->exists($path)) {
            return asset('storage/' . $path);
        }

        return $default ?? asset('images/no-image.png');
    }

    /**
     * Get image with responsive sizes
     */
    public static function getResponsiveImage($path, $alt = 'Image', $class = 'img-fluid')
    {
        $url = self::getImageUrl($path);
        $extension = pathinfo($path, PATHINFO_EXTENSION);
        
        return sprintf(
            '<img src="%s" alt="%s" class="%s" loading="lazy" decoding="async">',
            htmlspecialchars($url),
            htmlspecialchars($alt),
            htmlspecialchars($class)
        );
    }

    /**
     * Get thumbnail version of image (same as original due to compression)
     */
    public static function getThumbnail($path, $size = 'md')
    {
        // Since we compress all to same size, return main image
        return self::getImageUrl($path);
    }

    /**
     * Check if image exists
     */
    public static function exists($path)
    {
        return $path && Storage::disk('public')->exists($path);
    }

    /**
     * Get image size in bytes
     */
    public static function getSize($path)
    {
        if (self::exists($path)) {
            return Storage::disk('public')->size($path);
        }
        return 0;
    }

    /**
     * Format file size to human readable
     */
    public static function formatFileSize($bytes)
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= (1 << (10 * $pow));

        return round($bytes, 2) . ' ' . $units[$pow];
    }
}
