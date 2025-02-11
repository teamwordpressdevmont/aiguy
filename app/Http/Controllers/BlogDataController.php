<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\BlogCategory;
// use App\Models\BlogCategory;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BlogDataController extends Controller
{
    // Display a listing of the blog posts
    public function index() {
        $categories = BlogCategory::all(); // Fetch all categories
        return view('blog.index', compact('categories'));
    }

    public function store(Request $request) {

        $validatedData = [
            'user_id'        => 1,
            'category_id'    => $request->category_id,
            'featured_image' => $imagePath ?? null, // Ensure this is set even if not uploaded
            'heading'        => $request->heading,
            'reading_time'   => $request->reading_time,
            'content'        => $request->content,
            'left_image'     => $leftImagePath ?? null, // Ensure this is set even if not uploaded
            'right_text'     => $request->right_text,
            'middle_text'    => $request->middle_text,
            'middle_image'   => $middleImagePath ?? null, // Ensure this is set even if not uploaded
            'sub_title'      => $request->sub_title,
            'sub_content'    => $request->sub_content,
            'sub_image'      => $subImagePath ?? null, // Ensure this is set even if not uploaded
        ];



        // if ($request->hasFile('featured_image')) {
            // $imagePath       = $request->file('featured_image') ? $request->file('featured_image')->store('blog-images', 'public') : null;
            // $leftImagePath   = $request->file('left_image') ? $request->file('left_image')->store('blog-images', 'public') : null;
            // $middleImagePath = $request->file('middle_image') ? $request->file('middle_image')->store('blog-images', 'public') : null;
            // $subImagePath    = $request->file('sub_image') ? $request->file('sub_image')->store('blog-images', 'public') : null;
        // } else {
        //     $imagePath = null;
        // }

        if ($request->hasFile('featured_image')) {
            $image = $request->file('featured_image');
            $formattedDate = Carbon::now()->format('Y-m-d-His');
            $actualFileName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $image->getClientOriginalExtension();
            $imageName = 'blog-featured_image-' . $actualFileName . '-' . $formattedDate . '.' . $extension;
            $imagePath = $image->storeAs('blog-images', $imageName, 'public');
            $validatedData['featured_image'] = $imagePath;
        }

        if ($request->hasFile('left_image')) {
            $image = $request->file('left_image');
            $formattedDate = Carbon::now()->format('Y-m-d-His');
            $actualFileName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $image->getClientOriginalExtension();
            $imageName = 'blog-left_image-' . $actualFileName . '-' . $formattedDate . '.' . $extension;
            $leftImagePath = $image->storeAs('blog-images', $imageName, 'public');
            $validatedData['left_image'] = $leftImagePath;
        }

        if ($request->hasFile('middle_image')) {
            $image = $request->file('middle_image');
            $formattedDate = Carbon::now()->format('Y-m-d-His');
            $actualFileName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $image->getClientOriginalExtension();
            $imageName = 'blog-middle_image-' . $actualFileName . '-' . $formattedDate . '.' . $extension;
            $middleImagePath = $image->storeAs('blog-images', $imageName, 'public');
            $validatedData['middle_image'] = $middleImagePath;
        }

        if ($request->hasFile('sub_image')) {
            $image = $request->file('sub_image');
            $formattedDate = Carbon::now()->format('Y-m-d-His');
            $actualFileName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $image->getClientOriginalExtension();
            $imageName = 'blog-sub_image-' . $actualFileName . '-' . $formattedDate . '.' . $extension;
            $subImagePath = $image->storeAs('blog-images', $imageName, 'public');
            $validatedData['sub_image'] = $subImagePath;
        }


        // Blog::create([
        //     'user_id'        => 1,
        //     'category_id'    => $request->category_id,
        //     'featured_image' => $imagePath,
        //     'heading'        => $request->heading,
        //     'reading_time'   => $request->reading_time,
        //     'content'        => $request->content,
        //     'left_image'     => $leftImagePath,
        //     'right_text'     => $request->right_text,
        //     'middle_text'    => $request->middle_text,
        //     'middle_image'   => $middleImagePath,
        //     'sub_title'      => $request->sub_title,
        //     'sub_content'    => $request->sub_content,
        //     'sub_image'      => $subImagePath,
        // ]);
        Blog::create($validatedData);

        return redirect()->back()->with('success', 'Blog submitted successfully!');
    }

    // Blog List
    public function list() {
        $categories = BlogCategory::all();
        $blog = Blog::with('category')->get();
        return view('blog.blog-list', compact('categories','blog'));
    }

    // Blog Edit
    public function edit($id) {
        $blog = Blog::findOrFail($id);
        $categories = BlogCategory::all();
        return view('blog.index', compact('blog', 'categories'));
    }

    // Blog Update
    public function update(Request $request, $id) {
        $request->validate([
            'featured_image' => 'image|mimes:jpg,jpeg,png|max:2048',
            'category_id'    => 'required|exists:blog_category,id',
            'heading'        => 'required|string|max:255',
            'reading_time'   => 'required|integer',
            'content'        => 'required|string',
            'left_image'     => 'image|mimes:jpg,jpeg,png|max:2048',
            'right_text'     => 'required|string',
            'middle_text'    => 'required|string',
            'middle_image'   => 'image|mimes:jpg,jpeg,png|max:2048',
            'sub_title'      => 'required|string',
            'sub_content'    => 'required|string',
            'sub_image'      => 'image|mimes:jpg,jpeg,png|max:2048',

        ]);

        $blog = Blog::findOrFail($id);

        if ($request->hasFile('featured_image')) {
            $imagePath = $request->file('featured_image')->store('blog-images', 'public');
            $blog->featured_image = $imagePath;
        }
        if ($request->hasFile('left_image')) {
            $leftImagePath = $request->file('left_image')->store('blog-images', 'public');
            $blog->left_image = $leftImagePath;
        }
        if ($request->hasFile('middle_image')) {
            $middleImagePath = $request->file('middle_image')->store('blog-images', 'public');
            $blog->middle_image = $middleImagePath;
        }
        if ($request->hasFile('sub_image')) {
            $subImagePath = $request->file('sub_image')->store('blog-images', 'public');
            $blog->sub_image = $subImagePath;
        }

        if ($request->hasFile('featured_image')) {
            $image = $request->file('featured_image');
            $formattedDate = Carbon::now()->format('Y-m-d-His');
            $actualFileName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $image->getClientOriginalExtension();
            $imageName = 'blog-featured_image-' . $actualFileName . '-' . $formattedDate . '.' . $extension;
            $imagePath = $image->storeAs('blog-images', $imageName, 'public');
            $validatedData['featured_image'] = $imagePath;
        }

        if ($request->hasFile('left_image')) {
            $image = $request->file('left_image');
            $formattedDate = Carbon::now()->format('Y-m-d-His');
            $actualFileName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $image->getClientOriginalExtension();
            $imageName = 'blog-left_image-' . $actualFileName . '-' . $formattedDate . '.' . $extension;
            $leftImagePath = $image->storeAs('blog-images', $imageName, 'public');
            $validatedData['left_image'] = $leftImagePath;
        }

        if ($request->hasFile('middle_image')) {
            $image = $request->file('middle_image');
            $formattedDate = Carbon::now()->format('Y-m-d-His');
            $actualFileName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $image->getClientOriginalExtension();
            $imageName = 'blog-middle_image-' . $actualFileName . '-' . $formattedDate . '.' . $extension;
            $middleImagePath = $image->storeAs('blog-images', $imageName, 'public');
            $validatedData['middle_image'] = $middleImagePath;
        }

        if ($request->hasFile('sub_image')) {
            $image = $request->file('sub_image');
            $formattedDate = Carbon::now()->format('Y-m-d-His');
            $actualFileName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $image->getClientOriginalExtension();
            $imageName = 'blog-sub_image-' . $actualFileName . '-' . $formattedDate . '.' . $extension;
            $subImagePath = $image->storeAs('blog-images', $imageName, 'public');
            $validatedData['sub_image'] = $subImagePath;
        }

        $blog->heading      = $request->heading;
        $blog->reading_time = $request->reading_time;
        $blog->content      = $request->content;
        $blog->right_text   = $request->right_text;
        $blog->middle_text  = $request->middle_text;
        $blog->sub_title    = $request->sub_title;
        $blog->sub_content  = $request->sub_content;
        $blog->category_id  = $request->category_id;

        $blog->save();

        return redirect()->back()->with('success', 'Blog updated successfully!');
    }

    // Blog Delete
    public function destroy($id) {
        $blog = Blog::findOrFail($id);
        $blog->delete();

        return redirect()->back()->with('success', 'Blog deleted successfully!');
    }

    // Blog View
    public function view($id) {
        $blog = Blog::findOrFail($id);
        return view('blog.view', compact('blog'));
    }

}
