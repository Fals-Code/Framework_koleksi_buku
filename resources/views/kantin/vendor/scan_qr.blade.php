@extends('layouts.app')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;800&display=swap" rel="stylesheet">
<style>
    :root {
        --scan-primary: #2575fc;
        --scan-accent: #6a11cb;
        --outfit: 'Outfit', sans-serif;
    }
    body { font-family: var(--outfit); }

    .scan-container {
        max-width: 1100px;
        margin: 0 auto;
    }

    .vendor-card-premium {
        border: none;
        border-radius: 35px;
        box-shadow: 0 15px 50px rgba(0,0,0,0.08);
        background: white;
        overflow: hidden;
    }

    .scanner-wrapper {
        background: #000;
        border-radius: 25px;
        position: relative;
        overflow: hidden;
        border: 4px solid #f8f9fa;
        aspect-ratio: 1/1;
    }
    
    /* Scanner Overlay Frame */
    .scan-overlay {
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        border: 40px solid rgba(0,0,0,0.3);
        z-index: 10;
        pointer-events: none;
    }
    .scan-frame {
        position: absolute;
        top: 15%; left: 15%; right: 15%; bottom: 15%;
        border: 2px solid rgba(255, 255, 255, 0.5);
        border-radius: 20px;
        box-shadow: 0 0 0 1000px rgba(0,0,0,0.5);
    }
    .scan-line {
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 3px;
        background: var(--scan-primary);
        box-shadow: 0 0 15px var(--scan-primary);
        animation: scanLineMove 2.5s linear infinite;
    }
    @keyframes scanLineMove {
        0% { top: 0%; }
        50% { top: 100%; }
        100% { top: 0%; }
    }

    .result-card {
        display: none;
        animation: slideUp 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    @keyframes slideUp {
        from { transform: translateY(30px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }

    .status-badge {
        padding: 8px 20px;
        border-radius: 50px;
        font-weight: 800;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    .status-lunas { background: #e6f4ea; color: #1e8e3e; }
    .status-pending { background: #fff8e1; color: #ff8f00; }

    #reader { width: 100% !important; border: none !important; }
    #reader video { border-radius: 20px !important; object-fit: cover !important; }
</style>

<div class="scan-container">
    <div class="row g-4 align-items-stretch">
        <!-- Left: Scanner Area -->
        <div class="col-lg-5">
            <div class="card vendor-card-premium h-100">
                <div class="card-body p-5 text-center">
                    <div class="mb-4">
                        <h4 class="fw-bold text-dark">Validasi QR</h4>
                        <p class="text-muted small">Scan QR Code pesanan untuk melihat detail menu.</p>
                    </div>

                    <div class="scanner-wrapper mb-4 shadow-lg">
                        <div id="reader"></div>
                        <div class="scan-overlay" id="scanOverlay">
                            <div class="scan-frame">
                                <div class="scan-line"></div>
                            </div>
                        </div>
                    </div>

                    <div id="controls" style="display: none;">
                        <button class="btn btn-primary btn-lg w-100 rounded-pill py-3 fw-bold shadow" onclick="resetScanner()">
                            <i class="mdi mdi-refresh me-2"></i> SCAN LAGI
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Result Area -->
        <div class="col-lg-7">
            <!-- Placeholder -->
            <div class="card vendor-card-premium h-100 border-dashed bg-light" id="placeholder">
                <div class="card-body p-5 d-flex flex-column align-items-center justify-content-center text-center opacity-50">
                    <div class="rounded-circle bg-white p-4 mb-3">
                        <i class="mdi mdi-qrcode-scan text-muted" style="font-size: 50px;"></i>
                    </div>
                    <h5 class="fw-bold">Menunggu Pemindaian</h5>
                    <p class="text-muted small">Arahkan kamera ke QR Code pelanggan.</p>
                </div>
            </div>

            <!-- Result Display -->
            <div class="card vendor-card-premium h-100 result-card" id="resultCard">
                <div class="card-body p-5">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h6 class="text-muted small fw-bold mb-1 uppercase tracking-wider">DETAIL PESANAN</h6>
                            <h2 class="fw-bold text-dark mb-0" id="resOrderNo">ORD-XXXX</h2>
                        </div>
                        <span id="resStatus" class="status-badge">STATUS</span>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded-4 border">
                                <label class="x-small text-muted d-block fw-bold mb-1 uppercase">PELANGGAN</label>
                                <span class="fw-bold text-dark fs-5" id="resCustomer">-</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded-4 border">
                                <label class="x-small text-muted d-block fw-bold mb-1 uppercase">TOTAL BAYAR</label>
                                <span class="fw-bold text-primary fs-5" id="resTotal">Rp 0</span>
                            </div>
                        </div>
                    </div>

                    <h6 class="fw-bold mb-3">MENU YANG DIPESAN:</h6>
                    <div class="table-responsive rounded-4 border mb-4">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-0">Nama Menu</th>
                                    <th class="border-0 text-center">Qty</th>
                                </tr>
                            </thead>
                            <tbody id="resItems">
                                <!-- JS items -->
                            </tbody>
                        </table>
                    </div>

                    <div class="p-3 rounded-4" style="background: #fff9db; border: 1px solid #ffe066;">
                        <div class="d-flex">
                            <i class="mdi mdi-information-outline text-warning fs-4 me-3"></i>
                            <p class="small mb-0 text-dark">
                                <strong>Catatan:</strong> Segera siapkan menu di atas jika status sudah <span class="fw-bold text-success">LUNAS</span>.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<audio id="beep" src="{{ asset('assets/beep.mp3') }}" preload="auto"></audio>

<script src="https://unpkg.com/html5-qrcode"></script>
@endsection

@push('script-page')
<script>
    let html5QrcodeScanner = null;
    const beep = document.getElementById('beep');

    function onScanSuccess(decodedText) {
        beep.play();
        
        // Stop Scanner UI
        html5QrcodeScanner.clear().then(_ => {
            $('#controls').fadeIn();
            $('#scanOverlay').fadeOut();
        });

        // Show Loading
        Swal.fire({
            title: 'Memproses QR...',
            didOpen: () => { Swal.showLoading(); }
        });

        // Fetch Order
        fetch(`/vendor/api/order-detail/${decodedText}`)
            .then(res => res.json())
            .then(res => {
                Swal.close();
                if (res.status === 'success') {
                    const data = res.data;
                    $('#resOrderNo').text(data.nomor_pesanan);
                    $('#resCustomer').text(data.nama_pelanggan);
                    $('#resTotal').text('Rp ' + data.total_harga);
                    
                    $('#resStatus').text(data.status_label).attr('class', 'status-badge status-' + data.status);

                    let items = '';
                    data.items.forEach(i => {
                        items += `<tr>
                            <td class="fw-bold text-dark py-3">${i.nama}</td>
                            <td class="text-center py-3"><span class="badge bg-primary rounded-pill px-3">${i.qty} x</span></td>
                        </tr>`;
                    });
                    $('#resItems').html(items);

                    $('#placeholder').hide();
                    $('#resultCard').fadeIn();
                } else {
                    Swal.fire('Gagal', res.message, 'error');
                    resetScanner();
                }
            })
            .catch(err => {
                Swal.fire('Error', 'Gagal menghubungi server.', 'error');
                resetScanner();
            });
    }

    function initScanner() {
        $('#scanOverlay').show();
        html5QrcodeScanner = new Html5QrcodeScanner("reader", { 
            fps: 10, 
            qrbox: { width: 250, height: 250 },
            supportedScanTypes: [Html5QrcodeScanType.SCAN_TYPE_CAMERA]
        });
        html5QrcodeScanner.render(onScanSuccess);
    }

    function resetScanner() {
        $('#resultCard').hide();
        $('#placeholder').show();
        $('#controls').hide();
        initScanner();
    }

    $(document).ready(function() {
        initScanner();
    });
</script>
@endpush
