<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BlogCategory extends Model
{
    //
    use HasFactory;

    protected $table = 'blog_category';

    protected $fillable = [
        // 'parent_category_id',
        'name',
        'icon',
        'description',
    ];

}
