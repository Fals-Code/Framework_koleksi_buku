@extends('layouts.app')

@section('content')
<style>
    .scan-card {
        border: none;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        overflow: hidden;
    }
    .scanner-wrapper {
        position: relative;
        background: #f8f9fa;
        padding: 20px;
        border-radius: 15px;
    }
    #reader {
        width: 100%;
        border: none !important;
        border-radius: 15px;
        overflow: hidden;
    }
    #reader __video {
        border-radius: 15px;
    }
    .result-card {
        display: none;
        animation: slideUp 0.5s ease-out;
    }
    @keyframes slideUp {
        from { transform: translateY(20px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
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
            </span> Praktikum 1: Scan Barcode
        </h3>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card scan-card">
            <div class="card-body p-4 text-center">
                <h5 class="fw-bold mb-3">Scanner Kamera</h5>
                <p class="text-muted small mb-4">Arahkan barcode ke kotak di bawah untuk memproses data barang.</p>
                
                <div class="scanner-wrapper mb-4">
                    <div id="reader"></div>
                </div>

                <div class="d-grid gap-2">
                    <button class="btn btn-outline-primary rounded-pill fw-bold" onclick="restartScanner()" id="btnRestart" style="display: none;">
                        <i class="mdi mdi-refresh me-2"></i> Scan Ulang
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Result Area -->
    <div class="col-md-6 grid-margin stretch-card result-card" id="resultArea">
        <div class="card scan-card border-top border-primary border-4">
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
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-muted small">Status: Terverifikasi</span>
                    <button class="btn btn-gradient-primary rounded-pill px-4 fw-bold" onclick="restartScanner()">
                        SELESAI
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Audio for Beep -->
<audio id="beepSound" src="{{ asset('assets/beep.mp3') }}" preload="auto"></audio>

@endsection

@push('script-page')
<script>
    let html5QrcodeScanner = null;
    const beep = document.getElementById('beepSound');

    function onScanSuccess(decodedText, decodedResult) {
        // 1. Putar suara beep
        beep.play();

        // 2. Hentikan scanning
        html5QrcodeScanner.clear().then(_ => {
            console.log("Scanner stopped.");
            document.getElementById('btnRestart').style.display = 'block';
        }).catch(error => {
            console.warn("Failed to clear scanner: ", error);
        });

        // 3. Request data ke server
        fetchDataBarang(decodedText);
    }

    function onScanFailure(error) {
        // Abaikan error scanning yang bersifat sementara
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
                    
                    // Tampilkan Hasil
                    document.getElementById('resID').innerText = data.barcode || data.id_barang;
                    document.getElementById('resNama').innerText = data.nama;
                    document.getElementById('resHarga').innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(data.harga);
                    
                    document.getElementById('resultArea').style.display = 'block';
                    
                    // Notifikasi sukses
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                    });
                    Toast.fire({ icon: 'success', title: 'Data berhasil dimuat' });

                } else {
                    Swal.fire('Gagal', res.message, 'error');
                    restartScanner();
                }
            })
            .catch(error => {
                Swal.fire('Tidak Ditemukan', 'Kode barang ' + id + ' tidak terdaftar di sistem.', 'warning');
                document.getElementById('btnRestart').style.display = 'block';
            });
    }

    function initScanner() {
        html5QrcodeScanner = new Html5QrcodeScanner(
            "reader", 
            { 
                fps: 10, 
                qrbox: { width: 250, height: 250 },
                rememberLastUsedCamera: true,
                supportedScanTypes: [Html5QrcodeScanType.SCAN_TYPE_CAMERA]
            },
            /* verbose= */ false
        );
        html5QrcodeScanner.render(onScanSuccess, onScanFailure);
    }

    function restartScanner() {
        document.getElementById('resultArea').style.display = 'none';
        document.getElementById('btnRestart').style.display = 'none';
        if (html5QrcodeScanner) {
            html5QrcodeScanner.clear();
        }
        initScanner();
    }

    // Jalankan scanner saat halaman siap
    $(document).ready(function() {
        initScanner();
    });
</script>
@endpush
