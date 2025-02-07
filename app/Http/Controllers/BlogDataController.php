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
            'id' => 'required|unique:blogs,id',
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
            'featured_image' => $featuredImagePath,
            'heading' => $request->heading,
            'reading_time' => $request->reading_time,
            'content' => $request->content,
        ]);

        dd($request->all());


        return redirect()->back()->with('success', 'Blog submitted successfully!');
    }

    // public function view()
    // {
    //     $blog = Blog::all();
    //     return view('blog.blog-view', compact('blog'));
    // }



}
