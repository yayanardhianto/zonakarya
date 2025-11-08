<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;

class ImageCompressor
{
    /**
     * Compress image file using ImageMagick if available
     * 
     * @param string $filePath Full path to the image file
     * @param int $quality Quality level (1-100, default: 85)
     * @return bool True if compression was successful or skipped, false on error
     */
    public static function compress($filePath, $quality = 85)
    {
        // Check if file exists
        if (!file_exists($filePath)) {
            Log::warning('Image Compressor: File not found', ['path' => $filePath]);
            return false;
        }

        // Check if file is an image
        $mimeType = mime_content_type($filePath);
        $imageMimeTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
        
        if (!in_array($mimeType, $imageMimeTypes)) {
            Log::info('Image Compressor: File is not an image, skipping compression', [
                'path' => $filePath,
                'mime_type' => $mimeType
            ]);
            return true; // Not an error, just skip
        }

        // Check if ImageMagick is available
        $convertCommand = self::findImageMagick();
        if (!$convertCommand) {
            Log::info('Image Compressor: ImageMagick not found, skipping compression', [
                'path' => $filePath
            ]);
            return true; // Not an error, just skip compression
        }

        $originalSize = filesize($filePath);
        $tempPath = $filePath . '.compressed';
        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

        try {
            // Determine compression options based on image type
            $compressionOptions = self::getCompressionOptions($extension, $quality);

            // Compress image using ImageMagick convert
            // -strip: remove all profiles and comments
            // -quality: set compression quality
            // -interlace Plane: progressive JPEG
            // -sampling-factor: chroma subsampling for JPEG
            $command = sprintf(
                '%s %s %s %s 2>&1',
                escapeshellarg($convertCommand),
                escapeshellarg($filePath),
                $compressionOptions,
                escapeshellarg($tempPath)
            );

            $output = [];
            $returnVar = 0;
            exec($command, $output, $returnVar);

            if ($returnVar !== 0) {
                Log::warning('Image Compressor: ImageMagick compression failed', [
                    'path' => $filePath,
                    'return_code' => $returnVar,
                    'output' => implode("\n", $output)
                ]);
                // Clean up temp file if exists
                if (file_exists($tempPath)) {
                    @unlink($tempPath);
                }
                return true; // Continue with original file
            }

            // Check if compressed file exists and is smaller
            if (file_exists($tempPath)) {
                $compressedSize = filesize($tempPath);
                
                // Only use compressed version if it's actually smaller
                if ($compressedSize < $originalSize) {
                    // Replace original with compressed
                    if (rename($tempPath, $filePath)) {
                        $savings = $originalSize - $compressedSize;
                        $savingsPercent = ($savings / $originalSize) * 100;
                        
                        Log::info('Image Compressor: Compression successful', [
                            'path' => $filePath,
                            'original_size' => $originalSize,
                            'compressed_size' => $compressedSize,
                            'savings' => $savings,
                            'savings_percent' => round($savingsPercent, 2) . '%',
                            'quality' => $quality
                        ]);
                        return true;
                    } else {
                        Log::warning('Image Compressor: Failed to replace original file', [
                            'path' => $filePath
                        ]);
                        @unlink($tempPath);
                        return true; // Continue with original file
                    }
                } else {
                    // Compressed file is larger, keep original
                    Log::info('Image Compressor: Compressed file is larger, keeping original', [
                        'path' => $filePath,
                        'original_size' => $originalSize,
                        'compressed_size' => $compressedSize
                    ]);
                    @unlink($tempPath);
                    return true;
                }
            } else {
                Log::warning('Image Compressor: Compressed file not created', [
                    'path' => $filePath
                ]);
                return true; // Continue with original file
            }
        } catch (\Exception $e) {
            Log::error('Image Compressor: Exception during compression', [
                'path' => $filePath,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            // Clean up temp file if exists
            if (file_exists($tempPath)) {
                @unlink($tempPath);
            }
            return true; // Continue with original file on error
        }
    }

    /**
     * Get compression options based on image type
     * 
     * @param string $extension Image file extension
     * @param int $quality Quality level (1-100)
     * @return string ImageMagick options
     */
    private static function getCompressionOptions($extension, $quality)
    {
        $options = '-strip'; // Remove all profiles and comments
        
        switch (strtolower($extension)) {
            case 'jpg':
            case 'jpeg':
                // JPEG compression options
                $options .= ' -quality ' . $quality;
                $options .= ' -interlace Plane'; // Progressive JPEG
                $options .= ' -sampling-factor 4:2:0'; // Chroma subsampling
                break;
                
            case 'png':
                // PNG compression options
                $options .= ' -quality ' . $quality;
                // PNG doesn't use quality the same way, but we'll use it anyway
                // For better PNG compression, we could use pngquant separately
                break;
                
            case 'gif':
                // GIF compression options
                $options .= ' -quality ' . $quality;
                break;
                
            case 'webp':
                // WebP compression options
                $options .= ' -quality ' . $quality;
                break;
                
            default:
                // Default compression
                $options .= ' -quality ' . $quality;
                break;
        }
        
        return $options;
    }

    /**
     * Find ImageMagick convert executable
     * 
     * @return string|null Path to ImageMagick convert executable or null if not found
     */
    private static function findImageMagick()
    {
        $possiblePaths = [
            'convert',
            '/usr/bin/convert',
            '/usr/local/bin/convert',
            '/opt/homebrew/bin/convert', // macOS Homebrew
            'C:\Program Files\ImageMagick-*\convert.exe', // Windows
            'C:\ImageMagick\convert.exe', // Windows alternative
        ];

        foreach ($possiblePaths as $path) {
            // Handle wildcards in Windows paths
            if (strpos($path, '*') !== false && strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                $globPaths = glob($path);
                if (!empty($globPaths)) {
                    $path = $globPaths[0];
                } else {
                    continue;
                }
            }
            
            if (self::isExecutable($path)) {
                return $path;
            }
        }

        return null;
    }

    /**
     * Check if a command is executable
     * 
     * @param string $command
     * @return bool
     */
    private static function isExecutable($command)
    {
        // Check if it's a full path and file exists
        if (strpos($command, '/') !== false || strpos($command, '\\') !== false) {
            return file_exists($command) && is_executable($command);
        }

        // Check if command is available in PATH
        $output = [];
        $returnVar = 0;
        exec(sprintf('which %s 2>&1', escapeshellarg($command)), $output, $returnVar);
        
        if ($returnVar === 0 && !empty($output)) {
            $foundPath = trim($output[0]);
            return file_exists($foundPath) && is_executable($foundPath);
        }

        // Try direct execution (Windows)
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            exec(sprintf('%s -version 2>&1', escapeshellarg($command)), $output, $returnVar);
            return $returnVar === 0;
        }

        // Try direct execution (Unix-like)
        exec(sprintf('%s -version 2>&1', escapeshellarg($command)), $output, $returnVar);
        return $returnVar === 0;
    }

    /**
     * Resize image if it exceeds maximum dimensions
     * 
     * @param string $filePath Full path to the image file
     * @param int $maxWidth Maximum width in pixels (default: 1920)
     * @param int $maxHeight Maximum height in pixels (default: 1920)
     * @return bool True if resize was successful or skipped
     */
    public static function resizeIfNeeded($filePath, $maxWidth = 1920, $maxHeight = 1920)
    {
        // Check if file exists
        if (!file_exists($filePath)) {
            return false;
        }

        // Get image dimensions
        $imageInfo = @getimagesize($filePath);
        if (!$imageInfo) {
            return false;
        }

        $currentWidth = $imageInfo[0];
        $currentHeight = $imageInfo[1];

        // Check if resize is needed
        if ($currentWidth <= $maxWidth && $currentHeight <= $maxHeight) {
            return true; // No resize needed
        }

        // Calculate new dimensions maintaining aspect ratio
        $ratio = min($maxWidth / $currentWidth, $maxHeight / $currentHeight);
        $newWidth = (int)($currentWidth * $ratio);
        $newHeight = (int)($currentHeight * $ratio);

        // Check if ImageMagick is available
        $convertCommand = self::findImageMagick();
        if (!$convertCommand) {
            Log::info('Image Compressor: ImageMagick not found for resize, skipping', [
                'path' => $filePath
            ]);
            return true; // Not an error, just skip
        }

        $tempPath = $filePath . '.resized';

        try {
            // Resize image using ImageMagick
            $command = sprintf(
                '%s %s -resize %dx%d> %s 2>&1',
                escapeshellarg($convertCommand),
                escapeshellarg($filePath),
                $newWidth,
                $newHeight,
                escapeshellarg($tempPath)
            );

            $output = [];
            $returnVar = 0;
            exec($command, $output, $returnVar);

            if ($returnVar !== 0) {
                Log::warning('Image Compressor: ImageMagick resize failed', [
                    'path' => $filePath,
                    'return_code' => $returnVar,
                    'output' => implode("\n", $output)
                ]);
                if (file_exists($tempPath)) {
                    @unlink($tempPath);
                }
                return true; // Continue with original file
            }

            if (file_exists($tempPath)) {
                if (rename($tempPath, $filePath)) {
                    Log::info('Image Compressor: Resize successful', [
                        'path' => $filePath,
                        'original_dimensions' => "{$currentWidth}x{$currentHeight}",
                        'new_dimensions' => "{$newWidth}x{$newHeight}"
                    ]);
                    return true;
                } else {
                    @unlink($tempPath);
                    return true;
                }
            }

            return true;
        } catch (\Exception $e) {
            Log::error('Image Compressor: Exception during resize', [
                'path' => $filePath,
                'error' => $e->getMessage()
            ]);
            if (file_exists($tempPath)) {
                @unlink($tempPath);
            }
            return true;
        }
    }
}

