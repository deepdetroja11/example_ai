<?php
namespace App\Http\Controllers\Dreamface;

use App\Http\Controllers\Controller;
use App\Models\BgRemove;
use App\Services\Dreamface\BgRemoveServices;
use App\Services\MediaService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class BgRemoveController extends Controller
{
    protected BgRemoveServices $bgRemoveServices;
    protected MediaService $mediaService;

    public function __construct(
        BgRemoveServices $bgRemoveServices,
        MediaService $mediaService
    ) {
        $this->bgRemoveServices = $bgRemoveServices;
        $this->mediaService     = $mediaService;
    }

    public function BgRemove(Request $request)
    {

        return view('bgremove.bgr');
    }

    public function RequestBgRemove(Request $request)
    {
        try {
            $validated = $request->validate([
                'image' => 'required|image|max:2048',
            ]);

            if (! $request->hasFile('image')) {
                return response()->json(['error' => 'Image file is required.'], 400);
            }

            $imagePath = $this->mediaService->storeFile($request->file('image'), 'uploads/images');
            // Log::info('Image stored', ['path' => $imagePath]);

            $reqData = BgRemove::create([
                'photo' => $imagePath,
            ]);

            $userId    = 'a9dda1dbfc5742954c79bbab4bfd66e4';
            $accountId = "67ea5db2eddc35000732655f";

            $getImgUrl = $this->bgRemoveServices->uploadPhoto($imagePath, $userId);
            // Log::info('Upload photo response', $getImgUrl);

            if ($getImgUrl['status'] === 'success') {
                $imgUrl = $getImgUrl['file_url'];
                $submitForBGR = $this->bgRemoveServices->submitForBackgroundRemoval($imgUrl, $userId, $accountId);
                // Log::info('Submit for BGR response', $submitForBGR);

                if ($submitForBGR['status'] === 'success') {
                    $animateId = $submitForBGR['animate_id'];

                    $GetBGRImg = $this->bgRemoveServices->pollForBGRImage($userId, $accountId, $animateId);
                    // Log::info('Poll for BGR response', $GetBGRImg);

                    if ($GetBGRImg['status'] === 'success') {
                        $imageUrl = $GetBGRImg['matting_img_url'];
                        $reqData->update(['bgrUrl' => $imageUrl]);

                        $imageResponse = $this->mediaService->getImgContent($imageUrl);
                        if ($imageResponse) {
                            $content  = $imageResponse['content'];
                            $mimeType = $imageResponse['mimeType'];

                            return response($content, 200)
                                ->header('Content-Type', $mimeType)
                                ->header(
                                    'Content-Disposition',
                                    'inline; filename="matting_image.' . explode('/', $mimeType)[1] . '"'
                                );
                        } else {
                            Log::error('Failed to retrieve matting image content', ['imageUrl' => $imageUrl]);
                            return response()->json(['status' => 'error', 'message' => 'Failed to retrieve matting image content.'], 500);
                        }
                    }
                } else {
                    $reqData->update(['errorMsg' => 'submitForBackgroundRemoval:' . $submitForBGR['message']]);
                    Log::error('Submit for background removal failed', $submitForBGR);
                    return response()->json(['status' => 'error', 'message' => 'Faild to generate bgr img']);
                }
            } else {
                $reqData->update(['errorMsg' => 'uploadPhoto:' . $getImgUrl['message']]);
                Log::error('Upload photo failed', $getImgUrl);
                return response()->json(['status' => 'error', 'message' => 'Faild to generate bgr img']);
            }
        } catch (ValidationException $e) {
            Log::error('Validation failed', ['errors' => $e->errors()]);
            return response()->json(['error' => 'Validation failed', 'messages' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error('Image upload failed', ['exception' => $e->getMessage()]);
            return response()->json(['error' => 'Upload failed', 'message' => $e->getMessage()], 500);
        }
    }

    public function BgrTest()
    {
        $imageUrl = 'https://dreamface-resource-new.s3.amazonaws.com/server/common/work/f422a56fb2764f27b81b6b545c1aa2ff.png';

        $response = Http::get($imageUrl);

        if (! $response->successful()) {
            abort(404, 'Image not found or failed to load.');
        }

        $imageContent = $response->body();
        $mimeType     = $response->header('Content-Type');

        // Return the image as a response
        return response($imageContent, 200)
            ->header('Content-Type', $mimeType)
            ->header('Content-Disposition', 'inline; filename="image.png"');

    }
}
