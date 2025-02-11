<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'cover_image', 'logo', 'type', 'short_description'];
    

    public function categoryCourses()
    {
        return $this->belongsToMany(CategoryCourse::class, 'category_course_relation', 'course_id', 'category_id');
    }

}