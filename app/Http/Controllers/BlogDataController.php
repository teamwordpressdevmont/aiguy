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
        $blogs = Blog::all();
        return view('blog.blog', compact('blogs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id' => 'required|unique:blogs,id',
            'user_id' => 'required|exists:users,id',
            'featured_image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'heading' => 'required|string|max:255',
            'reading_time' => 'required|integer',
            'content' => 'required|string',
        ]);

        // Upload Images
        $logoPath = $request->file('logo') ? $request->file('logo')->store('logos', 'public') : null;
        $coverPath = $request->file('cover') ? $request->file('cover')->store('covers', 'public') : null;

        // Save to Database
        Blog::create([
            'id' => $request->id,
            'user_id' => $request->user_id,
            'featured_image' => $request->featured_image,
            'heading' => $request->heading,
            'reading_time' => $request->reading_time,
            'content' => $request->content,
        ]);

        return redirect()->back()->with('success', 'Blogs submitted successfully!');
    }


}
