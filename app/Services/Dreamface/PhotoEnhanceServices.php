<?php
namespace App\Services\Dreamface;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Throwable;

class PhotoEnhanceServices
{

    public function uploadPhoto(string $storagePath, string $userId): array
    {
        $uploadUrl = "https://tools.dreamfaceapp.com/dw-server/phone_file/upload_uss3_server/WEB_ANIMATE_MATERIAL";

        try {
            // Full absolute path to file
            $fullPath = Storage::disk('public')->path($storagePath);

            if (! file_exists($fullPath)) {
                return [
                    'status'  => 'error',
                    'message' => 'File not found: ' . $storagePath,
                ];
            }

            $filename     = basename($fullPath);
            $fileContents = file_get_contents($fullPath);

            // Send form-data request
            $response = Http::timeout(300)
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

    public function submitForPhotoEnhance(string $imageUrl, string $userId, string $accountId): array
    {
        $submitUrl = "https://tools.dreamfaceapp.com/dw-server/face/animate_image";

        $payload = [
            "user_id"         => $userId,
            "account_id"      => $accountId,
            "photo_info_list" => [
                [
                    "photo_path" => $imageUrl,
                ],
            ],
            "play_types"      => [
                "ENHANCE",
            ],
            "ext"             => [
                "sing_title" => "Photo Enhance",
            ],
            "work_type"       => "PHOTO_ENHANCER",
            "template_id"     => "6673981149d3810007f3c6ce",
            "app_version"     => "4.7.1",
            "platform_type"   => "WEB",
        ];

        try {
            $response = Http::timeout(300)
                ->withOptions(['verify' => false])
                ->withHeaders(['Content-Type' => 'application/json'])
                ->post($submitUrl, $payload);

            $result = $response->json();

            if (
                $response->successful() &&
                isset($result['data']['animate_image_id'])
            ) {
                return [
                    'status'       => 'success',
                    'animate_id'   => $result['data']['animate_image_id'],
                    'raw_response' => $result,
                ];
            }

            return [
                'status'       => 'error',
                'message'      => $result['status_msg'] ?? 'Failed to get animate_id',
                'raw_response' => $result,
            ];

        } catch (Throwable $e) {
            Log::error('Photo Enhance submission failed', [
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

    public function pollResultImage(string $userId, string $accountId, string $animateId): array
    {
        $pollUrl = "https://tools.dreamfaceapp.com/dw-server/face/animate_image_list_poll";

        $payload = [
            "user_id"         => $userId,
            "account_id"      => $accountId,
            "animate_id_list" => [$animateId],
            // "type" => $type
        ];

        $maxAttempts  = 10;
        $delaySeconds = 6;

        try {
            for ($attempt = 0; $attempt < $maxAttempts; $attempt++) {
                sleep($delaySeconds);

                $response = Http::timeout(300)
                    ->withOptions(['verify' => false])
                    ->withHeaders(['Content-Type' => 'application/json'])
                    ->post($pollUrl, $payload);

                $result = $response->json();

                if (
                    $response->successful()
                    && ! empty($result['data']['animate_image_list'][0]['process'] == 100)
                ) {
                    return [
                        'status'            => 'success',
                        'picture_path_list' => $result['data']['animate_image_list'][0]['common_list'][0]['picture_path_list'][0],
                        'attempts'          => $attempt + 1,
                    ];
                }
            }

            return [
                'status'  => 'error',
                'message' => 'result image not ready after 45 seconds.',
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
