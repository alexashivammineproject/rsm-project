<?php

/**
 * Custom Helper Functions
 * 
 * These helpers provide workarounds for missing PHP extensions
 * and other utility functions.
 */

if (!function_exists('safe_storage_url')) {
    /**
     * Get storage URL without triggering fileinfo extension
     * 
     * @param string $path
     * @return string
     */
    function safe_storage_url($path)
    {
        if (empty($path)) {
            return '';
        }
        
        // Remove 'public/' prefix if exists
        $path = str_replace('public/', '', $path);
        
        // Return direct URL without using Storage facade
        return url('storage/' . $path);
    }
}

if (!function_exists('safe_storage_put_file')) {
    /**
     * Store uploaded file without triggering fileinfo extension
     * 
     * @param string $directory
     * @param \Illuminate\Http\UploadedFile $file
     * @return string|false
     */
    function safe_storage_put_file($directory, $file)
    {
        try {
            // Validate file
            if (!$file || !$file->isValid()) {
                \Log::error('safe_storage_put_file: Invalid file uploaded');
                return false;
            }
            
            // Generate unique filename
            $extension = $file->getClientOriginalExtension();
            $filename = uniqid() . '_' . time() . '.' . $extension;
            
            // Create full path
            $path = $directory . '/' . $filename;
            
            // Get storage path
            $storagePath = storage_path('app/public/' . $directory);
            
            // Create directory if it doesn't exist
            if (!is_dir($storagePath)) {
                if (!mkdir($storagePath, 0755, true)) {
                    \Log::error('safe_storage_put_file: Failed to create directory: ' . $storagePath);
                    return false;
                }
            }
            
            // Get the temporary file path
            $tmpPath = $file->getRealPath();
            $destinationPath = $storagePath . '/' . $filename;
            
            // Log for debugging
            \Log::info('safe_storage_put_file: Copying from ' . $tmpPath . ' to ' . $destinationPath);
            
            // Copy file directly without using Storage facade
            if (copy($tmpPath, $destinationPath)) {
                // Set proper permissions
                chmod($destinationPath, 0644);
                \Log::info('safe_storage_put_file: File uploaded successfully: ' . $path);
                return $path;
            } else {
                \Log::error('safe_storage_put_file: Failed to copy file');
                return false;
            }
        } catch (\Exception $e) {
            \Log::error('safe_storage_put_file error: ' . $e->getMessage());
            \Log::error('safe_storage_put_file trace: ' . $e->getTraceAsString());
            return false;
        }
    }
}

if (!function_exists('get_mime_type_by_extension')) {
    /**
     * Get MIME type by file extension without fileinfo
     * 
     * @param string $filename
     * @return string
     */
    function get_mime_type_by_extension($filename)
    {
        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        $mimeTypes = [
            // Images
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'webp' => 'image/webp',
            'svg' => 'image/svg+xml',
            'ico' => 'image/x-icon',
            
            // Documents
            'pdf' => 'application/pdf',
            'doc' => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'xls' => 'application/vnd.ms-excel',
            'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            
            // Archives
            'zip' => 'application/zip',
            'rar' => 'application/x-rar-compressed',
            
            // Text
            'txt' => 'text/plain',
            'csv' => 'text/csv',
            'html' => 'text/html',
            'css' => 'text/css',
            'js' => 'application/javascript',
            'json' => 'application/json',
            'xml' => 'application/xml',
        ];
        
        return $mimeTypes[$extension] ?? 'application/octet-stream';
    }
}
