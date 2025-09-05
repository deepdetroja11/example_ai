<?php

use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dreamface\VideoTalkController;
use App\Http\Controllers\Dreamface\BgRemoveController;
use App\Http\Controllers\Dreamface\PhotoEnhanceController;
use App\Http\Controllers\IndexController;

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
Route::get("/bgr-test", [BgRemoveController::class,"BgrTest"]);


// photo enhance
Route::get("/photo-enhance", [PhotoEnhanceController::class,"photoEnhance"]);
Route::post("/photo-enhance-request", [PhotoEnhanceController::class,"RequestPhotoEnhance"])->name('enhance.request');
Route::get("/photo-enhance-test", [PhotoEnhanceController::class,"BgrTest"]);


Route::get("/", [IndexController::class,"index"])->name('index');
Route::get("/sign-up", [RegisterController::class,"getSignupForm"])->name("get.signup.form");
Route::post('/sign-up', [RegisterController::class, 'store'])->name('signup.store');
Route::get("/sign-in", [RegisterController::class,"getSigninForm"])->name("get.signin.form");
Route::post('/sign-in', [RegisterController::class, 'postLoginForm'])->name('signin.store');




Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);


Route::get('test',[TalkingVideoController::class,'test']);
Route::get('talk-video',[TalkingVideoController::class,'makeTalkingVideo']);



// Route::get('delete-video-url',[TalkingVideoController::class,'deleteVideoUrl']);
// check talk video limitation cron
Route::get('upload-img',[TalkingVideoController::class,'uploadImg']);

