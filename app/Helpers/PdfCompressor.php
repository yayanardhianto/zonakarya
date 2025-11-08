<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PdfCompressor
{
    /**
     * Compress PDF file using Ghostscript if available
     * 
     * @param string $filePath Full path to the PDF file
     * @return bool True if compression was successful or skipped, false on error
     */
    public static function compress($filePath)
    {
        // Check if file exists
        if (!file_exists($filePath)) {
            Log::warning('PDF Compressor: File not found', ['path' => $filePath]);
            return false;
        }

        // Check if file is PDF
        $mimeType = mime_content_type($filePath);
        if ($mimeType !== 'application/pdf') {
            Log::info('PDF Compressor: File is not PDF, skipping compression', [
                'path' => $filePath,
                'mime_type' => $mimeType
            ]);
            return true; // Not an error, just skip
        }

        // Check if Ghostscript is available
        $gsCommand = self::findGhostscript();
        if (!$gsCommand) {
            Log::info('PDF Compressor: Ghostscript not found, skipping compression', [
                'path' => $filePath
            ]);
            return true; // Not an error, just skip compression
        }

        $originalSize = filesize($filePath);
        $tempPath = $filePath . '.compressed';

        try {
            // Compress PDF using Ghostscript
            // -dNOPAUSE: don't pause between pages
            // -dBATCH: exit after processing
            // -sDEVICE=pdfwrite: output device
            // -dCompatibilityLevel=1.4: PDF version
            // -dPDFSETTINGS=/ebook: medium quality, good compression
            // -dNOPAUSE -dBATCH: non-interactive mode
            // -q: quiet mode
            $command = sprintf(
                '%s -dNOPAUSE -dBATCH -sDEVICE=pdfwrite -dCompatibilityLevel=1.4 -dPDFSETTINGS=/ebook -dNOPAUSE -dBATCH -q -sOutputFile=%s %s',
                escapeshellarg($gsCommand),
                escapeshellarg($tempPath),
                escapeshellarg($filePath)
            );

            $output = [];
            $returnVar = 0;
            exec($command . ' 2>&1', $output, $returnVar);

            if ($returnVar !== 0) {
                Log::warning('PDF Compressor: Ghostscript compression failed', [
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
                        
                        Log::info('PDF Compressor: Compression successful', [
                            'path' => $filePath,
                            'original_size' => $originalSize,
                            'compressed_size' => $compressedSize,
                            'savings' => $savings,
                            'savings_percent' => round($savingsPercent, 2) . '%'
                        ]);
                        return true;
                    } else {
                        Log::warning('PDF Compressor: Failed to replace original file', [
                            'path' => $filePath
                        ]);
                        @unlink($tempPath);
                        return true; // Continue with original file
                    }
                } else {
                    // Compressed file is larger, keep original
                    Log::info('PDF Compressor: Compressed file is larger, keeping original', [
                        'path' => $filePath,
                        'original_size' => $originalSize,
                        'compressed_size' => $compressedSize
                    ]);
                    @unlink($tempPath);
                    return true;
                }
            } else {
                Log::warning('PDF Compressor: Compressed file not created', [
                    'path' => $filePath
                ]);
                return true; // Continue with original file
            }
        } catch (\Exception $e) {
            Log::error('PDF Compressor: Exception during compression', [
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
     * Find Ghostscript executable
     * 
     * @return string|null Path to Ghostscript executable or null if not found
     */
    private static function findGhostscript()
    {
        $possiblePaths = [
            'gs',
            '/usr/bin/gs',
            '/usr/local/bin/gs',
            '/opt/homebrew/bin/gs', // macOS Homebrew
            'C:\Program Files\gs\bin\gswin64c.exe', // Windows 64-bit
            'C:\Program Files (x86)\gs\bin\gswin32c.exe', // Windows 32-bit
        ];

        foreach ($possiblePaths as $path) {
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
            return file_exists(trim($output[0])) && is_executable(trim($output[0]));
        }

        // Try direct execution (Windows)
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            exec(sprintf('%s -v 2>&1', escapeshellarg($command)), $output, $returnVar);
            return $returnVar === 0;
        }

        return false;
    }
}

