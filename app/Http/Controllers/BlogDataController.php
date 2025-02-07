<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\BlogCategory;
use Illuminate\Http\Request;

class BlogDataController extends Controller
{
    // Display a listing of the blog posts
    public function index()
    {
        $categories = BlogCategory::all();
        return view('blog.blog', compact('categories'));
    }

    public function store(Request $request)
    {

        // $request = $request->all();
        $request->validate([
            'id' => 'required|unique:blogs,id',
            'user_id' => 'required|exists:users,id',
            'featured_image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'heading' => 'required|string|max:255',
            'reading_time' => 'required|integer',
            'content' => 'required|string',
        ]);

        // Upload Image
        $featuredImagePath = $request->file('featured_image')->store('uploads', 'public');

        // Save to Database
        Blog::create([
            'id' => $request->id,
            'user_id' => $request->user_id,
            'featured_image' => $featuredImagePath,
            'heading' => $request->heading,
            'reading_time' => $request->reading_time,
            'content' => $request->content,
        ]);

        return redirect()->back()->with('success', 'Blogs submitted successfully!');
    }

    public function view()
    {
        $categories = BlogCategory::all();
        $aiTools = Blog::with('category')->get();
        return view('blog.blog-view', compact('blogs', 'categories'));
    }


}
