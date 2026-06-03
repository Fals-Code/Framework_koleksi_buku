@extends('layouts.app')

@push('style-page')
<style>
    .nfc-container {
        max-width: 800px;
        margin: 0 auto;
    }
    .nfc-card {
        border: none;
        border-radius: 25px;
        box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        background: #fff;
        overflow: hidden;
    }
    .nfc-animation {
        width: 150px;
        height: 150px;
        background: rgba(182, 109, 255, 0.1);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        position: relative;
    }
    .nfc-animation i {
        font-size: 80px;
        color: #b66dff;
        z-index: 2;
    }
    .nfc-pulse {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        border-radius: 50%;
        border: 2px solid #b66dff;
        animation: pulseAnim 2s infinite ease-out;
    }
    @keyframes pulseAnim {
        0% { transform: scale(0.9); opacity: 1; }
        100% { transform: scale(1.5); opacity: 0; }
    }
    .status-box {
        background: #f8f9fa;
        border-radius: 15px;
        padding: 20px;
        margin-top: 20px;
        border: 1px solid #eee;
    }
    .nav-pills .nav-link {
        border-radius: 50px;
        padding: 10px 25px;
        font-weight: bold;
        color: #6c757d;
        background: #f8f9fa;
        margin: 0 5px;
    }
    .nav-pills .nav-link.active {
        background: var(--primary-gradient);
        color: #fff;
        box-shadow: 0 5px 15px rgba(182, 109, 255, 0.3);
    }
</style>
@endpush

@section('content')
<div class="page-header flex-wrap">
    <div class="header-left">
        <h3 class="page-title text-dark fw-bold">
            <span class="page-title-icon bg-gradient-primary text-white me-2 shadow-sm">
                <i class="mdi mdi-nfc-variant"></i>
            </span> NFC Scanner Absensi Mahasiswa
        </h3>
    </div>
</div>

