<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\UserNotification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    // Get all notifications
    public function index()
    {
        try {

            $notifications = Notification::all();

            return response()->json($notifications);
            
        } catch (Exception $e) {

            return response()->json(['error' => 'Failed to retrieve notifications'], 500);
        }
    }

    // Create a new notification
    public function store(Request $request)
    {
        try {

            $request->validate([
                'title' => 'required|string|max:255',
                'message' => 'required|string',
            ]);
            
            $notification = Notification::create($request->all());

            return response()->json($notification, 201);

        } 
        catch (Exception $e) {
            return response()->json(['error' => 'Failed to create notification'], 500);
        }
    }

    // Assign notification to a user
    public function assignToUser(Request $request)
    {
        try {
            
            $request->validate([
                'notification_id' => 'required|exists:notifications,id',
                'show_to' => 'required|exists:users,id',
            ]);

            $userNotification = UserNotification::create([
                'notification_id' => $request->notification_id,
                'show_to' => $request->show_to,
                'seen' => false
            ]);

            return response()->json($userNotification, 201);

        } 
        catch (Exception $e) {
            
            return response()->json(['error' => 'Failed to assign notification'], 500);
        }
    }

    // Get notifications for a specific user
    public function userNotifications($userId)
    {
        try {
            $notifications = UserNotification::where('show_to', $userId)->with('notification')->get();

            return response()->json($notifications);

        } 
        catch (Exception $e) {

            return response()->json(['error' => 'Failed to retrieve user notifications'], 500);
        }
    }

    // Mark notification as seen
    public function markAsSeen($id)
    {
        try {

            $userNotification = UserNotification::findOrFail($id);

            $userNotification->update(['seen' => true]);

            return response()->json(['message' => 'Notification marked as seen']);

        } 
        catch (ModelNotFoundException $e) {

            return response()->json(['error' => 'Notification not found'], 404);

        } 
        catch (Exception $e) {
            
            return response()->json(['error' => 'Failed to mark notification as seen'], 500);
        }
    }
}