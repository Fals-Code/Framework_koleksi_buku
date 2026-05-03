@extends('layouts.app')

@section('content')
<style>
    .scan-container {
        max-width: 600px;
        margin: 0 auto;
    }
    .scanner-card {
        border: none;
        border-radius: 25px;
        box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        background: #fff;
        overflow: hidden;
    }
    .scanner-view {
        position: relative;
        width: 100%;
        aspect-ratio: 4/3;
        background: #1a1a1a;
        border-radius: 20px;
        overflow: hidden;
    }
    #reader {
        width: 100%;
        height: 100%;
    }
    #reader video {
        object-fit: cover !important;
    }
    .scan-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        border: 2px solid rgba(182, 109, 255, 0.3);
        box-shadow: 0 0 0 500px rgba(0,0,0,0.5);
        z-index: 10;
        pointer-events: none;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .scan-region {
        width: 250px;
        height: 180px;
        border: 2px solid #b66dff;
        border-radius: 15px;
        position: relative;
        box-shadow: 0 0 20px rgba(182, 109, 255, 0.4);
    }
    .scan-region::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 2px;
        background: #b66dff;
        box-shadow: 0 0 15px #b66dff;
        animation: scanAnim 2s infinite ease-in-out;
    }
    @keyframes scanAnim {
        0%, 100% { top: 0%; }
        50% { top: 100%; }
    }
    .permission-error {
        display: none;
        padding: 40px 20px;
        text-align: center;
    }
    .item-id {
        background: rgba(182, 109, 255, 0.1);
        color: #b66dff;
        padding: 5px 15px;
        border-radius: 10px;
        font-weight: bold;
        font-family: 'JetBrains Mono', monospace;
    }
</style>

<div class="page-header flex-wrap">
    <div class="header-left">
        <h3 class="page-title text-dark fw-bold">
            <span class="page-title-icon bg-gradient-primary text-white me-2 shadow-sm">
                <i class="mdi mdi-barcode-scan"></i>
            </span> Scan Barcode Barang
        </h3>
    </div>
</div>

<div class="scan-container">
    <div class="card scanner-card mb-4">
        <div class="card-body p-4">
            <!-- Normal Scanner View -->
            <div id="scannerWrapper">
                <div class="text-center mb-4">
                    <h5 class="fw-bold mb-1">Arahkan Barcode</h5>
                    <p class="text-muted small">Posisikan barcode barang di dalam kotak ungu untuk scan otomatis.</p>
                </div>

                <div class="scanner-view mb-4" id="viewContainer">
                    <div id="reader"></div>
                    <div class="scan-overlay">
                        <div class="scan-region"></div>
                    </div>
                </div>

                <div class="d-flex justify-content-center gap-2">
                    <button class="btn btn-outline-primary rounded-pill px-4" onclick="toggleScanner()" id="btnToggle">
                        <i class="mdi mdi-camera-off me-2"></i> Matikan Kamera
                    </button>
                </div>
            </div>

            <!-- Error View -->
            <div id="errorWrapper" class="permission-error">
                <div class="mb-4">
                    <i class="mdi mdi-camera-off text-danger" style="font-size: 60px;"></i>
                </div>
                <h4 class="fw-bold text-dark">Kamera Tidak Diakses</h4>
                <p class="text-muted mb-4" id="errorMessage">Gagal mengakses kamera. Pastikan izin kamera telah diberikan di pengaturan browser atau sistem Anda.</p>
                
                <div class="alert alert-info small text-start mb-4">
                    <strong>Tips Perbaikan:</strong>
                    <ul class="mb-0 mt-2">
                        <li>Klik ikon gembok di sebelah alamat website (URL) dan pilih "Allow Camera".</li>
                        <li>Pastikan tidak ada aplikasi lain (seperti Zoom/Teams) yang sedang menggunakan kamera.</li>
                        <li>Jika menggunakan HP, pastikan izin kamera di Pengaturan Aplikasi sudah aktif.</li>
                    </ul>
                </div>

                <button class="btn btn-gradient-primary rounded-pill px-5 py-3 fw-bold" onclick="startScanner()">
                    <i class="mdi mdi-refresh me-2"></i> COBA LAGI
                </button>
            </div>
        </div>
    </div>

    <!-- Result Area -->
    <div class="card scanner-card border-top border-primary border-4" id="resultArea" style="display: none;">
        <div class="card-body p-5">
            <div class="text-center mb-4">
                <div class="bg-light d-inline-block p-4 rounded-circle mb-3">
                    <i class="mdi mdi-package-variant-closed text-primary" style="font-size: 50px;"></i>
                </div>
                <h4 class="fw-bold text-dark">Data Barang Ditemukan</h4>
            </div>

            <div class="table-responsive">
                <table class="table table-borderless">
                    <tr>
                        <td class="text-muted small fw-bold py-3">REF ID / KODE</td>
                        <td class="text-end py-3"><span id="resID" class="item-id"></span></td>
                    </tr>
                    <tr>
                        <td class="text-muted small fw-bold py-3">NAMA PRODUK</td>
                        <td class="text-end py-3 fw-bold fs-5" id="resNama"></td>
                    </tr>
                    <tr>
                        <td class="text-muted small fw-bold py-3">HARGA SATUAN</td>
                        <td class="text-end py-3 text-success fw-bold fs-4" id="resHarga"></td>
                    </tr>
                </table>
            </div>

            <hr class="my-4">
            <div class="d-flex justify-content-center">
                <button class="btn btn-gradient-primary rounded-pill px-5 py-3 fw-bold" onclick="resumeScanner()">
                    SCAN BARANG LAIN
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Audio for Beep -->
<audio id="beepSound" src="{{ asset('assets/beep.mp3') }}" preload="auto"></audio>

