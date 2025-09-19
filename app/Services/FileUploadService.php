<?php
namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileUploadService
{
    /**
     * Upload a course thumbnail
     */
    public function uploadCourseThumbnail(UploadedFile $file): string
    {
        $filename = $this->generateUniqueFilename($file, 'course_thumb_');
        
        Storage::disk('public')->putFileAs('course-thumbnails',$file, $filename);
        
        return $filename;
    }

    /**
     * Delete a course thumbnail
     */
    public function deleteCourseThumbnail(?string $filename): bool
    {
        if (!$filename) {
            return false;
        }

        return Storage::disk('public')->delete('course-thumbnails/' . $filename);
    }

    /**
     * Upload course content file
     */
    public function uploadCourseFile(UploadedFile $file, string $courseSlug): string
    {
        $directory = "courses/{$courseSlug}/files";
        $filename = $this->generateUniqueFilename($file, 'file_');
        
        Storage::disk('public')->putFileAs($directory, $file, $filename);
        
        return $filename;
    }

    /**
     * Generate a unique filename
     */
    private function generateUniqueFilename(UploadedFile $file, string $prefix = ''): string
    {
        $extension = $file->getClientOriginalExtension();
        $timestamp = time();
        $random = Str::random(8);
        
        return $prefix . $timestamp . '_' . $random . '.' . $extension;
    }

    /**
     * Get file size in human readable format
     */
    public function getHumanReadableSize(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $power = floor(log($bytes, 1024));
        
        return round($bytes / pow(1024, $power), 2) . ' ' . $units[$power];
    }

    /**
     * Validate image file
     */
    public function isValidImage(UploadedFile $file): bool
    {
        $allowedMimes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
        $maxSize = 2048 * 1024; // 2MB
        
        return in_array($file->getMimeType(), $allowedMimes) && $file->getSize() <= $maxSize;
    }
}