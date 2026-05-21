@extends('layouts.app')

@push('style-page')
<style>
    .write-card {
        border-radius: 20px;
        border: none;
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
    }
    .step-box {
        background: #f8f9fa;
        border-radius: 15px;
        padding: 30px;
        position: relative;
        border: 2px dashed #ddd;
    }
    .step-number {
        position: absolute;
        top: -15px;
        left: 20px;
        background: var(--primary-gradient);
        color: white;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        font-weight: bold;
    }
</style>
@endpush

@section('content')
<div class="page-header flex-wrap">
    <div class="header-left">
        <h3 class="page-title text-dark fw-bold">
            <span class="page-title-icon bg-gradient-primary text-white me-2 shadow-sm">
                <i class="mdi mdi-card-plus"></i>
            </span> Tulis Kartu NFC Baru
        </h3>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card write-card mb-4">
            <div class="card-body p-5">
                
                <div id="nfcFallback" class="alert alert-warning" style="display: none; border-radius: 15px;">
                    <h5><i class="mdi mdi-alert-circle"></i> Browser Tidak Mendukung NFC</h5>
                    <p class="mb-0">Fitur ini memerlukan browser Android Chrome.</p>
                </div>

                <div class="step-box mb-5">
                    <div class="step-number">1</div>
                    <h5 class="fw-bold mb-3">Isi Data Anggota</h5>
                    <form id="writeForm">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama Lengkap</label>
                            <input type="text" class="form-control form-control-lg" id="nama" required placeholder="Masukkan nama anggota">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">NIM / NIK</label>
                            <input type="text" class="form-control form-control-lg" id="nim" placeholder="Masukkan NIM (opsional)">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Email</label>
                            <input type="email" class="form-control form-control-lg" id="email" placeholder="contoh@vokasi.ac.id">
                        </div>
                    </form>
                </div>

                <div class="step-box border-primary" style="border-style: solid;">
                    <div class="step-number">2</div>
                    <h5 class="fw-bold mb-3 text-center">Tulis ke Kartu NFC</h5>
                    <p class="text-center text-muted mb-4">Pastikan data di atas sudah benar sebelum menulis ke kartu.</p>
                    
                    <div class="text-center">
                        <button type="button" class="btn btn-gradient-primary btn-lg rounded-pill px-5 fw-bold" onclick="writeToNfc()" id="btnWrite">
                            <i class="mdi mdi-nfc me-2"></i> Tulis & Registrasi Kartu
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@push('script-page')
<script>
    // Konfigurasi CSRF Token untuk AJAX
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
    });

    function checkNfcSupport() {
        if (!('NDEFReader' in window)) {
            $('#nfcFallback').show();
            $('#btnWrite').prop('disabled', true);
        }
    }

    async function writeToNfc() {
        const nama = $('#nama').val();
        const nim = $('#nim').val();
        const email = $('#email').val();

        if (!nama) {
            Swal.fire('Validasi', 'Nama anggota wajib diisi!', 'warning');
            return;
        }

        if (!('NDEFReader' in window)) {
            Swal.fire('Error', 'Browser Anda tidak mendukung Web NFC API', 'error');
            return;
        }

        // Tampilkan loading modal yang menunggu tap
        let scanModal = Swal.fire({
            title: 'Siapkan Kartu NFC',
            html: '<div class="text-center mt-3"><i class="mdi mdi-cellphone-nfc pulse-animation" style="font-size:60px; color:#b66dff"></i></div><p class="mt-3">Tempelkan kartu NFC ke area sensor (biasanya di punggung HP) dan tahan sebentar...</p>',
            showConfirmButton: false,
            allowOutsideClick: false
        });

        try {
            const ndef = new NDEFReader();
            
            // Kita gunakan method scan dulu untuk membaca serialNumber kartu sebelum menulis
            await ndef.scan();

            ndef.onreading = async event => {
                // Hentikan listening agar tidak ter-trigger berkali-kali
                ndef.onreading = null;

                const serialNumber = event.serialNumber;
                if (!serialNumber) {
                    Swal.fire('Error', 'Gagal mendapatkan Serial Number kartu. Coba dengan kartu lain.', 'error');
                    return;
                }

                if (navigator.vibrate) navigator.vibrate(100);

                // Update UI: Proses registrasi database
                Swal.fire({
                    title: 'Memproses Registrasi...',
                    text: `Serial Number: ${serialNumber}`,
                    allowOutsideClick: false,
                    didOpen: () => { Swal.showLoading(); }
                });

                // Tulis metadata sederhana ke kartu (opsional, tapi berguna sebagai bukti ID)
                try {
                    await ndef.write({
                        records: [{ 
                            recordType: "text", 
                            data: JSON.stringify({ sys: "VOKASI-PERPUS", id: serialNumber }) 
                        }]
                    });
                } catch (writeErr) {
                    console.log("Write to tag omitted or failed (card might be read-only), proceeding with registration.");
                }

                // Kirim ke server
                $.ajax({
                    url: '{{ route("nfc.cards.store") }}',
                    type: 'POST',
                    data: {
                        serial_number: serialNumber,
                        nama_anggota: nama,
                        nim: nim,
                        email: email
                    },
                    success: function(res) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: res.message,
                            confirmButtonText: 'Ke Manajemen Kartu'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = '{{ route("nfc.cards") }}';
                            }
                        });
                        $('#writeForm')[0].reset();
                    },
                    error: function(xhr) {
                        let errMsg = xhr.responseJSON ? xhr.responseJSON.message : 'Gagal menyimpan ke database.';
                        Swal.fire('Registrasi Gagal', errMsg, 'error');
                    }
                });
            };

            ndef.onreadingerror = () => {
                Swal.fire('Error Baca Kartu', 'Gagal mendeteksi kartu. Coba tempelkan lebih dekat dan tahan.', 'error');
            };

        } catch (error) {
            console.error(error);
            Swal.fire('Gagal', 'Terjadi kesalahan sistem atau akses NFC ditolak.', 'error');
        }
    }

    $(document).ready(function() {
        checkNfcSupport();
    });
</script>
@endpush
