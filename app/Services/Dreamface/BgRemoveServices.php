<?php
namespace App\Services\Dreamface;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Throwable;

class BgRemoveServices
{

    public function uploadPhoto(string $storagePath, string $userId): array
    {
        $uploadUrl = "https://tools.dreamfaceapp.com/dw-server/phone_file/upload_uss3_server/WEB_ANIMATE_MATERIAL";
        try {
            $fullPath = Storage::disk('public')->path($storagePath);
            if (! file_exists($fullPath)) {
                return [
                    'status'  => 'error',
                    'message' => 'File not found: ' . $storagePath,
                ];
            }

            $filename     = basename($fullPath);
            $fileContents = file_get_contents($fullPath);

            $response = Http::timeout(90)
                ->withOptions(['verify' => false])
                ->attach('file', $fileContents, $filename)
                ->post($uploadUrl, [
                    'user_id' => $userId,
                ]);

            $result = $response->json();

            if (
                $response->successful() &&
                ($result['status_msg'] ?? '') === 'Success' &&
                ! empty($result['data']['file_path'])
            ) {
                return [
                    'status'   => 'success',
                    'file_url' => $result['data']['file_path'],
                ];
            }

            return [
                'status'  => 'error',
                'message' => $result['status_msg'] ?? 'Unknown error from API',
            ];

        } catch (Throwable $e) {
            Log::error('Image upload failed', [
                'user_id' => $userId,
                'error'   => $e->getMessage(),
            ]);

            return [
                'status'  => 'error',
                'message' => 'Exception: ' . $e->getMessage(),
            ];
        }
    }

    public function submitForBackgroundRemoval(string $imageUrl, string $userId, string $accountId): array
    {
        $submitUrl = "https://tools.dreamfaceapp.com/dw-server/matting/submit";

        $payload = [
            "user_id"        => $userId,
            "type"           => 2,
            "bg_img_info"    => ["img_url" => $imageUrl],
            "human_img_info" => ["img_url" => $imageUrl],
            "account_id"     => $accountId,
            "platform_type"  => "WEB",
        ];

        try {
            // Log::info('Submitting for background removal', [
            //     'url'     => $submitUrl,
            //     'payload' => $payload,
            // ]);

            $response = Http::timeout(60)
                ->withOptions(['verify' => false])
                ->withHeaders(['Content-Type' => 'application/json'])
                ->post($submitUrl, $payload);

            // Log::info('Submit response raw', [
            //     'status' => $response->status(),
            //     'body'   => $response->body(),
            // ]);

            $result = $response->json();

            if (
                $response->successful() &&
                isset($result['data']['animate_id'])
            ) {
                return [
                    'status'       => 'success',
                    'animate_id'   => $result['data']['animate_id'],
                    'raw_response' => $result,
                ];
            }

            return [
                'status'       => 'error',
                'message'      => $result['status_msg'] ?? 'Failed to get animate_id',
                'raw_response' => $result,
            ];

        } catch (Throwable $e) {
            Log::error('Background removal submission failed', [
                'user_id'   => $userId,
                'image_url' => $imageUrl,
                'error'     => $e->getMessage(),
            ]);

            return [
                'status'  => 'error',
                'message' => 'Exception occurred: ' . $e->getMessage(),
            ];
        }
    }

    public function pollForBGRImage(string $userId, string $accountId, string $animateId, int $type = 2): array
    {
        $pollUrl = "https://tools.dreamfaceapp.com/dw-server/matting/animate_poll";

        $payload = [
            "user_id"         => $userId,
            "account_id"      => $accountId,
            "poll_animate_id" => $animateId,
            "type"            => $type,
        ];

        $maxAttempts  = 10;
        $delaySeconds = 6;

        try {
            for ($attempt = 0; $attempt < $maxAttempts; $attempt++) {
                sleep($delaySeconds);
                // Log::info("Polling attempt #" . ($attempt + 1), [
                //     'url'     => $pollUrl,
                //     'payload' => $payload,
                // ]);

                $response = Http::timeout(60)
                    ->withOptions(['verify' => false])
                    ->withHeaders(['Content-Type' => 'application/json'])
                    ->post($pollUrl, $payload);

                // Log::info('Poll response raw', [
                //     'status' => $response->status(),
                //     'body'   => $response->body(),
                // ]);

                $result = $response->json();

                if ($response->successful() && ! empty($result['data']['matting_img_url'])) {
                    return [
                        'status'          => 'success',
                        'matting_img_url' => $result['data']['matting_img_url'],
                        'attempts'        => $attempt + 1,
                    ];
                }
            }

            return [
                'status'  => 'error',
                'message' => 'Matting image not ready after 45 seconds.',
            ];

        } catch (Throwable $e) {
            Log::error('Polling for matting image failed', [
                'user_id'    => $userId,
                'animate_id' => $animateId,
                'error'      => $e->getMessage(),
            ]);

            return [
                'status'  => 'error',
                'message' => 'Polling exception: ' . $e->getMessage(),
            ];
        }
    }

}
