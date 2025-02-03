<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tools;
use App\Models\Comment;
use App\Models\Reply;
use App\Models\User;

class CommentController extends Controller

{

    public function postComment(Request $request, $toolId)
    {
        try {

            $request->validate([
                'tools_id' => 'required|exists:tools,id|in:' . $toolId,
                'content' => 'required|string',
            ]);

            $tool = Tools::findOrFail($toolId);

            $comment = $tool->comments()->create([
                'tools_id' => $toolId,
                'user_id'  => auth()->id(), // Get the logged-in user's ID
                'content' => $request->content,
            ]);

            return response()->json($comment, 201);

        } 

        catch (\Exception $e) {

            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function replyToComment(Request $request, $commentId)
    {
        try {

            $request->validate([
                'content' => 'required|string',
            ]);

            $comment = Comment::findOrFail($commentId);

            $reply = $comment->replies()->create([
                'content' => $request->content,
            ]);

            return response()->json($reply, 201);

        }

        catch (\Exception $e) {

            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function listComments($toolId)
    {
        try {

            $tool = Tools::with('comments.replies')->findOrFail($toolId);

            return response()->json($tool->comments);

        } 
        catch (\Exception $e) {

            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

}
