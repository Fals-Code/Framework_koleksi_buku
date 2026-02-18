<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::middleware(['auth', 'check.session'])->group(function () {
    
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    Route::resource('kategori', KategoriController::class);

    Route::resource('buku', BukuController::class);
});