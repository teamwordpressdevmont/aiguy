<?php

namespace App\Http\Controllers;

use App\Models\BookmarkFolder;
use App\Models\Tool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

use Exception;

class BookmarkController extends Controller
{
    // Create Bookmark Folder
    public function createBookmarkFolder(Request $request)
    {
        DB::beginTransaction();
        
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'user_id' => 'required|exists:users,id',
                'tool_id' => 'array',
                'tool_id.*' => 'exists:tools,id'
            ]);

            // Create the folder
            $folder = BookmarkFolder::create([
                'name' => $validated['name'],
                'user_id' => $validated['user_id']
            ]);

            // Attach tools if provided
            if (!empty($validated['tool_id'])) {
                $folder->tools()->attach($validated['tool_id']);
            }

            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Bookmark folder created successfully',
                'data' => $folder->load('tools')
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    // Add Tool to Bookmark Folder
    public function addToolToFolder(Request $request, $folderId)
    {
        DB::beginTransaction();

        try {
            $validated = $request->validate([
                'tool_id' => 'required|exists:tools,id',
            ]);

            $folder = BookmarkFolder::findOrFail($folderId);
            $tool = Tool::findOrFail($validated['tool_id']);

            // Prevent duplicate entry
            if ($folder->tools()->where('tool_id', $tool->id)->exists()) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Tool already exists in this folder'
                ], 409);
            }

            $folder->tools()->attach($tool->id);
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Tool added to bookmark folder successfully'
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function updateBookmarkFolder(Request $request, $folderId)
    {
        DB::beginTransaction();

        try {
            $validated = $request->validate([
                'folder_id' => 'required|exists:bookmark_folders,id',
                'name' => 'required|string|max:255',
                'tool_id' => 'array',
                'tool_id.*' => 'exists:tools,id'
            ]);

            // Ensure the folder ID in the URL matches the one in the request body
            if ($validated['folder_id'] != $folderId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Folder ID mismatch'
                ], 400);
            }

            $folder = BookmarkFolder::findOrFail($folderId);

            // Update folder name
            $folder->update(['name' => $validated['name']]);

            // Sync tools: This will remove missing ones and add new ones
            $folder->tools()->sync($validated['tool_id']);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Bookmark folder updated successfully',
                'data' => $folder->load('tools')
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }




    // Remove Tool from Bookmark Folder
    public function removeToolFromFolder(Request $request)
    {
        try {
            $validated = $request->validate([
                'folder_id' => 'required|exists:bookmark_folders,id',
                'tool_id' => 'required|exists:tools,id',
            ]);

            $folder = BookmarkFolder::findOrFail($validated['folder_id']);
            $tool = Tool::findOrFail($validated['tool_id']);

            // Remove the tool from the folder
            $folder->tools()->detach($tool->id);

            return response()->json([
                'success' => true,
                'message' => 'Tool removed from bookmark folder successfully'
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // List Bookmark Folders for a User
    public function listFolders(Request $request)
    {
        try {
            $validated = $request->validate([
                'user_id' => 'required|exists:users,id',
            ]);

            $folders = BookmarkFolder::with('tools')->where('user_id', $validated['user_id'])->get();

            return response()->json([
                'success' => true,
                'message' => 'Bookmark folders retrieved successfully',
                'data' => $folders
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Share Bookmark Folder
    public function shareFolder($folderId)
    {
        try {
            $folder = BookmarkFolder::with('tools')->findOrFail($folderId);

            // Generate a shareable link (example, you can also create a PDF document or other formats)
            $shareLink = route('bookmark.folder.show', ['folder' => $folderId]);

            return response()->json([
                'success' => true,
                'message' => 'Bookmark folder shared successfully',
                'share_link' => $shareLink
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Folder not found or could not be shared',
                'error' => $e->getMessage()
            ], 404);
        }
    }

}