<div class="nfc-container">
    <div class="card nfc-card mb-4">
        <div class="card-body p-4 p-md-5">
            
            <!-- Fallback Alert -->
            <div id="nfcFallback" class="alert alert-warning" style="display: none; border-radius: 15px;">
                <h5><i class="mdi mdi-alert-circle"></i> Browser Tidak Mendukung NFC</h5>
                <p class="mb-0">Fitur NFC Scanner memerlukan browser Android Chrome dan koneksi yang aman (HTTPS/localhost).</p>
            </div>

            <!-- Tabs -->
            <ul class="nav nav-pills justify-content-center mb-5" id="nfcTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="pinjam-tab" data-bs-toggle="pill" data-bs-target="#pinjam" type="button" role="tab">Peminjaman</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="kembali-tab" data-bs-toggle="pill" data-bs-target="#kembali" type="button" role="tab">Pengembalian</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="absen-tab" data-bs-toggle="pill" data-bs-target="#absen" type="button" role="tab">Absensi</button>
                </li>
            </ul>

            <!-- Scanner Area -->
            <div id="scannerArea" class="text-center">
                <h4 class="fw-bold mb-1" id="modeTitle">Mode Peminjaman</h4>
                <p class="text-muted small mb-4" id="modeDesc">Dekatkan kartu NFC anggota ke area sensor NFC di belakang HP.</p>

                <div class="nfc-animation">
                    <i class="mdi mdi-cellphone-nfc"></i>
                    <div class="nfc-pulse"></div>
                </div>

                <div class="status-box">
                    <h5 class="fw-bold text-dark mb-1">Status Scanner</h5>
                    <p class="mb-0" id="scanStatus">
                        <span class="text-warning"><i class="mdi mdi-circle-medium"></i> Menunggu kartu...</span>
                    </p>
                </div>
                
                <div class="mt-4">
                    <button class="btn btn-gradient-primary rounded-pill px-5 fw-bold" id="btnStartScan" onclick="startNfcScan()">
                        <i class="mdi mdi-play-circle-outline me-2"></i> Mulai Scan
                    </button>
                    <button class="btn btn-outline-secondary rounded-pill px-4 fw-bold ms-3" id="btnQuickRead" onclick="quickReadUid()">
                        <i class="mdi mdi-magnify-scan me-2"></i> Quick Read UID
                    </button>
                </div>
            </div>

            <!-- Result Placeholder -->
            <div id="resultArea" style="display: none;" class="mt-4 border-top pt-4">
                <h5 class="fw-bold">Data Anggota:</h5>
                <div class="table-responsive mb-4">
                    <table class="table table-borderless">
                        <tr>
                            <td width="150" class="text-muted fw-bold">Nama</td>
                            <td id="resNama" class="fw-bold"></td>
                        </tr>
                        <tr>
                            <td class="text-muted fw-bold">NIM</td>
                            <td id="resNim"></td>
                        </tr>
                        <tr>
                            <td class="text-muted fw-bold">Status Buku</td>
                            <td id="resBukuStatus"></td>
                        </tr>
                    </table>
                </div>

                <!-- Form Action Placeholder based on Tab -->
                <div id="actionFormArea">
                    <!-- Dinamis akan diisi via JS -->
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@push('script-page')
<script>
    let nfcReader = null;
    let isScanning = false;
    let currentCardId = null;
    let currentActiveBorrow = null;

    // Konfigurasi CSRF Token untuk AJAX
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
    });

    // Handle perpindahan tab
    $('button[data-bs-toggle="pill"]').on('shown.bs.tab', function (e) {
        let target = $(e.target).attr("data-bs-target");
        
        if(target === '#pinjam') {
            $('#modeTitle').text('Mode Peminjaman');
            $('#modeDesc').text('Scan kartu untuk mulai meminjam buku.');
        } else if (target === '#kembali') {
            $('#modeTitle').text('Mode Pengembalian');
            $('#modeDesc').text('Scan kartu untuk mengembalikan buku yang sedang dipinjam.');
        } else {
            $('#modeTitle').text('Mode Absensi Kunjungan');
            $('#modeDesc').text('Scan kartu untuk mencatat kehadiran kunjungan.');
        }

        resetScanArea();
    });

    function resetScanArea() {
        $('#resultArea').slideUp();
        $('#actionFormArea').empty();
        if (isScanning) {
            $('#scanStatus').html('<span class="text-success"><i class="mdi mdi-cellphone-nfc pulse-animation"></i> Sedang mendeteksi kartu...</span>');
        } else {
            $('#scanStatus').html('<span class="text-warning"><i class="mdi mdi-circle-medium"></i> Menunggu kartu...</span>');
        }
    }

    function checkNfcSupport() {
        if (!('NDEFReader' in window)) {
            $('#nfcFallback').show();
            $('#btnStartScan').prop('disabled', true);
            $('.nfc-pulse').css('animation', 'none');
        }
    }

    async function startNfcScan() {
        if (!('NDEFReader' in window)) {
            Swal.fire('Error', 'Browser Anda tidak mendukung Web NFC API', 'error');
            return;
        }

        try {
            nfcReader = new NDEFReader();
            await nfcReader.scan();
            isScanning = true;
            
            $('#btnStartScan').removeClass('btn-gradient-primary').addClass('btn-outline-danger').html('<i class="mdi mdi-stop-circle-outline me-2"></i> Hentikan Scan');
            $('#btnStartScan').attr('onclick', 'stopNfcScan()');
            $('#scanStatus').html('<span class="text-success"><i class="mdi mdi-cellphone-nfc pulse-animation"></i> Sedang mendeteksi kartu...</span>');

            nfcReader.onreading = event => {
                const serialNumber = event.serialNumber;
                
                // Play beep sound if we had one, or just vibrate
                if (navigator.vibrate) navigator.vibrate(200);

                $('#scanStatus').html(`<span class="text-primary fw-bold"><i class="mdi mdi-check-circle"></i> Kartu terdeteksi: ${serialNumber}</span>`);
                
                lookupCardData(serialNumber);
            };

            nfcReader.onreadingerror = () => {
                $('#scanStatus').html('<span class="text-danger"><i class="mdi mdi-alert"></i> Gagal membaca tag NFC. Coba lagi.</span>');
            };

        } catch (error) {
            console.error(error);
            Swal.fire('Izin Ditolak', 'Harap izinkan akses NFC pada browser Anda.', 'warning');
        }
    }

    function stopNfcScan() {
        // There is no native stop() method in NDEFReader yet, so we just ignore events
        isScanning = false;
        if(nfcReader) nfcReader.onreading = null;
        
        $('#btnStartScan').removeClass('btn-outline-danger').addClass('btn-gradient-primary').html('<i class="mdi mdi-play-circle-outline me-2"></i> Mulai Scan');
        $('#btnStartScan').attr('onclick', 'startNfcScan()');
        resetScanArea();
    }

    function lookupCardData(serial) {
        Swal.fire({
            title: 'Memeriksa Data...',
            text: 'Mencari informasi anggota di sistem.',
            allowOutsideClick: false,
            didOpen: () => { Swal.showLoading(); }
        });

        $.ajax({
            url: '{{ route("nfc.lookup") }}',
            type: 'POST',
            data: { serial_number: serial },
            success: function(response) {
                Swal.close();
                currentCardId = response.data.card_id;
                currentActiveBorrow = response.data.active_borrow;
                
                $('#resNama').text(response.data.nama);
                $('#resNim').text(response.data.nim || '-');
                
                if(currentActiveBorrow) {
                    $('#resBukuStatus').html(`<span class="badge bg-warning text-dark"><i class="mdi mdi-book-open"></i> Meminjam: ${currentActiveBorrow.buku.judul}</span>`);
                } else {
                    $('#resBukuStatus').html(`<span class="badge bg-success"><i class="mdi mdi-check"></i> Bebas pinjaman</span>`);
                }

                $('#resultArea').slideDown();
                
                // Eksekusi aksi berdasarkan tab aktif
                processTabAction();
            },
            error: function(xhr) {
                Swal.fire('Gagal', xhr.responseJSON ? xhr.responseJSON.message : 'Terjadi kesalahan sistem.', 'error');
                resetScanArea();
            }
        });
    }

    // Quick read-only UID reader for index page (no write)
    async function quickReadUid() {
        if (!('NDEFReader' in window)) {
            // show manual input modal for demo
            $('#manualScanUid').val('');
            $('#manualScanModal').modal('show');
            return;
        }

        try {
            const reader = new NDEFReader();
            await reader.scan();

            Swal.fire({
                title: 'Menunggu kartu... (Quick Read)',
                html: '<div class="text-center mt-3"><i class="mdi mdi-cellphone-nfc pulse-animation" style="font-size:60px; color:#b66dff"></i></div><p class="mt-3">Tempelkan kartu untuk mencoba baca UID (read-only).</p>',
                showConfirmButton: false,
                allowOutsideClick: false
            });

            reader.onreading = event => {
                reader.onreading = null;
                Swal.close();

                const uid = event.serialNumber || null;
                if (!uid) {
                    Swal.fire('Tidak ada UID', 'Perangkat/Tag tidak mengungkapkan UID. Silakan gunakan input manual.', 'info');
                    $('#manualScanUid').val('');
                    $('#manualScanModal').modal('show');
                    return;
                }

                // show and lookup
                Swal.fire('UID Terbaca', `<p><strong>${uid}</strong></p>`, 'success');
                lookupCardData(uid);
            };

            reader.onreadingerror = () => {
                Swal.close();
                Swal.fire('Gagal Baca', 'Tidak dapat membaca kartu. Coba lagi atau gunakan input manual.', 'error');
            };
        } catch (e) {
            console.error(e);
            Swal.fire('Gagal', 'Akses NFC ditolak atau terjadi kesalahan.', 'error');
        }
    }

    function processTabAction() {
        const activeTab = $('.nav-pills .nav-link.active').attr('data-bs-target');
        const actionArea = $('#actionFormArea');
        actionArea.empty();

        if (activeTab === '#pinjam') {
            if (currentActiveBorrow) {
                actionArea.html(`
                    <div class="alert alert-danger mt-3">
                        <i class="mdi mdi-close-circle"></i> Anggota ini masih meminjam buku dan belum dikembalikan. Tidak dapat meminjam buku lain.
                    </div>
                `);
            } else {
                actionArea.html(`
                    <div class="mt-4 p-4 bg-light rounded" style="border: 1px dashed #ddd;">
                        <h6 class="fw-bold mb-3">Pilih Buku yang Akan Dipinjam:</h6>
                        <select class="form-select form-select-lg mb-3" id="bukuSelect">
                            <option value="">-- Pilih Buku --</option>
                            @foreach($bukus as $b)
                                <option value="{{ $b->id }}">{{ $b->kode }} - {{ $b->judul }}</option>
                            @endforeach
                        </select>
                        <button class="btn btn-primary w-100 fw-bold py-3" onclick="submitPeminjaman()">
                            <i class="mdi mdi-book-plus me-2"></i> Proses Peminjaman
                        </button>
                    </div>
                `);
            }
        } 
        else if (activeTab === '#kembali') {
            if (!currentActiveBorrow) {
                actionArea.html(`
                    <div class="alert alert-info mt-3">
                        <i class="mdi mdi-information"></i> Anggota ini tidak memiliki buku yang sedang dipinjam.
                    </div>
                `);
            } else {
                actionArea.html(`
                    <div class="mt-4 p-4 bg-light rounded text-center" style="border: 1px dashed #ddd;">
                        <h6 class="fw-bold text-muted mb-1">Buku yang harus dikembalikan:</h6>
                        <h4 class="fw-bold text-dark mb-4">${currentActiveBorrow.buku.judul}</h4>
                        <button class="btn btn-success w-100 fw-bold py-3" onclick="submitPengembalian(${currentActiveBorrow.id})">
                            <i class="mdi mdi-check-circle me-2"></i> Konfirmasi Pengembalian
                        </button>
                    </div>
                `);
            }
        } 
        else if (activeTab === '#absen') {
            // Langsung eksekusi absensi tanpa klik tambahan
            submitKunjungan();
        }
    }

    function submitPeminjaman() {
        const bukuId = $('#bukuSelect').val();
        if(!bukuId) {
            Swal.fire('Peringatan', 'Harap pilih buku terlebih dahulu.', 'warning');
            return;
        }

        let btn = $('#actionFormArea button');
        btnLoading(btn);

        $.ajax({
            url: '{{ route("nfc.peminjaman") }}',
            type: 'POST',
            data: { nfc_card_id: currentCardId, buku_id: bukuId },
            success: function(res) {
                Swal.fire('Berhasil!', res.message, 'success');
                resetScanArea();
            },
            error: function(xhr) {
                Swal.fire('Gagal', xhr.responseJSON.message, 'error');
                btn.html('<i class="mdi mdi-book-plus me-2"></i> Proses Peminjaman').removeClass('disabled').prop('disabled', false);
            }
        });
    }

    function submitPengembalian(peminjamanId) {
        let btn = $('#actionFormArea button');
        btnLoading(btn);

        $.ajax({
            url: '{{ route("nfc.pengembalian") }}',
            type: 'POST',
            data: { peminjaman_id: peminjamanId },
            success: function(res) {
                Swal.fire('Berhasil!', res.message, 'success');
                resetScanArea();
            },
            error: function(xhr) {
                Swal.fire('Gagal', xhr.responseJSON.message, 'error');
                btn.html('<i class="mdi mdi-check-circle me-2"></i> Konfirmasi Pengembalian').removeClass('disabled').prop('disabled', false);
            }
        });
    }

    function submitKunjungan() {
        $('#actionFormArea').html(`
            <div class="text-center mt-4">
                <div class="spinner-border text-primary" role="status"></div>
                <p class="mt-2 text-muted fw-bold">Mencatat Absensi...</p>
            </div>
        `);

        $.ajax({
            url: '{{ route("nfc.kunjungan") }}',
            type: 'POST',
            data: { nfc_card_id: currentCardId },
            success: function(res) {
                const icon = res.action === 'in' ? 'mdi-login text-success' : 'mdi-logout text-danger';
                const bg = res.action === 'in' ? 'bg-success' : 'bg-danger';
                
                $('#actionFormArea').html(`
                    <div class="alert alert-success mt-4 text-center">
                        <i class="mdi ${icon}" style="font-size: 40px;"></i>
                        <h4 class="fw-bold mt-2">${res.message}</h4>
                        <p class="mb-0 text-muted">Waktu: ${new Date().toLocaleTimeString()}</p>
                    </div>
                `);
                
                // Suara tap masuk/keluar bisa ditambahkan di sini
                if (navigator.vibrate) navigator.vibrate([100, 50, 100]);
            },
            error: function(xhr) {
                Swal.fire('Gagal', xhr.responseJSON.message, 'error');
                resetScanArea();
            }
        });
    }

    $(document).ready(function() {
        checkNfcSupport();
    });
</script>
@endpush
