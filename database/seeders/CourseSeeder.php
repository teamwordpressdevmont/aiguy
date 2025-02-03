<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Courses; // Make sure to import the Course model


class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Courses::create([
            'course_name' => 'Introduction to Web Development',
        ]);

        Courses::create([
            'course_name' => 'Data Science with Python',
        ]);

        Courses::create([
            'course_name' => 'Building iOS Apps with Swift',
        ]);

    }
}
