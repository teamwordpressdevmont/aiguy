<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BlogDataController extends Controller
{
    // Display a listing of the blog posts
    public function blog()
    {
        return view('blog.blog');
    }

   
}