<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function read($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);

        $notification->markAsRead();
        
        if (isset($notification->data['link'])) {
            return redirect($notification->data['link']);
        }

        return redirect()->back();
    }

    public function clearAll()
    {
        $user = Auth::user();
        
        if ($user) {
            $user->unreadNotifications->markAsRead();
        }

        return redirect()->back();
    }

    public function getLatest()
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $unreadNotifications = $user->unreadNotifications;
        
        $notifications = $unreadNotifications->take(10)->map(function ($notif) {
            return [
                'id' => $notif->id,
                'title' => $notif->data['title'] ?? 'Notification',
                'message' => $notif->data['message'] ?? '',
                'type' => $notif->data['type'] ?? 'info',
                'created_at' => $notif->created_at->diffForHumans(),
            ];
        });

        return response()->json([
            'unread_count' => $unreadNotifications->count(),
            'notifications' => $notifications,
        ]);
    }
}