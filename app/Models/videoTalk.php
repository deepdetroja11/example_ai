<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VideoTalk extends Model
{
    protected $table = 'video_talk'; // your custom table name

    protected $fillable = [
        'photo',
        'audio',
        'photoURL',
        'addPhotoForGetAvatarID',
        'avatarId',
        'audioURL',
        'audioDuration',
        'animateId',
        'checkVideoStatus',
        'dateForcheckVSts',
        'videoId',
        'videoURL',
        'isDel',
        'created_at',
        'updated_at',
    ];
}
