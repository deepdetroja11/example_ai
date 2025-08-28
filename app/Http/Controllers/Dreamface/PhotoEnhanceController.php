<?php

namespace App\Http\Controllers\Dreamface;

use App\Http\Controllers\Controller;
use App\Services\MediaService;
use App\Services\Dreamface\PhotoEnhanceServices;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use App\Models\BgRemove;
use Carbon\Carbon;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class PhotoEnhanceController extends Controller
{
    protected PhotoEnhanceServices $photoEnhanceServices;
    protected MediaService $mediaService;

    public function __construct(
        PhotoEnhanceServices $photoEnhanceServices,
        MediaService $mediaService
    ) {
        $this->photoEnhanceServices = $photoEnhanceServices;
        $this->mediaService = $mediaService;
    }


    public function photoEnhance(Request $request)
    {
        return view('enhance.enhance');
    }


    public function RequestPhotoEnhance(Request $request)
    {

        try {
            set_time_limit(300);
            $validated = $request->validate([
                'image' => 'required|image',
            ]);

            if (!$request->hasFile('image')) {
                return response()->json(['error' => 'Image file is required.'], 400);
            }

            $imagePath = $this->mediaService->storeFile($request->file('image'), 'uploads/images');

            $reqData = BgRemove::create([
                'photo' => $imagePath
            ]);

            $userId = '42c37a2f689065a5a8f6519034f0f72a';
            $accountId = "67ea5a35eddc350007325e54";

            $getImgUrl = $this->photoEnhanceServices->uploadPhoto($imagePath, $userId);

            if ($getImgUrl['status'] === 'success') {
                $imgUrl = $getImgUrl['file_url'];
                $submitForEnhance = $this->photoEnhanceServices->submitForPhotoEnhance($imgUrl, $userId, $accountId);

                if ($submitForEnhance['status'] === 'success') {
                    $animateId = $submitForEnhance['animate_id'];

                    $GetResultImg = $this->photoEnhanceServices->pollResultImage($userId, $accountId, $animateId);

                    if ($GetResultImg['status'] === 'success') {
                        $imageUrl = $GetResultImg['picture_path_list'];

                        $reqData->update([
                            'bgrUrl' => $imageUrl,
                        ]);


                        $imageResponse = $this->mediaService->getImgContent($imageUrl);
                        if ($imageResponse) {
                            $content = $imageResponse['content'];
                            $mimeType = $imageResponse['mimeType'];

                            // Example: stream to browser
                            return response($content, 200)
                                ->header('Content-Type', $mimeType)
                                ->header('Content-Disposition', 'inline; filename="matting_image.' . explode('/', $mimeType)[1] . '"');

                        } else {
                            return response()->json([
                                'status' => 'error',
                                'message' => 'Failed to retrieve matting image content.'
                            ], 500);
                        }

                    } else {
                        $reqData->update([
                            'errorMsg' => 'pollForBGRImage:' . $GetResultImg['message'],
                        ]);
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Faild to generate bgr img',
                        ]);
                    }
                } else {
                    $reqData->update([
                        'errorMsg' => 'submitForBackgroundRemoval:' . $submitForEnhance['message'],
                    ]);
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Faild to generate bgr img',
                    ]);

                }
            } else {
                $reqData->update([
                    'errorMsg' => 'uploadPhoto:' . $getImgUrl['message'],
                ]);
                return response()->json([
                    'status' => 'error',
                    'message' => 'Faild to generate bgr img',
                ]);
            }

        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'Validation failed',
                'messages' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            Log::error('Image upload failed', ['exception' => $e->getMessage()]);
            // dd($e);
            return response()->json([
                'error' => 'Upload failed',
                'message' => $e->getMessage()
            ], 500);
        }
    }


}
