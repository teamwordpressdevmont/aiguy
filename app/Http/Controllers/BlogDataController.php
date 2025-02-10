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
        return view('blog.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'featured_image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'heading'        => 'required|string|max:255',
            'reading_time'   => 'required|integer',
            'content'        => 'required|string',
            'left_image'     => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'right_text'     => 'required|string',
            'middle_text'    => 'required|string',
            'middle_image'   => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'sub_title'      => 'required|string',
            'sub_content'    => 'required|string',
            'sub_image'      => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // if ($request->hasFile('featured_image')) {
            $imagePath       = $request->file('featured_image') ? $request->file('featured_image')->store('blog-images', 'public') : null;
            $leftImagePath   = $request->file('left_image') ? $request->file('left_image')->store('blog-images', 'public') : null;
            $middleImagePath = $request->file('middle_image') ? $request->file('middle_image')->store('blog-images', 'public') : null;
            $subImagePath    = $request->file('sub_image') ? $request->file('sub_image')->store('blog-images', 'public') : null;
        // } else {
        //     $imagePath = null;
        // }



        Blog::create([
            'user_id' => 1,
            'featured_image' => $imagePath,
            'heading'        => $request->heading,
            'reading_time'   => $request->reading_time,
            'content'        => $request->content,
            'left_image'     => $leftImagePath,
            'right_text'     => $request->right_text,
            'middle_text'    => $request->middle_text,
            'middle_image'   => $middleImagePath,
            'sub_title'      => $request->sub_title,
            'sub_content'    => $request->sub_content,
            'sub_image'      => $subImagePath,
        ]);

        return redirect()->back()->with('success', 'Blog submitted successfully!');
    }

    // Blog List
    public function list()
    {
        $blog = Blog::all();
        return view('blog.blog-list', compact('blog'));
    }

    // Blog Edit
    public function edit($id)
    {
        $blog = Blog::findOrFail($id);
        return view('blog.index', compact('blog'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'featured_image' => 'image|mimes:jpg,jpeg,png|max:2048',
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

        $blog->heading = $request->heading;
        $blog->reading_time = $request->reading_time;
        $blog->content = $request->content;
        $blog->right_text = $request->right_text;
        $blog->middle_text = $request->middle_text;
        $blog->sub_title = $request->sub_title;
        $blog->sub_content = $request->sub_content;

        $blog->save();

        return redirect()->back()->with('success', 'Blog updated successfully!');
    }

    // Blog Delete
    public function destroy($id)
    {
        $blog = Blog::findOrFail($id);
        $blog->delete();

        return redirect()->back()->with('success', 'Blog deleted successfully!');
    }

    // Blog View
    public function view($id)
    {
        $blog = Blog::findOrFail($id);
        return view('blog.view', compact('blog'));
    }

}
