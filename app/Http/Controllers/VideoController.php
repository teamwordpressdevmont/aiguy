<?php

namespace App\Http\Controllers;

use App\Models\Video;
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
        try {
            $video = Video::findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $video
            ], 200);

        } catch (Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Video not found',
                'error' => $e->getMessage()
            ], 404);

        }
    }


   //Add
   public function store(Request $request)
   {
       try {
           $validated = $request->validate([
               'youtube_link' => 'required|url',
               'title' => 'required|string|max:255',
               'description' => 'required|string',
               'category' => 'required|string|max:100',
           ]);

           $video = Video::create($validated);

           return response()->json([
               'success' => true,
               'message' => 'Video added successfully',
               'data' => $video
           ], 201);

       } catch (ValidationException $e) {
           return response()->json([
               'success' => false,
               'message' => 'Validation Error',
               'errors' => $e->errors()
           ], 422);
       } catch (Exception $e) {
           return response()->json([
               'success' => false,
               'message' => 'Something went wrong',
               'error' => $e->getMessage()
           ], 500);
       }
   }
   
   // Update Video
   public function update(Request $request, $id)
   {
      try {
           $video = Video::findOrFail($id);

           $validated = $request->validate([
               'youtube_link' => 'nullable|url',
               'title' => 'nullable|string|max:255',
               'description' => 'nullable|string',
               'category' => 'nullable|string|max:100',
           ]);

           $video->update($validated);

           return response()->json([
               'success' => true,
               'message' => 'Video updated successfully',
               'data' => $video
           ], 200);

       } catch (ValidationException $e) {

           return response()->json([
               'success' => false,
               'message' => 'Validation Error',
               'errors' => $e->errors()
           ], 422);

       } catch (Exception $e) {

           return response()->json([
               'success' => false,
               'message' => 'Something went wrong',
               'error' => $e->getMessage()
           ], 500);

       }
   }

   // Delete Video
   public function destroy($id)
    {
        try {
            $video = Video::findOrFail($id);
            $video->delete();

            return response()->json([
                'success' => true,
                'message' => 'Video deleted successfully'
            ], 200);

        } catch (Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Video not found or could not be deleted',
                'error' => $e->getMessage()
            ], 404);
            
        }
    }
}
