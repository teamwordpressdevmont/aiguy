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
        $courses = Course::with('categoryCourses')->get();
        return view('courses.index', compact('courses'));
    }

    // Show Create Form
    public function create()
    {
        $categoryCourses = CategoryCourse::all(); 
        return view('courses.create', compact('categoryCourses'));
    }

    // Store Course Data
    public function store(Request $request)
    {
        // dd($request->all()); // Debugging ke liye

        $request->validate([
            'name' => 'required',
            'cover_image' => 'nullable|image',
            'logo' => 'nullable|image',
            'type' => 'required|in:free,paid',
            'short_description' => 'nullable',
            'categoryCourses' => 'required|array',
        ]);

        // Ensure categoryCourses is always an array
        $categoryCourses = is_array($request->categoryCourses) ? $request->categoryCourses : [];

        // File Upload
        $coverImage = null;
        $logo = null;

        if ($request->hasFile('cover_image')) {
            $image = $request->file('cover_image');
            $formattedDate = Carbon::now()->format('Y-m-d-His');
            $actualFileName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $image->getClientOriginalExtension();
            $imageName = 'courses-cover-' . $actualFileName . '-' . $formattedDate . '.' . $extension;
            $coverImage = $image->storeAs('courses-images', $imageName, 'public');
        }
        if ($request->hasFile('logo')) {
            $image = $request->file('logo');
            $formattedDate = Carbon::now()->format('Y-m-d-His');
            $actualFileName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $image->getClientOriginalExtension();
            $imageName = 'courses-logo-' . $actualFileName . '-' . $formattedDate . '.' . $extension;
            $logo = $image->storeAs('courses-images', $imageName, 'public');
        }

        // Create Course
        $course = Course::create([
            'name' => $request->name,
            'cover_image' => $coverImage,
            'logo' => $logo,
            'type' => $request->type,
            'short_description' => $request->short_description,
        ]);

        // Attach Categories (Only if they exist)
        if (!empty($categoryCourses)) {
            $course->categoryCourses()->attach($categoryCourses);
        }

        return redirect()->route('courses.index')->with('success', 'Course Created Successfully');
    }


    // Show Edit Form
    public function edit(Course $course)
    {
        $categoryCourses = CategoryCourse::all();
        return view('courses.edit', compact('course', 'categoryCourses'));
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
            'categoryCourses' => 'required|array',
        ]);

        $coverImage = $course->cover_image;
        $logo = $course->logo;

        if ($request->hasFile('cover_image')) {
            if ($course->cover_image) {
                Storage::disk('public')->delete($course->cover_image);
            }
            $image = $request->file('cover_image');
            $formattedDate = Carbon::now()->format('Y-m-d-His');
            $imageName = 'courses-cover-' . $formattedDate . '.' . $image->getClientOriginalExtension();
            $coverImage = $image->storeAs('courses-images', $imageName, 'public');
        }

        if ($request->hasFile('logo')) {
            if ($course->logo) {
                Storage::disk('public')->delete($course->logo);
            }
            $image = $request->file('logo');
            $formattedDate = Carbon::now()->format('Y-m-d-His');
            $imageName = 'courses-logo-' . $formattedDate . '.' . $image->getClientOriginalExtension();
            $logo = $image->storeAs('courses-images', $imageName, 'public');
        }

        $course->update([
            'name' => $request->name,
            'cover_image' => $coverImage,
            'logo' => $logo,
            'type' => $request->type,
            'short_description' => $request->short_description,
        ]);

        $course->categoryCourses()->sync($request->categoryCourses);

        return redirect()->route('courses.index')->with('success', 'Course Updated Successfully');
    }

    // Delete Course
    public function destroy($id)
    {
        $course = Course::findOrFail($id);

        if ($course) {
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