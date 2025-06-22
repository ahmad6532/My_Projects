<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use InvalidArgumentException;

trait FileUploadTrait
{
    /**
     * Handle file upload, rename with timestamp, and return the storage path.
     *
     * @param \Illuminate\Http\UploadedFile $file The file to be uploaded.
     * @param string $directory The directory where the file should be stored.
     * @return string The path where the file is stored.
     * @throws \InvalidArgumentException If the provided file is not an instance of UploadedFile.
     */
    public function handleFileUpload(UploadedFile $file, $directory = 'images')
    {
        // Ensure the provided file is an instance of UploadedFile
        if (!$file instanceof UploadedFile) {
            throw new InvalidArgumentException('Provided file must be an instance of UploadedFile.');
        }

        // Get the original file name with extension
        $originalName = $file->getClientOriginalName();
        
        // Get the file extension
        $extension = $file->getClientOriginalExtension();
        
        // Generate a new file name with timestamp in milliseconds
        $timestamp = round(microtime(true) * 1000);
        $newFileName = pathinfo($originalName, PATHINFO_FILENAME) . '_' . $timestamp . '.' . $extension;
        
        // Store the file in the specified directory with the new file name
        $path = $file->storeAs($directory, $newFileName, 'public');
        
        // Return the path where the file is stored
        return $path;
    }
}
