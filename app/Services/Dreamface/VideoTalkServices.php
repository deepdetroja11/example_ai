<?php

namespace App\Services\Dreamface;
use Illuminate\Http\Client\RequestException;
use Throwable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class VideoTalkServices
{
    public function uploadMediaFromStoragePath(string $storagePath, string $userId): array
    {
        $uploadUrl = "https://tools.dreamfaceapp.com/dw-server/phone_file/upload_uss3_server/WEB_ANIMATE_MATERIAL";

        try {
            // Full absolute path to file
            $fullPath = Storage::disk('public')->path($storagePath);

            if (!file_exists($fullPath)) {
                return [
                    'status' => 'error',
                    'message' => 'File not found in storage: ' . $storagePath,
                ];
            }

            $filename = basename($fullPath);
            $fileContents = file_get_contents($fullPath);

            // Upload to Dreamface
            $response = Http::timeout(90)->attach(
                'file',
                $fileContents,
                $filename
            )->post($uploadUrl, [
                        'user_id' => $userId,
                    ]);

            $result = $response->json();

            if (
                $response->successful() &&
                ($result['status_msg'] ?? '') === 'Success' &&
                !empty($result['data']['file_path'])
            ) {
                return [
                    'status' => 'success',
                    'file_url' => $result['data']['file_path'],
                ];
            }

            return [
                'status' => 'error',
                'message' => $result['status_msg'] ?? 'Unknown error',
            ];

        } catch (Throwable $e) {
            Log::critical('Unexpected error in uploadMediaFromStoragePath()', ['error' => $e->getMessage()]);

            return [
                'status' => 'error',
                'message' => 'Unexpected error: ' . $e->getMessage(),
            ];
        }
    }


    public function addPhotoForGetAvatarID($fileUrl, $accountId, $userId): array
    {
        $avatarAddUrl = "https://tools.dreamfaceapp.com/df-server/avatar/add";

        $avatarData = [
            'user_id' => $userId,
            'account_id' => $accountId,
            'url' => $fileUrl,
            'type' => 'IMAGE',
            'support_multi_face' => 'false',
        ];

        try {
            $response = Http::timeout(90)->asForm()->post($avatarAddUrl, $avatarData);
            $result = $response->json();

            // dd($result);
            if ($response->successful() && ($result['status_msg'] ?? '') === 'Success') {
                return [
                    'status' => 'success',
                    'message' => 'Avatar created successfully.',
                ];
            }

            return [
                'status' => 'error',
                'message' => $result['status_msg'] ?? 'Unknown error',
            ];
        } catch (Throwable $e) {
            Log::critical('Unexpected error in addPhotoForGetAvatarID()', ['error' => $e->getMessage()]);
            return [
                'status' => 'error',
                'message' => 'Unexpected error: ' . $e->getMessage(),
            ];
        }
    }


    public function getAvatarIdByFileUrl($userId, $fileUrl): array
    {
        $listUrl = "https://tools.dreamfaceapp.com/df-server/avatar/list";

        try {
            $response = Http::timeout(150)->withHeaders([
                'Content-Type' => 'application/json',
            ])->post($listUrl, ['user_id' => $userId]);

            $data = $response->json();

            if (isset($data['avatars']) && is_array($data['avatars'])) {
                foreach ($data['avatars'] as $avatar) {
                    if (($avatar['path'] ?? '') === $fileUrl) {
                        return [
                            'status' => 'success',
                            'avatar_id' => $avatar['id'],
                        ];
                    }
                }
            }

            return [
                'status' => 'error',
                'message' => 'No matching avatar found.',
            ];
        } catch (Throwable $e) {
            Log::critical('Unexpected error in getAvatarIdByFileUrl()', ['error' => $e->getMessage()]);
            return [
                'status' => 'error',
                'message' => 'Unexpected error: ' . $e->getMessage(),
            ];
        }
    }

    public function uploadAudio(string $audioPath, string $userId): array
    {
        $audioUploadUrl = "https://tools.dreamfaceapp.com/dw-server/phone_file/upload_audio_with_dir";


        try {
            $audioPath = Storage::disk('public')->path($audioPath);

            if (!file_exists($audioPath)) {
                return [
                    'status' => 'error',
                    'message' => 'Audio file not found: ' . $audioPath,
                ];
            }

            $response = Http::timeout(90)
                ->attach('file', file_get_contents($audioPath), basename($audioPath))->asMultipart()->post($audioUploadUrl, [
                        ['name' => 'userId', 'contents' => $userId],
                        ['name' => 'ossDir', 'contents' => 'AVATAR_AUDIO'],
                    ]);

            $audioResult = $response->json();

            if ($response->successful() && ($audioResult['status_msg'] ?? '') === 'Success' && !empty($audioResult['data']['file_path'])) {
                return [
                    'status' => 'success',
                    'file_path' => $audioResult['data']['file_path'],
                ];
            }

            return [
                'status' => 'error',
                'message' => $audioResult['status_msg'] ?? 'Unknown upload error',
            ];
        }  catch (Throwable $e) {
            Log::critical('Unexpected error in uploadAudio()', ['error' => $e->getMessage()]);
            return [
                'status' => 'error',
                'message' => 'Unexpected error: ' . $e->getMessage(),
            ];
        }
    }


   public function getAudioDuration(string $audioPath): array
{
    try {
        $audioPath = Storage::disk('public')->path($audioPath);

        if (!file_exists($audioPath)) {
            return [
                'status' => 'error',
                'message' => 'Audio file does not exist.',
            ];
        }

        $ffmpegPath = 'C:\\ffmpeg\\bin\\ffmpeg.exe';

        if (!file_exists($ffmpegPath)) {
            return [
                'status' => 'error',
                'message' => 'FFmpeg not found at specified path.',
            ];
        }

        $command = "\"$ffmpegPath\" -i \"$audioPath\" 2>&1";
        $output = shell_exec($command);

        if (preg_match('/Duration: (\d+):(\d+):(\d+\.\d+)/', $output, $matches)) {
            $hours = (int)$matches[1];
            $minutes = (int)$matches[2];
            $seconds = (float)$matches[3];

            $totalMilliseconds = ($hours * 3600 + $minutes * 60 + $seconds) * 1000;

            if ($totalMilliseconds <= 2500) {
                return [
                    'status' => 'duration_error',
                    'message' => 'Audio Duration is < 2.5 seconds.',
                ];
            } elseif ($totalMilliseconds >= 180000) {
                return [
                    'status' => 'duration_error',
                    'message' => 'Audio Duration is > 180 seconds.',
                ];
            }

            return [
                'status' => 'success',
                'duration_ms' => (int) $totalMilliseconds,
                'message' => 'Duration fetched successfully.',
            ];
        } else {
            return [
                'status' => 'error',
                'message' => 'Unable to parse audio duration.',
            ];
        }

    } catch (Throwable $e) {
        return [
            'status' => 'error',
            'message' => 'Exception: ' . $e->getMessage(),
        ];
    }
}


    public function callAnimateApi(string $audioUrl, $duration_ms, string $fileUrl, string $avatarId, string $userId, string $accountId): array
    {
        $animateApiUrl = "https://tools.dreamfaceapp.com/dw-server/face/animate_image_web";

        $animatePayload = [
            "aigc_img_no_save_flag" => false,
            "template_id" => "655b213cccd1db0007e1d977",
            "app_version" => "4.7.1",
            "timestamp" => round(microtime(true) * 1000),
            "user_id" => $userId,
            "no_water_mark" => 1,
            "merge_by_server" => false,
            "account_id" => $accountId,
            "pt_infos" => [
                [
                    "audio_url" => $audioUrl,
                    "original_video_url" => "",
                    "file_name" => basename($audioUrl),
                    "asset_id" => "",
                    "audio_start_time" => 0,
                    "audio_end_time" => $duration_ms
                ]
            ],
            "work_type" => "AVATAR_VIDEO",
            "santa_info" => [
                "email" => "",
                "signature" => ""
            ],
            "photo_info_list" => [
                [
                    "photo_path" => $fileUrl,
                    "mask_path" => "",
                    "avatar_id" => $avatarId,
                    "resolution" => 720,
                    "is_default_avatar" => false
                ]
            ],
            "play_types" => ["PT"],
            "ext" => [
                "track_info" => "{}",
                "sing_title" => basename($audioUrl),
                "animate_channel" => "phototalk"
            ]
        ];

        try {
            $response = Http::timeout(120)->withHeaders([
                'Content-Type' => 'application/json',
            ])->post($animateApiUrl, $animatePayload);

            if ($response->successful()) {
                $result = $response->json();

                if (
                    isset($result['status_msg']) &&
                    $result['status_msg'] === 'Success' &&
                    !empty($result['data']['animate_image_id'])
                ) {
                    return [
                        'status' => 'success',
                        'animate_image_id' => $result['data']['animate_image_id']
                    ];
                }

                return [
                    'status' => 'error',
                    'message' => $result['status_msg'] ?? 'Unknown API error'
                ];
            } else {
                Log::error('Animate API failed', ['response' => $response->body()]);
                return ['status' => 'error', 'message' => 'HTTP Error: ' . $response->status()];
            }
        } catch (Throwable $e) {
            Log::critical('Exception in animate API', ['error' => $e->getMessage()]);
            return ['status' => 'error', 'message' => 'Exception: ' . $e->getMessage()];
        }
    }


    public function checkVideoStatus(string $animateImageId, string $userId, string $accountId): array
    {
        $checkStatusUrl = "https://tools.dreamfaceapp.com/dw-server/work/v2/get_recent_creation_list";

        try {
            $response = Http::timeout(120)->withHeaders([
                'Content-Type' => 'application/json',
            ])->post($checkStatusUrl, [
                        "user_id" => $userId,
                        "account_id" => $accountId,
                        "page" => 1,
                        "size" => 150,
                        "creation_types" => ["CREATION_AVATAR_VIDEO"],
                        "app_version" => "4.7.1"
                    ]);

            if (!$response->successful()) {
                return [
                    'status' => 'error',
                    'message' => 'HTTP error',
                    'http_status' => $response->status(),
                    'body' => $response->body(),
                ];
            }

            $statusData = $response->json();

            if (isset($statusData['data']['list']) && is_array($statusData['data']['list'])) {
                foreach ($statusData['data']['list'] as $work) {
                    if (!empty($work['animate_id']) && $work['animate_id'] === $animateImageId) {
                        return [
                            'status' => 'success',
                            'video_id' => $work['id'] ?? null,
                            'web_work_status' => $work['web_work_status'] ?? null,
                            'raw' => $work
                        ];

                    }
                }

                return [
                    'status' => 'not_found',
                    'message' => "animate_id $animateImageId not found in recent creation list"
                ];
            }

            return [
                'status' => 'error',
                'message' => 'Invalid response format',
                // 'response' => $statusData,
            ];

        } catch (Throwable $e) {
            Log::error('Error while checking video status', [
                'exception' => $e->getMessage(),
            ]);

            return [
                'status' => 'error',
                'message' => 'Exception occurred: ' . $e->getMessage(),
            ];
        }
    }


    public function getVideoURL(string $workId): array
    {
        $uploadEndpoint = "https://tools.dreamfaceapp.com/df-server/work_v4/get_work_download_url";

        $payload = [
            'accelerate' => false,
            'work_id' => $workId,
        ];

        try {
            $response = Http::timeout(90)->asJson()->post($uploadEndpoint, $payload);
            $result = $response->json();

            if ($response->successful() && isset($result['status_msg']) && $result['status_msg'] === 'Success' && isset($result['data']) && !empty($result['data'])) {
                return [
                    'status' => 'success',
                    'message' => 'Image uploaded and processed successfully.',
                    'data' => $result['data'],
                ];
            }

            return [
                'status' => 'error',
                'message' => $result['status_msg'] ?? 'API did not return success.',
                'data' => $result ?? [],
            ];
        } catch (Throwable $e) {
            Log::critical('Unexpected error during image upload process', [
                'work_id' => $workId,
                'error' => $e->getMessage(),
            ]);
            return [
                'status' => 'error',
                'message' => 'Unexpected error: ' . $e->getMessage(),
            ];
        }
    }


    public function deleteVideoUrl($videoId, $userId, $accountId)
    {

        try {

            // Ensure work_ids is always a flat array
            $workIds = is_array($videoId) ? $videoId : [$videoId];

            $response = Http::timeout(90)->withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])->post('https://tools.dreamfaceapp.com/df-server/work_v3/delete_work_batch', [
                        'user_id' => $userId,
                        'account_id' => $accountId,
                        'work_ids' => $workIds
                    ]);

            $result = $response->json();

            if ($response->successful() && isset($result['status_msg']) && $result['status_msg'] === 'Success') {
                return [
                    'status' => 'success',
                ];
            }

            return [
                'status' => 'error',
                'message' => 'API did not return success.',
            ];

        }catch (Throwable $e) {
            Log::critical('Unexpected error delete Video Url', [
                'videoId' => $videoId,
                'error' => $e->getMessage(),
            ]);
            return [
                'status' => 'error',
                'message' => 'Unexpected error: ' . $e->getMessage(),
            ];
        }
    }

    public function deletePhotoUrl($avatarId)
    {

        try {

            // Ensure work_ids is always a flat array
            $workIds = is_array($avatarId) ? $avatarId : [$avatarId];

            $response = Http::timeout(60)->withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])->post('https://tools.dreamfaceapp.com/df-server/avatar/delete', [
                        'id' => $avatarId,
                    ]);

            $result = $response->json();

            if ($response->successful() && isset($result['status_msg']) && $result['status_msg'] === 'Success') {
                return [
                    'status' => 'success',
                ];
            }

            return [
                'status' => 'error',
                'message' => 'API did not return success.',
            ];

        }catch (Throwable $e) {
            Log::critical('Unexpected error delete photo Url', [
                'avatar_id' => $avatarId,
                'error' => $e->getMessage(),
            ]);
            return [
                'status' => 'error',
                'message' => 'Unexpected error: ' . $e->getMessage(),
            ];
        }
    }




}