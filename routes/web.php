<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dreamface\VideoTalkController;
use App\Http\Controllers\Dreamface\BgRemoveController;







// talking video
Route::post("/demo", [VideoTalkController::class,"demo"]);
Route::post("/store-img-audio", [VideoTalkController::class,"storeImgAduio"])->name("store.img.audio");
Route::get("/audio-duration", [VideoTalkController::class,"getAudioDuration"]);
Route::get("/upload-img-for-dm-url", [VideoTalkController::class,"uploadImgForDMUrl"]);
Route::get("/add-photo-for-avatar-id", [VideoTalkController::class,"addPhotoForGetAvatarID"]);
Route::get("/get-avatarid-using-photourl", [VideoTalkController::class,"getAvatarIdByPhotoUrl"]);
Route::get("/upload-audio", [VideoTalkController::class,"uploadAudio"]);
Route::get("/animate-api", [VideoTalkController::class,"animateApi"]);
Route::get("/check-video-status", [VideoTalkController::class,"checkVideoStatus"]);
Route::get("/get-video-url", [VideoTalkController::class,"getVideoUrl"]);
Route::get("/delete-data-from-df", [VideoTalkController::class,"deleteDataFromDF"]);

// bg remove

Route::get("/bg-remove", [BgRemoveController::class,"bgRemove"]);
Route::post("/bgr-request", [BgRemoveController::class,"RequestBgRemove"])->name('bg.request');
// Route::get("/bgr-test", [BgRemoveController::class,"BgrTest"]);








Route::get('test',[TalkingVideoController::class,'test']);
Route::get('talk-video',[TalkingVideoController::class,'makeTalkingVideo']);



// Route::get('delete-video-url',[TalkingVideoController::class,'deleteVideoUrl']);
// check talk video limitation cron
Route::get('upload-img',[TalkingVideoController::class,'uploadImg']);

