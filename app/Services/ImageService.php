<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class ImageService
{
    /**
     * Upload gambar dengan kompresi atau fallback
     */
    public function uploadAndCompress($file, $folder = 'obat', $maxWidth = 800, $quality = 80)
    {
        try {
            // Validate file
            if (!$file->isValid()) {
                throw new \Exception('File upload tidak valid');
            }

            // Generate unique filename dengan hash
            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '_' . uniqid() . '_' . $this->slugify($originalName) . '.' . strtolower($extension);
            
            // Cek apakah Intervention Image tersedia
            if (class_exists('\Intervention\Image\Laravel\Facades\Image')) {
                return $this->uploadWithIntervention($file, $folder, $filename, $maxWidth, $quality);
            } else {
                // Fallback: upload langsung dengan validasi
                return $this->uploadDirect($file, $folder, $filename);
            }
            
        } catch (\Exception $e) {
            \Log::error('Image upload error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Upload dengan Intervention Image
     */
    private function uploadWithIntervention($file, $folder, $filename, $maxWidth, $quality)
    {
        $image = \Intervention\Image\Laravel\Facades\Image::read($file->getRealPath());
        
        // Resize jika terlalu besar
        if ($image->width() > $maxWidth) {
            $image->scale(width: $maxWidth);
        }
        
        // Encode ke JPEG dengan quality
        $encoded = $image->toJpeg($quality);
        
        // Save ke storage
        $path = $folder . '/' . $filename;
        Storage::disk('public')->put($path, $encoded, 'public');
        
        return $path;
    }

    /**
     * Upload langsung (fallback jika Intervention tidak ada)
     */
    private function uploadDirect($file, $folder, $filename)
    {
        // Validate MIME type ketat
        $mimeType = $file->getMimeType();
        $validMimes = ['image/jpeg', 'image/png', 'image/jpg'];
        
        if (!in_array($mimeType, $validMimes)) {
            throw new \Exception('Format file tidak didukung: ' . $mimeType);
        }

        // Read file content
        $content = file_get_contents($file->getRealPath());
        
        if (!$content) {
            throw new \Exception('Gagal membaca konten file');
        }

        // Verify image
        if (!$this->isValidImage($content)) {
            throw new \Exception('File bukan image valid');
        }

        // Save ke storage
        $path = $folder . '/' . $filename;
        Storage::disk('public')->put($path, $content, 'public');
        
        return $path;
    }

    /**
     * Validate image content
     */
    private function isValidImage($imageData)
    {
        // Check image header
        $handle = fopen('php://memory', 'r+');
        fwrite($handle, $imageData);
        rewind($handle);
        
        $data = fgets($handle, 12);
        fclose($handle);
        
        // Check magic bytes untuk JPEG/PNG
        $jpegHeader = substr($imageData, 0, 3) === "\xFF\xD8\xFF";
        $pngHeader = substr($imageData, 0, 8) === "\x89PNG\r\n\x1a\n";
        
        return $jpegHeader || $pngHeader;
    }

    /**
     * Slugify filename
     */
    private function slugify($text)
    {
        $text = preg_replace('~[^\\pL\d]+~u', '_', $text);
        $text = trim($text, '_');
        return substr($text, 0, 20); // Limit to 20 chars
    }

    /**
     * Upload gambar (untuk compatibility dengan multiple sizes)
     */
    public function uploadMultipleSizes($file, $folder = 'obat')
    {
        // Upload dengan compression standar
        $path = $this->uploadAndCompress($file, $folder);
        
        return [
            'thumbnail' => $path,
            'medium' => $path,
            'large' => $path,
        ];
    }

    /**
     * Delete image
     */
    public function delete($path)
    {
        if ($path && Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->delete($path);
        }
        return false;
    }

    /**
     * Delete multiple images
     */
    public function deleteMultiple($paths)
    {
        foreach ($paths as $path) {
            $this->delete($path);
        }
    }

    /**
     * Get image URL
     */
    public function getUrl($path)
    {
        if ($path) {
            return asset('storage/' . $path);
        }
        return asset('images/no-image.png'); // Default image
    }
}
