<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Course extends Model
{
    //
    use HasFactory;

    protected $table = 'courses';

    protected $fillable = [
        'title',
        'description',
        'imageurl',
        'type',
        'redirect_link'
    ];

    public function CoursesInteraction ()
    {
        return $this->hasMany(CoursesInteraction::class, 'course_id', 'id');
    }
}
