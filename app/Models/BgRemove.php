<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BgRemove extends Model
{
    protected $table = 'bg_remove'; // your custom table name

    protected $fillable = [
        'photo',
        'bgrUrl',
        'errorMsg',
        'isDel',
        'created_at',
        'updated_at',
    ];
}
