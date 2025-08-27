<?php
namespace App\Services;

use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

class MediaService
{
    public function storeFile(UploadedFile $file, string $folder): string
    {
        try {
            $timestamp = now()->format('Ymd_His_u'); // Includes microseconds
            $random    = Str::random(8);             // Add extra randomness
            $extension = $file->getClientOriginalExtension();
            $fileName  = "{$timestamp}_{$random}.{$extension}";

            $path = $file->storeAs($folder, $fileName, 'public');

            if (! $path || ! Storage::disk('public')->exists($path)) {
                throw new Exception("File storage failed or file not found after save.");
            }

            return $path;
        } catch (Throwable $e) {
            Log::error('File storage error', [
                'error'  => $e->getMessage(),
                'file'   => $file->getClientOriginalName(),
                'folder' => $folder,
            ]);

            // You could throw a custom exception here instead if needed
            throw new Exception("Error storing file: " . $e->getMessage(), 0, $e);
        }
    }

    public function getImgContent(string $url): ?array
    {

        // dd($url);
        try {
            $response = Http::withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
            ])
                ->timeout(20)
                ->withOptions(['verify' => false]) // disable SSL verify if needed
                ->get($url);

            if (! $response->successful()) {
                throw new \Exception("Failed to fetch image. HTTP status: " . $response->status());
            }

            $mimeType = $response->header('Content-Type');
            $content  = $response->body();

            if (empty($content) || ! str_starts_with($mimeType, 'image/')) {
                throw new \Exception("Invalid image data or MIME type. Got: " . $mimeType);
            }

            return [
                'content'  => $content,
                'mimeType' => $mimeType,
            ];

        } catch (\Throwable $e) {
            Log::error('Failed to fetch image content', [
                'url'   => $url,
                'error' => $e->getMessage(),
            ]);

            return null; // don’t dd() in production
        }
    }

}
