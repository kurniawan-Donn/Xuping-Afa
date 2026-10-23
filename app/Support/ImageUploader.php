<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Menyimpan gambar upload ke disk public dalam format WebP yang sudah diperkecil,
 * supaya halaman ringan dan hemat kuota hosting. Jika GD/WebP tidak tersedia,
 * file disimpan apa adanya.
 */
class ImageUploader
{
    public static function store(UploadedFile $file, string $directory, int $maxWidth = 1600, int $quality = 82): string
    {
        $image = self::createImage($file);

        if (! $image || ! function_exists('imagewebp')) {
            return $file->store($directory, 'public');
        }

        $width = imagesx($image);
        $height = imagesy($image);

        if ($width > $maxWidth) {
            $newHeight = (int) round($height * $maxWidth / $width);
            $resized = imagecreatetruecolor($maxWidth, $newHeight);
            imagealphablending($resized, false);
            imagesavealpha($resized, true);
            imagecopyresampled($resized, $image, 0, 0, 0, 0, $maxWidth, $newHeight, $width, $height);
            imagedestroy($image);
            $image = $resized;
        }

        ob_start();
        imagewebp($image, null, $quality);
        $contents = ob_get_clean();
        imagedestroy($image);

        $path = trim($directory, '/') . '/' . Str::random(40) . '.webp';
        Storage::disk('public')->put($path, $contents);

        return $path;
    }

    private static function createImage(UploadedFile $file)
    {
        if (! extension_loaded('gd')) {
            return null;
        }

        $path = $file->getRealPath();

        $image = match ($file->getMimeType()) {
            'image/jpeg' => @imagecreatefromjpeg($path),
            'image/png' => @imagecreatefrompng($path),
            'image/webp' => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($path) : null,
            default => null,
        };

        if (! $image) {
            return null;
        }

        if (! imageistruecolor($image)) {
            imagepalettetotruecolor($image);
        }
        imagealphablending($image, true);
        imagesavealpha($image, true);

        return self::fixOrientation($image, $file);
    }

    /**
     * Foto dari kamera HP sering tersimpan miring; putar sesuai data EXIF.
     */
    private static function fixOrientation($image, UploadedFile $file)
    {
        if ($file->getMimeType() !== 'image/jpeg' || ! function_exists('exif_read_data')) {
            return $image;
        }

        $orientation = @exif_read_data($file->getRealPath())['Orientation'] ?? 1;

        return match ((int) $orientation) {
            3 => imagerotate($image, 180, 0),
            6 => imagerotate($image, -90, 0),
            8 => imagerotate($image, 90, 0),
            default => $image,
        };
    }
}
