<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     * Method ini otomatis dipanggil Laravel setelah login berhasil
     */
    protected function authenticated(Request $request, $user)
    {
        // Simpan data custom ke session (jika diperlukan)
        session(['custom_user_data' => [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => 'admin' 
        ]]);

        // TAMBAHKAN ->with('success', ...) di sini agar SweetAlert muncul
        return redirect()->intended($this->redirectPath())
            ->with('success', 'Selamat Datang, ' . $user->name . '! Anda berhasil masuk ke sistem.');
    }

    /**
     * Override method logout untuk memberikan notifikasi saat keluar
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Anda telah berhasil keluar dari sistem.');
    }
}