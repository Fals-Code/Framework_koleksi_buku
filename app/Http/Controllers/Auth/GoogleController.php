<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Google\Client as GoogleClient;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        $client = new GoogleClient();
        $client->setClientId(env('GOOGLE_CLIENT_ID'));
        $client->setClientSecret(env('GOOGLE_CLIENT_SECRET'));
        $client->setRedirectUri(env('GOOGLE_REDIRECT_URL'));
        $client->addScope("email");
        $client->addScope("profile");

        return redirect()->away($client->createAuthUrl());
    }

    public function handleGoogleCallback(Request $request)
    {
        $client = new GoogleClient();
        $client->setClientId(env('GOOGLE_CLIENT_ID'));
        $client->setClientSecret(env('GOOGLE_CLIENT_SECRET'));
        $client->setRedirectUri(env('GOOGLE_REDIRECT_URL'));

        $token = $client->fetchAccessTokenWithAuthCode($request->code);
        $client->setAccessToken($token);
        
        $googleService = new \Google\Service\Oauth2($client);
        $google_user = $googleService->userinfo->get();

        $user = User::where('email', $google_user->email)->first();

        if (!$user) {
            $user = User::create([
                'name'      => $google_user->name,
                'email'     => $google_user->email,
                'id_google' => $google_user->id,
            ]);
        }

        $otp_code = strtoupper(substr(str_shuffle("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 6));
        $user->update(['otp' => $otp_code]);

        Mail::send([], [], function ($message) use ($user, $otp_code) {
            $message->to($user->email)
                ->subject('🔐 Kode Verifikasi OTP - Koleksi Buku')
                ->html("
                    <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #e3e3e3; border-radius: 15px;'>
                        <div style='text-align: center; margin-bottom: 20px;'>
                            <h1 style='color: #7117ea; margin: 0;'>Koleksi Buku</h1>
                            <p style='color: #888; font-size: 14px;'>Sistem Manajemen Buku Digital</p>
                        </div>
                        <div style='background: #f9f9f9; padding: 30px; border-radius: 10px; text-align: center;'>
                            <h2 style='color: #333; margin-top: 0;'>Kode Verifikasi Anda</h2>
                            <p style='color: #555; font-size: 16px;'>Gunakan kode di bawah ini untuk menyelesaikan proses login Anda:</p>
                            <div style='background: #ffffff; border: 2px dashed #7117ea; display: inline-block; padding: 15px 40px; margin: 20px 0;'>
                                <span style='font-size: 32px; font-weight: bold; letter-spacing: 10px; color: #7117ea;'>{$otp_code}</span>
                            </div>
                            <p style='color: #888; font-size: 13px; margin-top: 20px;'>
                                Kode ini berlaku selama 5 menit. <br> 
                                Mohon jangan berikan kode ini kepada siapapun demi keamanan akun Anda.
                            </p>
                        </div>
                        <div style='text-align: center; margin-top: 25px; color: #aaa; font-size: 12px;'>
                            <p>&copy; 2026 Koleksi Buku Team. <br> Surabaya, Jawa Timur, Indonesia.</p>
                        </div>
                    </div>
                ");
        });

        session(['otp_user_id' => $user->id]);
        return redirect()->route('otp.view');
    }

    public function verifyOtp(Request $request)
    {
        $otp_input = strtoupper($request->otp_input);
        $user = User::find(session('otp_user_id'));

        if ($user && $otp_input === $user->otp) {
            Auth::login($user);
            $user->update(['otp' => null]);
            session()->forget('otp_user_id');
            $request->session()->regenerate();

            $this->addNotification('Login Berhasil', 'Selamat Datang, ' . $user->name . '! Anda berhasil masuk ke sistem.');

            return redirect()->intended('/home');
        }

        return back()->with('error', 'Kode OTP salah!');
    }

    public function logout(Request $request)
    {
        $user_name = Auth::user()->name ?? 'User';
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        $this->addNotification('Logout Berhasil', 'Terima kasih ' . $user_name . ', Anda telah keluar dengan aman.');

        return redirect('/');
    }

    private function addNotification($title, $message)
    {
        $notifications = session()->get('notifications', []);
        array_unshift($notifications, [
            'title' => $title,
            'message' => $message,
            'time' => now()->format('H:i'),
            'unread' => true
        ]);
        session()->put('notifications', array_slice($notifications, 0, 5));
    }
}