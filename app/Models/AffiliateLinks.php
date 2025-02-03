<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class AffiliateLinks extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'url',
        'description',
        'category_id',
        'tool_id',
        'course_id',
    ];

}
