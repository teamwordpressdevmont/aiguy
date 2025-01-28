<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VideoController extends Controller
{
   // List Videos (Paginated, Categorized by Type)
   public function index(Request $request)
   {

    
       $category = $request->category;
       $videos = Video::when($category, function ($query, $category) {
           return $query->where('category', $category);
       })->paginate(10);

       return response()->json($videos);
   }

   // Get Video Details
   public function show($id)
   {
       $video = Video::findOrFail($id);
       return response()->json($video);
   }

   // Add Video (Admin)
   public function store(Request $request)
   {
       $validated = $request->validate([
           'youtube_link' => 'required|url',
           'title' => 'required|string',
           'imageurl' => 'required|string',
           'description' => 'required|string',
           'category' => 'required|string',
       ]);

       $video = Video::create($validated);
       return response()->json($video, 201);
   }

   // Update Video (Admin)
   public function update(Request $request, $id)
   {
       $video = Video::findOrFail($id);
       $validated = $request->validate([
           'youtube_link' => 'url',
           'title' => 'string',
           'imageurl' => 'string',
           'description' => 'string',
           'category' => 'string',
       ]);

       $video->update($validated);
       return response()->json($video);
   }

   // Delete Video (Admin)
   public function destroy($id)
   {
       $video = Video::findOrFail($id);
       $video->delete();
       return response()->json(null, 204);
   }
}
