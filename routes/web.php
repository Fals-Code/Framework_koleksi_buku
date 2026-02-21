<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () { 
    return view('auth.login'); 
});

Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.login');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

Route::get('auth/otp', function () {
    if (!session()->has('otp_user_id')) return redirect('/');
    return view('auth.otp_verify');
})->name('otp.view');

Route::post('auth/otp/verify', [GoogleController::class, 'verifyOtp'])->name('otp.verify');

Auth::routes();

Route::middleware(['auth', 'check.session'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    
    Route::resource('kategori', KategoriController::class);
    Route::resource('buku', BukuController::class);
    
    Route::get('/get-next-kode/{idkategori}', [BukuController::class, 'getNextKode']);
    
    Route::get('/notifications/{id}/read', [NotificationController::class, 'read'])->name('notifications.read');
    Route::get('/notifications/clear', [NotificationController::class, 'clearAll'])->name('notifications.clear');
    
    Route::get('/cetak-sertifikat', [PDFController::class, 'cetakSertifikat'])->name('cetak.sertifikat');
    Route::get('/cetak-undangan', [PDFController::class, 'cetakUndangan'])->name('cetak.undangan');
});