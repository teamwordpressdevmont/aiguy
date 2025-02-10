<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Category;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    // Show All Courses
    public function index()
    {
        $courses = Course::with('categories')->get();
        return view('courses.index', compact('courses'));
    }

    // Show Create Form
    public function create()
    {
        $categories = Category::all(); 
        return view('courses.create', compact('categories'));
    }

    // Store Course Data
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'cover_image' => 'nullable|image',
            'logo' => 'nullable|image',
            'type' => 'required|in:free,paid',
            'short_description' => 'nullable',
            'categories' => 'required|array',
        ]);

        // File Upload
        $coverImage = $request->file('cover_image') ? $request->file('cover_image')->store('covers', 'public') : null;
        $logo = $request->file('logo') ? $request->file('logo')->store('logos', 'public') : null;

        // Create Course
        $course = Course::create([
            'name' => $request->name,
            'cover_image' => $coverImage,
            'logo' => $logo,
            'type' => $request->type,
            'short_description' => $request->short_description,
        ]);

        // Attach Categories
        $course->categories()->attach($request->categories);

        return redirect()->route('courses.index')->with('success', 'Course Created Successfully');
    }

    // Show Edit Form
    public function edit(Course $course)
    {
        $categories = Category::all();
        return view('courses.edit', compact('course', 'categories'));
    }

    // Update Course
    public function update(Request $request, Course $course)
    {
        $request->validate([
            'name' => 'required',
            'cover_image' => 'nullable|image',
            'logo' => 'nullable|image',
            'type' => 'required|in:free,paid',
            'short_description' => 'nullable',
            'categories' => 'required|array',
        ]);

        // File Upload
        if ($request->hasFile('cover_image')) {
            $course->cover_image = $request->file('cover_image')->store('covers', 'public');
        }
        if ($request->hasFile('logo')) {
            $course->logo = $request->file('logo')->store('logos', 'public');
        }

        // Update Course
        $course->update([
            'name' => $request->name,
            'type' => $request->type,
            'short_description' => $request->short_description,
        ]);

        // Sync Categories
        $course->categories()->sync($request->categories);

        return redirect()->route('courses.index')->with('success', 'Course Updated Successfully');
    }

    // Delete Course
    public function destroy(Course $course)
    {
        $course->categories()->detach();
        $course->delete();

        return redirect()->route('courses.index')->with('success', 'Course Deleted Successfully');
    }
}
