<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
protected function authenticated(Request $request, $user)
{
    session(['custom_user_data' => [
        'id' => $user->id,
        'name' => $user->name,
        'email' => $user->email,
        'role' => 'admin' // Contoh penambahan data custom
    ]]);

    return redirect()->intended($this->redirectPath());
}

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }
}
