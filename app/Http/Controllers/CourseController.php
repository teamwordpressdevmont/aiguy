<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
   // List Free Courses
   public function listFreeCourses()
   {
       $courses = Course::where('type', 'free')->get();
       return response()->json($courses);
   }

   // List Paid Courses
   public function listPaidCourses()
   {
       $courses = Course::where('type', 'paid')->get();
       return response()->json($courses);
   }
}