@endsection

@push('script-page')
<script>
    let html5QrCode = null;
    let isScanning = false;
    const beep = document.getElementById('beepSound');

    function startScanner() {
        if (!html5QrCode) {
            html5QrCode = new Html5Qrcode("reader");
        }

        const config = { fps: 10, qrbox: { width: 250, height: 180 } };

        $('#errorWrapper').hide();
        $('#scannerWrapper').show();
        $('#btnToggle').html('<i class="mdi mdi-camera-off me-2"></i> Matikan Kamera');

        html5QrCode.start(
            { facingMode: "environment" }, 
            config, 
            onScanSuccess
        ).then(() => {
            isScanning = true;
        }).catch(err => {
            console.error("Camera access error:", err);
            handleScannerError(err);
        });
    }

    function handleScannerError(err) {
        $('#scannerWrapper').hide();
        $('#errorWrapper').show();
        
        let message = "Gagal mengakses kamera.";
        if (err.name === 'NotAllowedError') {
            message = "Izin kamera ditolak oleh sistem atau browser. Silakan aktifkan izin kamera untuk situs ini.";
        } else if (err.name === 'NotFoundError') {
            message = "Kamera tidak ditemukan pada perangkat Anda.";
        } else if (location.protocol !== 'https:' && location.hostname !== 'localhost') {
            message = "Fitur kamera memerlukan koneksi aman (HTTPS). Silakan akses melalui HTTPS.";
        }
        
        $('#errorMessage').text(message);
    }

    function onScanSuccess(decodedText) {
        if (!isScanning) return;
        
        isScanning = false;
        beep.play();
        
        // Pause scanner but keep UI
        html5QrCode.pause();
        
        fetchDataBarang(decodedText);
    }

    function fetchDataBarang(id) {
        Swal.fire({
            title: 'Memproses...',
            text: 'Mencari data barang ' + id,
            allowOutsideClick: false,
            didOpen: () => { Swal.showLoading(); }
        });

        fetch(`/kasir/cari-barang/${id}`)
            .then(response => {
                if (!response.ok) throw new Error('Barang tidak ditemukan');
                return response.json();
            })
            .then(res => {
                Swal.close();
                if (res.status === 'success') {
                    const data = res.data;
                    document.getElementById('resID').innerText = data.barcode || data.id_barang;
                    document.getElementById('resNama').innerText = data.nama;
                    document.getElementById('resHarga').innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(data.harga);
                    
                    $('#resultArea').slideDown();
                    window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' });
                } else {
                    Swal.fire('Gagal', res.message, 'error');
                    resumeScanner();
                }
            })
            .catch(error => {
                Swal.fire('Tidak Ditemukan', 'Kode barang ' + id + ' tidak terdaftar.', 'warning');
                resumeScanner();
            });
    }

    function resumeScanner() {
        $('#resultArea').slideUp();
        if (html5QrCode) {
            html5QrCode.resume();
            isScanning = true;
        }
    }

    function toggleScanner() {
        if (isScanning) {
            html5QrCode.stop().then(() => {
                isScanning = false;
                $('#btnToggle').html('<i class="mdi mdi-camera me-2"></i> Aktifkan Kamera');
                $('#viewContainer').css('opacity', '0.5');
            });
        } else {
            startScanner();
            $('#viewContainer').css('opacity', '1');
        }
    }

    $(document).ready(function() {
        // Delay slighty to ensure DOM is ready
        setTimeout(startScanner, 500);
    });
</script>
@endpush
