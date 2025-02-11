<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'cover_image', 'logo', 'type', 'short_description'];

    public function category_course()
    {
        return $this->belongsToMany(Category::class);
    }

    // public function categories()
    // {
    //     return $this->belongsToMany(Category::class, 'category_course');
    // }
}