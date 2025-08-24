<?php

namespace App\Http\Controllers\Dreamface;

use App\Http\Controllers\Controller;
use App\Services\MediaService;
use App\Services\Dreamface\VideoTalkServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Models\videoTalk;
use Carbon\Carbon;



class VideoTalkController extends Controller
{
    protected VideoTalkServices $videoTalkServices;
    protected MediaService $mediaService;

    public function __construct(
        VideoTalkServices $videoTalkServices,
        MediaService $mediaService
    ) {
        $this->videoTalkServices = $videoTalkServices;
        $this->mediaService = $mediaService;
    }

   public function storeImgAduio(Request $request)
{
    $request->validate([
        'image' => 'required|image|max:2048',
        'audio' => 'required|mimes:mp3,wav,aac|max:10000'
    ]);

    try {
        if (!$request->hasFile('image') || !$request->hasFile('audio')) {
            return response()->json(['error' => 'Both image and audio files are required.'], 400);
        }

        // Upload image
        $imagePath = $this->mediaService->storeFile($request->file('image'), 'uploads/images');

        try {
            // Upload audio
            $audioPath = $this->mediaService->storeFile($request->file('audio'), 'uploads/audio');

            // Get audio duration
            $durationInfo = $this->videoTalkServices->getAudioDuration($audioPath);

            if ($durationInfo['status'] === 'error' || $durationInfo['status'] === 'duration_error') {
                // Cleanup both files
                if (Storage::disk('public')->exists($imagePath)) {
                    Storage::disk('public')->delete($imagePath);
                }
                if (Storage::disk('public')->exists($audioPath)) {
                    Storage::disk('public')->delete($audioPath);
                }

                return response()->json([
                    'status' => 'error',
                    'message' => $durationInfo['message'] ?? 'Failed to extract audio duration.',
                ]);
            }

            // Success
            $audioDuration = $durationInfo['duration_ms'];

        } catch (\Throwable $e) {
            // Delete image if audio processing fails
            if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }
            throw $e;
        }

        // Save to DB
        videoTalk::create([
            'photo' => $imagePath,
            'audio' => $audioPath,
            'audioDuration' => $audioDuration,
        ]);

        return response()->json([
            'message' => 'Files uploaded successfully.',
            'image_path' => $imagePath,
            'audio_path' => $audioPath
        ], 201);

    } catch (\Throwable $e) {
        Log::error('Upload failed', ['exception' => $e->getMessage()]);

        return response()->json([
            'error' => 'Upload failed.',
            'details' => $e->getMessage()
        ], 500);
    }
}





    // step 5 get audio duration
    public function getAudioDuration(Request $request)
    {


        try {
            $data = videoTalk::where('audioDuration', '0')
                ->orderByDesc('id')
                ->first();

            if (!$data) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Data not found.!',
                ]);
            }

            $audioPath = $data->audio;
            $getAudioDuration = $this->videoTalkServices->getAudioDuration($audioPath, );

            if ($getAudioDuration['status'] == 'success' && !empty($getAudioDuration['duration_ms'])) {
                $audioDuration = $getAudioDuration['duration_ms'];
                $data->update([
                    'audioDuration' => $audioDuration,
                ]);
                return response()->json([
                    'status' => 'success',
                ]);
            }

            $data->update([
                'audioDuration' => 10,
            ]);

            return response()->json([
                'status' => 'error',
                'message' => $getAudioDuration['message'],
            ]);

        } catch (\Throwable $th) {
            $data->update([
                'audioDuration' => 10,
            ]);
            return response()->json([
                'status' => 'error',
                'message' => 'Exception: ' . $th->getMessage(),
            ]);
        }
    }





    // step 1 upload photo for dreamface url
    public function uploadImgForDMUrl()
    {

        $userId = 'a9dda1dbfc5742954c79bbab4bfd66e4';
        $accountId = "67ea5db2eddc35000732655f";

        try {

            $data = videoTalk::where('photoURL', '0')->orderByDesc("id")->first();

            if (!$data) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Data not found.!',
                ]);
            }

            $fileUrl = $data->photo;

            $getphotoUrl = $this->videoTalkServices->uploadMediaFromStoragePath($fileUrl, $userId);

            // dd($imagePath);

            if ($getphotoUrl['status'] == 'success') {
                $fileUrl = $getphotoUrl['file_url'];

                $data->update([
                    'photoURL' => $fileUrl,
                ]);

                return response()->json([
                    'status' => 'success',
                    'photo_url' => $fileUrl,
                ]);
            }

            $data->update([
                'photoURL' => 10,
            ]);

            return response()->json([
                'status' => 'error',
                'message' => $getphotoUrl['message'],
            ]);

        } catch (\Throwable $th) {
            $data->update([
                'photoURL' => 10,
            ]);
            return response()->json([
                'status' => 'error',
                'message' => 'Exception: ' . $th->getMessage(),
            ]);
        }
    }

    // step 2 add photo for get avatar id
    public function addPhotoForGetAvatarID(Request $request)
    {

        $userId = 'a9dda1dbfc5742954c79bbab4bfd66e4';
        $accountId = "67ea5db2eddc35000732655f";

        try {

            $data = videoTalk::whereNotIn('photoURL', ['0', '10'])
                ->where('addPhotoForGetAvatarID', '0')
                ->orderByDesc('id')
                ->first();


            if (!$data) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Data not found.!',
                ]);
            }
            $photoURL = $data->photoURL;
            // dd($data->id);

            $addPhotoForGetAvatarID = $this->videoTalkServices->addPhotoForGetAvatarID($photoURL, $accountId, $userId);
            // dd($imagePath);

            if ($addPhotoForGetAvatarID['status'] == 'success') {

                $data->update([
                    'addPhotoForGetAvatarID' => 5,
                ]);

                return response()->json([
                    'status' => 'success',
                ]);
            }

            $data->update([
                'addPhotoForGetAvatarID' => 10,
            ]);

            return response()->json([
                'status' => 'error',
                'message' => $addPhotoForGetAvatarID['message'],
            ]);

        } catch (\Throwable $th) {
            $data->update([
                'addPhotoForGetAvatarID' => 10,
            ]);
            return response()->json([
                'status' => 'error',
                'message' => 'Exception: ' . $th->getMessage(),
            ]);
        }
    }


    // step 3 get avatar id using photo url
    public function getAvatarIdByPhotoUrl(Request $request)
    {

        $userId = 'a9dda1dbfc5742954c79bbab4bfd66e4';
        $accountId = "67ea5db2eddc35000732655f";
        try {
            $data = videoTalk::where('avatarId', '0')
                ->where('addPhotoForGetAvatarID', '5')
                ->orderByDesc('id')
                ->first();
            if (!$data) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Data not found.!',
                ]);
            }
            $photoURL = $data->photoURL;
            $getAvatarIdByFileUrl = $this->videoTalkServices->getAvatarIdByFileUrl($userId, $photoURL);
            if ($getAvatarIdByFileUrl['status'] == 'success' && !empty($getAvatarIdByFileUrl['avatar_id'])) {
                $avatarId = $getAvatarIdByFileUrl['avatar_id'];
                $data->update([
                    'avatarId' => $avatarId,
                ]);
                return response()->json([
                    'status' => 'success',
                ]);
            }

            $data->update([
                'avatarId' => 10,
            ]);

            return response()->json([
                'status' => 'error',
                'message' => @$getAvatarIdByFileUrl['message'],
            ]);

        } catch (\Throwable $th) {
            $data->update([
                'avatarId' => 10,
            ]);
            return response()->json([
                'status' => 'error',
                'message' => 'Exception: ' . $th->getMessage(),
            ]);
        }
    }

    // step 4 upload audio
    public function uploadAudio(Request $request)
    {

        $userId = 'a9dda1dbfc5742954c79bbab4bfd66e4';
        $accountId = "67ea5db2eddc35000732655f";
        try {
            $data = videoTalk::where('audioURL', '0')
                ->whereNotIn('avatarId', ['0', '10'])
                ->orderByDesc('id')
                ->first();
            if (!$data) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Data not found.!',
                ]);
            }
            $audioPath = $data->audio;
            $uploadAudio = $this->videoTalkServices->uploadAudio($audioPath, $userId);

            if ($uploadAudio['status'] == 'success' && !empty($uploadAudio['file_path'])) {
                $audioUrl = $uploadAudio['file_path'];
                $data->update([
                    'audioURL' => $audioUrl,
                ]);
                return response()->json([
                    'status' => 'success',
                ]);
            }

            $data->update([
                'audioURL' => 10,
            ]);

            return response()->json([
                'status' => 'error',
                'message' => $uploadAudio['message'],
            ]);

        } catch (\Throwable $th) {
            $data->update([
                'audioURL' => 10,
            ]);
            return response()->json([
                'status' => 'error',
                'message' => 'Exception: ' . $th->getMessage(),
            ]);
        }
    }




    // step 6 send craete video request  
    public function animateApi(Request $request)
    {
        $userId = 'a9dda1dbfc5742954c79bbab4bfd66e4';
        $accountId = "67ea5db2eddc35000732655f";

        try {
            $data = videoTalk::where('animateId', '0')
                ->orderByDesc('id')
                ->first();

            if (!$data) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Data not found.!',
                ]);
            }
            $fileUrl = $data->photoURL;
            $audioUrl = $data->audioURL;
            $duration_ms = $data->audioDuration;
            $avatarId = $data->avatarId;

            $callAnimateApi = $this->videoTalkServices->callAnimateApi($audioUrl, $duration_ms, $fileUrl, $avatarId, $userId, $accountId);
            if ($callAnimateApi['status'] === 'success') {
                $animateImageId = $callAnimateApi['animate_image_id'];
                $data->update([
                    'animateId' => $animateImageId,
                ]);
                return response()->json([
                    'status' => 'success',
                ]);
            }

            $data->update([
                'animateId' => 10,
            ]);

            return response()->json([
                'status' => 'error',
                'message' => $callAnimateApi['message'],
            ]);

        } catch (\Throwable $th) {
            $data->update([
                'animateId' => 10,
            ]);
            return response()->json([
                'status' => 'error',
                'message' => 'Exception: ' . $th->getMessage(),
            ]);
        }
    }

    // step 7 check video status
    public function checkVideoStatus(Request $request)
    {

        $twentySecondsAgo = Carbon::now()->subSeconds(20);

        $user = VideoTalk::whereNotIn('animateId', ['0', '10'])
            ->where(function ($query) use ($twentySecondsAgo) {
                $query->where('checkVideoStatus', 0)
                    ->orWhere(function ($subQuery) use ($twentySecondsAgo) {
                        $subQuery->where('checkVideoStatus', 4)
                            ->where('dateForcheckVSts', '<', $twentySecondsAgo);
                    });
            })
            ->first();

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data not found.!',
            ]);
        }

        $userId = 'a9dda1dbfc5742954c79bbab4bfd66e4';
        $accountId = "67ea5db2eddc35000732655f";

        try {
            $animateImageId = $user->animateId;
            $checkVideoStatus = $this->videoTalkServices->checkVideoStatus($animateImageId, $userId, $accountId);

            // dd($checkVideoStatus);

            if ($checkVideoStatus['status'] === 'success' && isset($checkVideoStatus['video_id']) && !empty($checkVideoStatus['video_id']) && isset($checkVideoStatus['web_work_status']) && !empty($checkVideoStatus['web_work_status'])) {

                $videoStatus = $checkVideoStatus['web_work_status'];
                $video_id = $checkVideoStatus['video_id'];

                if ($videoStatus === 0) {
                    $user->update([
                        'checkVideoStatus' => 4,
                        'dateForcheckVSts' => Carbon::now(),
                    ]);
                } else if ($videoStatus == 200) {
                    $user->update([
                        'videoId' => $video_id,
                        'checkVideoStatus' => 5,
                        'dateForcheckVSts' => Carbon::now(),
                    ]);
                } else {
                    $user->update([
                        'checkVideoStatus' => $videoStatus,
                        'dateForcheckVSts' => Carbon::now(),
                    ]);
                }
                return response()->json([
                    'status' => 'success',
                ]);

            }

            $user->update([
                'checkVideoStatus' => 10
            ]);
            return response()->json([
                'status' => 'error',
                'message' => 'faild to craete Video' . $checkVideoStatus['message'],
            ]);

        } catch (\Throwable $th) {
            $user->update([
                'checkVideoStatus' => 10
            ]);
            return response()->json([
                'status' => 'error',
                'message' => 'Exception: ' . $th->getMessage()
            ]);
        }


    }

    // step 8 get video url
    public function getVideoUrl(Request $request)
    {
        $user = VideoTalk::where('videoURL', '0')
            ->where('videoId', '!=', '')
            ->first();
        // dd($user);
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data not found.!',
            ]);
        }
        try {

            $videoId = $user->videoId;
            $getVideo = $this->videoTalkServices->getVideoURL($videoId);
            if ($getVideo['status'] === 'success' && isset($getVideo['data']) && !empty($getVideo['data'])) {

                $fullUrl = $getVideo['data'];
                // Get position of first '.mp4'
                $pos = strpos($fullUrl, '.mp4');
                // Extract the base URL up to and including that first '.mp4'
                if ($pos !== false) {
                    $baseVideoUrl = substr($fullUrl, 0, $pos + 4); // +4 for ".mp4"
                    $user->update([
                        'videoURL' => $baseVideoUrl,
                    ]);
                    return response()->json([
                        'status' => 'success',
                    ]);
                }
                $user->update([
                    'videoURL' => 10,
                ]);
                return response()->json([
                    'status' => 'error',
                    'message' => 'Faild to extract video url'
                ]);
            }
            $user->update([
                'videoURL' => 10,
            ]);
            return response()->json([
                'status' => 'error',
                'message' => 'Exception: ' . $getVideo['message']
            ]);
        } catch (\Throwable $th) {
            $user->update([
                'videoURL' => 10
            ]);
            return response()->json([
                'status' => 'error',
                'message' => 'Exception: ' . $th->getMessage()
            ]);
        }
    }

    // step 9 delete photo,audio and video from dreamface   
    public function deleteDataFromDF(Request $request)
    {
        $userId = 'a9dda1dbfc5742954c79bbab4bfd66e4';
        $accountId = "67ea5db2eddc35000732655f";

        try {
            $data = videoTalk::whereNotIn('isDel', ['5', '10'])
                ->where('videoId', '!=', '')
                ->orderByDesc('id')
                ->first();

            if (!$data) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Data not found.!',
                ]);
            }
            $videoId = $data->videoId;
            $avatarId = $data->avatarId;
            $deletePhotooUrl = $this->videoTalkServices->deletePhotoUrl($avatarId);
            $deleteVideoUrl = $this->videoTalkServices->deleteVideoUrl($videoId, $userId, $accountId);

            if ($deleteVideoUrl['status'] === 'success' && $deletePhotooUrl['status'] === 'success') {
                $data->update([
                    'isDel' => 5,
                ]);

                return response()->json([
                    'status' => 'success',
                ]);
            }

            $data->update([
                'isDel' => 10,
            ]);

            return response()->json([
                'status' => 'error',
                'message' => $deleteVideoUrl['message'],
            ]);

        } catch (\Throwable $th) {
            $data->update([
                'isDel' => 10,
            ]);
            return response()->json([
                'status' => 'error',
                'message' => 'Exception: ' . $th->getMessage(),
            ]);
        }
    }

}