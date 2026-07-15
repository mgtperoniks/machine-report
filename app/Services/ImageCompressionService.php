<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageCompressionService
{
    /**
     * Compress an uploaded image using GD library, downscale to max 1000px, and save as JPEG at 60% quality.
     *
     * @param UploadedFile $file
     * @param string $directory
     * @return string Relative path to public storage (e.g., 'photos/maintenance/filename.jpg')
     */
    public function compressAndStore(UploadedFile $file, string $directory = 'photos/maintenance'): string
    {
        $mime = $file->getMimeType();
        $sourceImage = null;

        // 1. Create source image resource based on mime type
        switch ($mime) {
            case 'image/jpeg':
            case 'image/jpg':
                $sourceImage = @imagecreatefromjpeg($file->getRealPath());
                break;
            case 'image/png':
                $sourceImage = @imagecreatefrompng($file->getRealPath());
                break;
            case 'image/webp':
                $sourceImage = @imagecreatefromwebp($file->getRealPath());
                break;
            default:
                // Fallback to try loading it anyway
                $sourceImage = @imagecreatefromstring(file_get_contents($file->getRealPath()));
                break;
        }

        if (!$sourceImage) {
            // If GD creation fails, store raw file as fallback
            return $file->store($directory, 'public');
        }

        // 2. Calculate dimensions to fit inside a 1000x1000 box
        $origWidth = imagesx($sourceImage);
        $origHeight = imagesy($sourceImage);
        $maxSize = 1000;

        $newWidth = $origWidth;
        $newHeight = $origHeight;

        if ($origWidth > $maxSize || $origHeight > $maxSize) {
            if ($origWidth > $origHeight) {
                $newWidth = $maxSize;
                $newHeight = (int) round(($origHeight * $maxSize) / $origWidth);
            } else {
                $newHeight = $maxSize;
                $newWidth = (int) round(($origWidth * $maxSize) / $origHeight);
            }
        }

        // 3. Resample image
        $targetImage = imagecreatetruecolor($newWidth, $newHeight);
        
        // Handle transparency for PNGs and WEBPs
        imagealphablending($targetImage, false);
        imagesavealpha($targetImage, true);
        
        imagecopyresampled(
            $targetImage,
            $sourceImage,
            0, 0, 0, 0,
            $newWidth,
            $newHeight,
            $origWidth,
            $origHeight
        );

        // 4. Save to temporary file as JPEG with 60% quality
        $tempPath = tempnam(sys_get_temp_dir(), 'compressed_img_');
        imagejpeg($targetImage, $tempPath, 60);

        // 5. Store in public disk using Laravel Storage
        $filename = Str::random(40) . '.jpg';
        $targetPath = $directory . '/' . $filename;
        
        Storage::disk('public')->put($targetPath, fopen($tempPath, 'r'));

        // Clean up
        @unlink($tempPath);
        imagedestroy($sourceImage);
        imagedestroy($targetImage);

        return $targetPath;
    }
}
