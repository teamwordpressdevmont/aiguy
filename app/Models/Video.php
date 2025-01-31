<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Video extends Model
{
    //
    use HasFactory;

    protected $table = 'videos';

    protected $fillable = [
        'youtube_link',
        'title',
        'imageurl',
        'description',
    ];

    public function VideoInteraction ()
    {
        return $this->hasMany(VideoInteraction::class, 'video_id', 'id');
    }
}
