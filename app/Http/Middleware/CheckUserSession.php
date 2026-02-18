<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserSession
{
    public function handle(Request $request, Closure $next): Response
    {
        // Pengecekan: Apakah user sudah login DAN session custom ada?
        if (Auth::check() && $request->session()->has('custom_user_data')) {
            return $next($request);
        }

        // Jika tidak ada session, paksa logout dan kembali ke login
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('error', 'Session tidak valid, silahkan login kembali.');
    }
}
