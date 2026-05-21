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
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\KantinController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ScanController;
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

    Route::get('/kasir', [PenjualanController::class, 'index'])->name('kasir.index');
    Route::get('/kasir/cari-barang/{kode}', [PenjualanController::class, 'cariBarang'])->name('kasir.cari');
    Route::get('/kasir/search-barang', [PenjualanController::class, 'searchBarang'])->name('kasir.search');
    Route::post('/kasir/simpan', [PenjualanController::class, 'simpan'])->name('kasir.simpan');
    
    Route::get('/get-next-kode/{idkategori}', [BukuController::class, 'getNextKode']);

    Route::post('/notifications/clear', [HomeController::class, 'clearAll'])->name('notifications.clear');
    Route::post('/notifications/{id}/read', [HomeController::class, 'markAsRead']);
    Route::get('/notifications/{id}/read', [NotificationController::class, 'read'])->name('notifications.read');
    Route::get('/notifications/clear-all', [NotificationController::class, 'clearAll'])->name('notifications.clear_all');
    Route::get('/notifications/latest', [NotificationController::class, 'getLatest'])->name('notifications.latest');
    
    Route::get('/laporan-buku', [PDFController::class, 'cetakLaporanBuku'])->name('laporan.buku');
    Route::get('/label-buku', [PDFController::class, 'cetakLabelBuku'])->name('laporan.label');

    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

    // Vendor Management (Internal Tabs)
    Route::prefix('vendor')->group(function () {
        Route::get('/scan-qr', [VendorController::class, 'scanQR'])->name('vendor.scan_qr');
        Route::get('/api/order-detail/{id}', [VendorController::class, 'getOrderDetail'])->name('vendor.api.order_detail');
        Route::get('/dashboard', [VendorController::class, 'dashboard'])->name('vendor.dashboard');
        Route::get('/menu', [VendorController::class, 'menuIndex'])->name('vendor.menu.index');
        Route::get('/menu/create', [VendorController::class, 'menuCreate'])->name('vendor.menu.create');
        Route::post('/menu', [VendorController::class, 'menuStore'])->name('vendor.menu.store');
        Route::get('/menu/{id}/edit', [VendorController::class, 'menuEdit'])->name('vendor.menu.edit');
        Route::put('/menu/{id}', [VendorController::class, 'menuUpdate'])->name('vendor.menu.update');
        Route::delete('/menu/{id}', [VendorController::class, 'menuDestroy'])->name('vendor.menu.destroy');
        Route::get('/orders', [VendorController::class, 'orders'])->name('vendor.orders');
        Route::get('/api/orders/count', [VendorController::class, 'getNewOrdersCount'])->name('vendor.api.orders.count');
        Route::post('/orders/{id}/status', [VendorController::class, 'orderUpdateStatus'])->name('vendor.order.status');
    });

    // Customer Routes
    Route::get('/customer', [CustomerController::class, 'index'])->name('customer.index');
    Route::get('/customer/tambah1', [CustomerController::class, 'create1'])->name('customer.create1');
    Route::post('/customer/tambah1', [CustomerController::class, 'store1'])->name('customer.store1');
    Route::get('/customer/tambah2', [CustomerController::class, 'create2'])->name('customer.create2');
    Route::post('/customer/tambah2', [CustomerController::class, 'store2'])->name('customer.store2');
    Route::get('/customer/foto-blob/{id}', [CustomerController::class, 'showBlob'])->name('customer.blob');

    // Scan Routes
    Route::get('/scan-barcode', [ScanController::class, 'barcode'])->name('scan.barcode');

    // NFC Scanner Routes
    Route::prefix('nfc')->group(function () {
        Route::get('/', [App\Http\Controllers\NfcController::class, 'index'])->name('nfc.index');
        Route::get('/write', [App\Http\Controllers\NfcController::class, 'writeCard'])->name('nfc.write');
        Route::get('/cards', [App\Http\Controllers\NfcController::class, 'cards'])->name('nfc.cards');
        Route::post('/cards', [App\Http\Controllers\NfcController::class, 'storeCard'])->name('nfc.cards.store')->middleware('throttle:10,1');
        
        // API Routes dengan Rate Limiting untuk mencegah spam tap NFC berlebihan
        Route::middleware('throttle:30,1')->group(function () {
            Route::post('/lookup', [App\Http\Controllers\NfcController::class, 'lookupCard'])->name('nfc.lookup');
            Route::post('/peminjaman', [App\Http\Controllers\NfcController::class, 'storePeminjaman'])->name('nfc.peminjaman');
            Route::post('/pengembalian', [App\Http\Controllers\NfcController::class, 'storePengembalian'])->name('nfc.pengembalian');
            Route::post('/kunjungan', [App\Http\Controllers\NfcController::class, 'storeKunjungan'])->name('nfc.kunjungan');
        });
        
        Route::get('/history', [App\Http\Controllers\NfcController::class, 'history'])->name('nfc.history');
    });
});

    Route::get('/kantin', [KantinController::class, 'index'])->name('kantin.index');
    Route::get('/kantin/history', [KantinController::class, 'history'])->name('kantin.history');
    Route::post('/kantin/checkout', [KantinController::class, 'checkout'])->name('kantin.checkout');
Route::get('/kantin/status/{id}', [KantinController::class, 'status'])->name('kantin.status');
Route::get('/kantin/success/{id}', [KantinController::class, 'orderSuccess'])->name('kantin.success');
Route::get('/kantin/receipt/{id}', [KantinController::class, 'receipt'])->name('kantin.receipt');
Route::get('/kantin/track/{id}', [KantinController::class, 'track'])->name('kantin.track');
Route::post('/midtrans/callback', [KantinController::class, 'callback']);