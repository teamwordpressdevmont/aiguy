<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VideoInteraction extends Model
{
    //
    use HasFactory;

    protected $table = 'video_interactions';

    protected $fillable = [
        'video_id',
        'user_id',
    ];

    public function Video() {
        return $this->belongsTo(Video::class, 'video_id', 'id');
    }
}
