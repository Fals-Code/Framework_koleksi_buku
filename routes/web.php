<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\ProfileController;
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

Route::get('/register', [App\Http\Controllers\Auth\RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [App\Http\Controllers\Auth\RegisterController::class, 'register']);

Route::middleware(['auth', 'check.session'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    Route::resource('kategori', KategoriController::class);
    Route::resource('buku', BukuController::class);
    Route::get('/buku-cetak-label', [BukuController::class, 'cetakLabel'])->name('buku.cetak_label');
    Route::delete('/buku/bulk-delete', [BukuController::class, 'bulkDelete'])->name('buku.bulkDelete');

    Route::resource('barang', BarangController::class);
    Route::post('/barang/cetak', [BarangController::class, 'cetakLabel'])->name('barang.cetak');
    Route::put('/barang/{id}', [BarangController::class, 'update'])->name('barang.update');
    Route::delete('/barang/{id}', [BarangController::class, 'destroy'])->name('barang.destroy');
    Route::get('/barang-tabel-html', [BarangController::class, 'tabelHtml'])->name('barang.tabel_html');
    Route::get('/warehouse-system', [BarangController::class, 'latihan'])->name('latihan.index');
    
    Route::get('/get-next-kode/{idkategori}', [BukuController::class, 'getNextKode']);

    Route::post('/notifications/clear', [HomeController::class, 'clearAll'])->name('notifications.clear');
    Route::post('/notifications/{id}/read', [HomeController::class, 'markAsRead']);
    Route::get('/notifications/{id}/read', [NotificationController::class, 'read'])->name('notifications.read');
    Route::get('/notifications/clear-all', [NotificationController::class, 'clearAll'])->name('notifications.clear_all');
    
    Route::get('/laporan-buku', [PDFController::class, 'cetakLaporanBuku'])->name('laporan.buku');
    Route::get('/label-buku', [PDFController::class, 'cetakLabelBuku'])->name('laporan.label');

    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
});