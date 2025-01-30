<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CoursesInteraction extends Model
{
    //
    use HasFactory;

    protected $table = 'courses_interactions';

    protected $fillable = [
        'course_id',
        'user_id',
    ];
}
