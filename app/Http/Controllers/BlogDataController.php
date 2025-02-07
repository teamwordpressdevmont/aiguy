<?php

namespace App\Http\Controllers;

use App\Models\Blog;
// use App\Models\BlogCategory;
use Illuminate\Http\Request;

class BlogDataController extends Controller
{
    // Display a listing of the blog posts
    public function index()
    {
        return view('blog.blog');
    }

    public function store(Request $request)
    {
        $request->validate([
            'featured_image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'heading' => 'required|string|max:255',
            'reading_time' => 'required|integer',
            'content' => 'required|string',
        ]);

        if ($request->hasFile('featured_image')) {
            $imagePath = $request->file('featured_image')->store('uploads', 'public');
        } else {
            $imagePath = null;
        }

        Blog::create([
            'user_id' => auth()->id(),
            'featured_image' => $imagePath,
            'heading' => $request->heading,
            'reading_time' => $request->reading_time,
            'content' => $request->content,
        ]);

        return redirect()->back()->with('success', 'Blog submitted successfully!');
    }


    // public function view()
    // {
    //     $blog = Blog::all();
    //     return view('blog.blog-view', compact('blog'));
    // }



}
