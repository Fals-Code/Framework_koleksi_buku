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
}