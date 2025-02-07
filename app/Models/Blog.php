<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Blog extends Model
{
    //
    use HasFactory;

    protected $table = 'blogs';

    protected $fillable = [
        // 'category_id',
        'id',
        'user_id',
        'featured_image',
        'heading',
        'reading_time',
        'content',
    ];
}
