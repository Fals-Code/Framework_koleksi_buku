<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Notifications\SystemNotification;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        $needsPassword = is_null($user->password);
        
        return view('profile.index', compact('user', 'needsPassword'));
    }

    public function update(Request $request)
    {
        $user = User::findOrFail(Auth::id());
        
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        ];

        if (is_null($user->password) || $request->filled('password')) {
            $rules['password'] = 'required|string|min:8|confirmed';
        }

        $request->validate($rules);

        $user->name = $request->name;
        $user->email = $request->email;

        $isNewPassword = false;
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
            $isNewPassword = true;
        }

        $user->save();

        auth()->user()->notify(new SystemNotification([
            'title' => 'Update Profil',
            'message' => $isNewPassword 
                ? 'Profil dan password Anda berhasil diperbarui.' 
                : 'Informasi profil Anda berhasil diperbarui.',
            'link' => route('profile.index'),
            'type' => 'success'
        ]));

        return redirect()->route('profile.index')->with('success', 'Profil berhasil diperbarui!');
    }
}