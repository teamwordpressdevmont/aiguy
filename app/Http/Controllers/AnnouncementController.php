<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\AdminAnnouncement;
use App\Models\TrackAnnouncement;
use App\Models\User;


class AnnouncementController extends Controller
{
    public function sendAnnouncement(Request $request)
    {
        $request->validate([
            'announcement_name' => 'required|string|max:255',
            'announcement_description' => 'required|string',
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id'
        ]);

        // Create Announcement
        $announcement = AdminAnnouncement::create([
            'announcement_name' => $request->announcement_name,
            'announcement_description' => $request->announcement_description,
        ]);

        // Track Announcement for Users
        foreach ($request->user_ids as $userId) {
            TrackAnnouncement::create([
                'announcement_id' => $announcement->id,
                'user_id' => $userId,
            ]);
        }

        return response()->json([
            'message' => 'Announcement sent successfully',
            'announcement' => $announcement
        ], 201);
    }

    public function trackAnnouncement(Request $request)
    {
        $request->validate([
            'announcement_id' => 'required|exists:admin_announcements,id',
            'user_id' => 'required|exists:users,id'
        ]);

        // Delete the tracked announcement for the user
        TrackAnnouncement::where('announcement_id', $request->announcement_id)->where('user_id', $request->user_id)->delete();

        return response()->json([
            'message' => 'Announcement tracked and removed successfully.'
        ], 200);
    }
}