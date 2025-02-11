<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CategoryCourse;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class CourseController extends Controller
{
    // Show All Courses
    public function index()
    {
        $courses = Course::with('category_course')->get();
        return view('courses.index', compact('courses'));
    }

    // Show Create Form
    public function create()
    {
        $category_course = CategoryCourse::all(); 
        return view('courses.create', compact('category_course'));
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
            'category_courses' => 'required|array',
        ]);

        // Fix categories if sent as a string
        $category_courses = is_array($request->category_courses) ? $request->category_courses : [$request->category_courses];

        // File Upload
        if ($request->hasFile('cover_image')) {
            $image = $request->file('cover_image');
            $formattedDate = Carbon::now()->format('Y-m-d-His');
            $actualFileName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $image->getClientOriginalExtension();
            $imageName = 'courses-cover-' . $actualFileName . '-' . $formattedDate . '.' . $extension;
            $coverImage = $image->storeAs('courses-images', $imageName, 'public');
            $validatedData['cover_image'] = $coverImage;
        }
        if ($request->hasFile('logo')) {
            $image = $request->file('logo');
            $formattedDate = Carbon::now()->format('Y-m-d-His');
            $actualFileName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $image->getClientOriginalExtension();
            $imageName = 'courses-logo-' . $actualFileName . '-' . $formattedDate . '.' . $extension;
            $logo = $image->storeAs('courses-images', $imageName, 'public');
            $validatedData['logo'] = $logo;
        }

        // Create Course
        $course = Course::create([
            'name' => $request->name,
            'cover_image' => $coverImage,
            'logo' => $logo,
            'type' => $request->type,
            'short_description' => $request->short_description,
        ]);

        // Attach Categories (Ensures it's an array)
        $course->category_courses()->attach($category_courses);

        return redirect()->route('courses.index')->with('success', 'Course Created Successfully');
    }

    // Show Edit Form
    public function edit(Course $course)
    {
        $category_course = Category::all();
        return view('courses.edit', compact('course', 'category_course'));
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
            'category_courses' => 'required|array',
        ]);

        // File Upload
        // if ($request->hasFile('cover_image')) {
        //     $course->cover_image = $request->file('cover_image')->store('covers', 'public');
        // }
        if ($request->hasFile('cover_image')) {
            $image = $request->file('cover_image');
            $formattedDate = Carbon::now()->format('Y-m-d-His');
            $actualFileName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $image->getClientOriginalExtension();
            $imageName = 'courses-cover-' . $actualFileName . '-' . $formattedDate . '.' . $extension;
            $coverImage = $image->storeAs('courses-images', $imageName, 'public');
            $validatedData['cover_image'] = $coverImage;
        }
        if ($request->hasFile('logo')) {
            $image = $request->file('logo');
            $formattedDate = Carbon::now()->format('Y-m-d-His');
            $actualFileName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $image->getClientOriginalExtension();
            $imageName = 'courses-logo-' . $actualFileName . '-' . $formattedDate . '.' . $extension;
            $logo = $image->storeAs('courses-images', $imageName, 'public');
            $validatedData['logo'] = $logo;
        }
       

        // Update Course
        $course->update([
            'name' => $request->name,
            'cover_image' => $coverImage, // Updated to use the correct variable
            'logo' =>  $logo,
            'type' => $request->type,
            'short_description' => $request->short_description,
        ]);

        // Sync Categories
        $course->category_courses()->sync($request->category_courses);

        return redirect()->route('courses.index')->with('success', 'Course Updated Successfully');
    }

    // Delete Course
    public function destroy(Course $course , $id)
    {

        $course = Course::findOrFail($id);

        if (!is_null($course)) {

            if ($course->cover_image) {
                Storage::disk('public')->delete($course->cover_image);
            }

            if ($course->logo) {
                Storage::disk('public')->delete($course->logo);
            }

            $course->delete();
        }

        return redirect()->route('courses.index')->with('success', 'Course Deleted Successfully');
    }
}