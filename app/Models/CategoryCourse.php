<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryCourse extends Model
{
    use HasFactory;

    protected $fillable = ['name' , 'icon' , 'slug' , 'description'];

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'category_course_relation');
    }
}
