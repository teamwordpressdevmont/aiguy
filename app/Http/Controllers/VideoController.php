<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class VideoController extends Controller
{
   // List Videos (Paginated, Categorized by Type)
    public function index(Request $request)
    {
        try {
            $category = $request->category;
            $videos = Video::when($category, function ($query, $category) {
                return $query->where('category', $category);
            })->paginate(10);

            return response()->json($videos);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch videos',
                'error' => $e->getMessage()
            ], 500);
        }
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
        DB::beginTransaction();
        try {
            $validated = $request->validate([
                'youtube_link' => 'required|url',
                'title' => 'required|string|max:255',
                'imageurl' => 'nullable|string|max:255',
                'description' => 'required|string',
                'category_id' => 'nullable|integer',
            ]);

            $video = Video::create($validated);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Video added successfully',
                'data' => $video
            ], 201);

        } catch (ValidationException $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Validation Error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();

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
        DB::beginTransaction();
        try {
            $video = Video::findOrFail($id);

            $validated = $request->validate([
                'youtube_link' => 'nullable|url',
                'title' => 'nullable|string|max:255',
                'imageurl' => 'nullable|string|max:255',
                'description' => 'nullable|string',
                'category_id' => 'nullable|integer',
            ]);

            $video->update($validated);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Video updated successfully',
                'data' => $video
            ], 200);

        } catch (ValidationException $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Validation Error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();

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
       DB::beginTransaction();
       try {
           $video = Video::findOrFail($id);
           $video->delete();

           DB::commit();

           return response()->json([
               'success' => true,
               'message' => 'Video deleted successfully'
           ], 200);

       } catch (\Exception $e) {
           DB::rollBack();

           return response()->json([
               'success' => false,
               'message' => 'Video not found or could not be deleted',
               'error' => $e->getMessage()
           ], 404);
       }
   }
}
