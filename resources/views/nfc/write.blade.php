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
                        <button type="button" class="btn btn-gradient-primary btn-lg rounded-pill px-5 fw-bold" onclick="registerCard()" id="btnWrite">
                            <i class="mdi mdi-nfc me-2"></i> Daftarkan Kartu
                        </button>
                        <button type="button" class="btn btn-outline-secondary btn-lg rounded-pill px-4 fw-bold ms-3" onclick="quickReadUid()" id="btnQuickRead">
                            <i class="mdi mdi-magnify-scan me-2"></i> Quick Read UID
                        </button>
                    </div>

                    <div class="alert alert-warning mt-3" role="alert" style="border-radius:12px;">
                        <strong>Catatan:</strong> Jangan menulis ke kartu bank atau kartu identitas resmi (KTP/SIM). Gunakan fungsi <em>Quick Read</em> hanya untuk membaca UID jika tersedia, atau gunakan input manual untuk demo.
                    </div>

                    <div class="mt-4">
                        <h6 class="fw-bold">Recent UID (Demo)</h6>
                        <div id="recentUids" class="mb-2"></div>
                        <small class="text-muted">Klik UID untuk memasukkan ke form cepat.</small>
                    </div>

                    <div class="mt-3">
                        <h6 class="fw-bold">Kartu Terdaftar (Sementara)</h6>
                        <ul id="registeredCards" class="list-group list-group-flush"></ul>
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
            // Tampilkan informasi bahwa Web NFC tidak tersedia,
            // tetapi jangan menonaktifkan tombol sehingga fallback manual dapat digunakan.
            $('#nfcFallback').show();
        }
    }

    async function registerCard() {
        // If browser supports Web NFC, use normal scan; otherwise show manual modal.
        if ('NDEFReader' in window) {
            await writeToNfc();
        } else {
            openManualModal();
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

                let serialNumber = event.serialNumber;

                // Jika serialNumber tidak tersedia, coba ekstrak dari NDEF records
                if (!serialNumber && event.message && event.message.records && event.message.records.length) {
                    try {
                        for (const r of event.message.records) {
                            try {
                                // beberapa implementasi menyajikan data sebagai ArrayBuffer
                                const textDecoder = new TextDecoder('utf-8');
                                let decoded = null;
                                if (r.data) {
                                    if (typeof r.data === 'string') decoded = r.data;
                                    else decoded = textDecoder.decode(r.data);
                                }
                                if (decoded) {
                                    // coba parse JSON tersimpan seperti {sys:'..', id:'UID'}
                                    try {
                                        const parsed = JSON.parse(decoded);
                                        if (parsed && parsed.id) {
                                            serialNumber = parsed.id;
                                            break;
                                        }
                                    } catch(e) {
                                        // bukan JSON, gunakan teks langsung jika masuk akal
                                        if (decoded.length > 0 && decoded.length < 200) {
                                            serialNumber = decoded;
                                            break;
                                        }
                                    }
                                }
                            } catch(e) {
                                console.log('Error parsing record', e);
                            }
                        }
                    } catch(e) {
                        console.log('NDEF parse failed', e);
                    }
                }

                // Jika masih tidak ada serialNumber, minta input manual dari petugas
                if (!serialNumber) {
                    const { value: manualId } = await Swal.fire({
                        title: 'Serial Number tidak tersedia',
                        text: 'Tag tidak mengungkapkan UID. Masukkan ID/tag secara manual atau batalkan.',
                        input: 'text',
                        inputPlaceholder: 'Masukkan UID atau kode tag',
                        showCancelButton: true,
                    });

                    if (!manualId) {
                        Swal.fire('Dibatalkan', 'Registrasi dibatalkan karena tidak ada ID kartu.', 'info');
                        return;
                    }

                    serialNumber = manualId.trim();
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
                registerToServer(serialNumber, nama, nim, email);
            };

            ndef.onreadingerror = () => {
                Swal.fire('Error Baca Kartu', 'Gagal mendeteksi kartu. Coba tempelkan lebih dekat dan tahan.', 'error');
            };

        } catch (error) {
            console.error(error);
            Swal.fire('Gagal', 'Terjadi kesalahan sistem atau akses NFC ditolak.', 'error');
        }
    }

    // ----- Manual modal & helpers -----
    function openManualModal(prefillUid = '') {
        $('#manualUid').val(prefillUid);
        // If name field empty, keep whatever in #nama
        const currentName = $('#nama').val();
        $('#manualNama').val(currentName);
        $('#manualModal').modal('show');
    }

    function renderRecentUids() {
        const data = JSON.parse(localStorage.getItem('nfc_recent_uids') || '[]');
        const container = $('#recentUids');
        container.empty();
        data.forEach(uid => {
            const btn = $(`<button class="btn btn-sm btn-outline-secondary me-2 mb-2">${uid}</button>`);
            btn.on('click', () => openManualModal(uid));
            container.append(btn);
        });
    }

    function renderRegisteredCard(card) {
        const list = $('#registeredCards');
        const item = $(`<li class="list-group-item">${card.serial_number} — ${card.nama_anggota || '-'} <span class="text-muted float-end">ID:${card.id || '—'}</span></li>`);
        list.prepend(item);
    }

    function saveRecentUid(uid) {
        let data = JSON.parse(localStorage.getItem('nfc_recent_uids') || '[]');
        // avoid duplicates
        data = data.filter(x => x !== uid);
        data.unshift(uid);
        data = data.slice(0, 10);
        localStorage.setItem('nfc_recent_uids', JSON.stringify(data));
        renderRecentUids();
    }

    function manualSave() {
        const uid = $('#manualUid').val().trim();
        const namaManual = $('#manualNama').val().trim();
        const nimManual = $('#manualNim').val().trim();
        const emailManual = $('#manualEmail').val().trim();

        if (!uid) {
            Swal.fire('Validasi', 'UID wajib diisi.', 'warning');
            return;
        }

        // Prevent obvious duplicate client-side
        const recent = JSON.parse(localStorage.getItem('nfc_recent_uids') || '[]');
        if (recent.includes(uid)) {
            // Allow but warn user
            if (!confirm('UID sudah ada pada recent list. Tetap lanjutkan?')) return;
        }

        registerToServer(uid, namaManual || $('#nama').val(), nimManual || $('#nim').val(), emailManual || $('#email').val(), true);
    }

    function registerToServer(serialNumber, nama, nim, email, hideModal=false) {
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
                    confirmButtonText: 'Tutup'
                });
                $('#writeForm')[0].reset();
                if (hideModal) $('#manualModal').modal('hide');
                // Save recent uid and show in registered list
                saveRecentUid(serialNumber);
                // Append a lightweight representation to the registered list
                renderRegisteredCard({ serial_number: serialNumber, nama_anggota: nama, id: res.id || '' });
            },
            error: function(xhr) {
                let errMsg = xhr.responseJSON ? xhr.responseJSON.message : 'Gagal menyimpan ke database.';
                Swal.fire('Registrasi Gagal', errMsg, 'error');
            }
        });
    }

    // Quick read-only UID reader (no write) for testing/demo with cards
    async function quickReadUid() {
        if (!('NDEFReader' in window)) {
            // fallback to manual modal
            openManualModal();
            return;
        }

        try {
            const ndef = new NDEFReader();
            await ndef.scan();

            Swal.fire({
                title: 'Menunggu kartu... (Quick Read)',
                html: '<div class="text-center mt-3"><i class="mdi mdi-cellphone-nfc pulse-animation" style="font-size:60px; color:#b66dff"></i></div><p class="mt-3">Tempelkan kartu untuk mencoba baca UID (read-only).</p>',
                showConfirmButton: false,
                allowOutsideClick: false
            });

            ndef.onreading = event => {
                ndef.onreading = null;
                let uid = event.serialNumber || '';

                if (!uid && event.message && event.message.records && event.message.records.length) {
                    // try to parse first text-like record
                    try {
                        const r = event.message.records[0];
                        const td = new TextDecoder('utf-8');
                        if (r.data) {
                            uid = typeof r.data === 'string' ? r.data : td.decode(r.data);
                        }
                    } catch (e) {
                        console.log('QuickRead parse failed', e);
                    }
                }

                Swal.close();

                if (!uid) {
                    Swal.fire('Tidak ada UID', 'Perangkat/Tag tidak mengungkapkan UID. Gunakan input manual untuk demo.', 'info');
                    openManualModal();
                    return;
                }

                // Show UID and offer to copy into manual modal
                Swal.fire({
                    title: 'UID Terbaca',
                    html: `<p><strong>${uid}</strong></p>`,
                    showCancelButton: true,
                    confirmButtonText: 'Gunakan UID',
                    cancelButtonText: 'Tutup'
                }).then(result => {
                    if (result.isConfirmed) {
                        openManualModal(uid);
                    }
                });
            };

            ndef.onreadingerror = () => {
                Swal.close();
                Swal.fire('Gagal Baca', 'Tidak dapat membaca kartu. Coba lagi atau gunakan input manual.', 'error');
            };
        } catch (e) {
            console.error(e);
            Swal.fire('Gagal', 'Akses NFC ditolak atau terjadi kesalahan.', 'error');
        }
    }

    $(document).ready(function() {
                checkNfcSupport();
                renderRecentUids();
    });
</script>

<!-- Manual UID Modal -->
<div class="modal fade" id="manualModal" tabindex="-1" aria-labelledby="manualModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="manualModalLabel">Input UID Kartu Manual</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">UID / Kode Kartu</label>
                    <input id="manualUid" class="form-control" placeholder="Masukkan UID kartu">
                </div>
                <div class="mb-3">
                    <label class="form-label">Nama Pemilik</label>
                    <input id="manualNama" class="form-control" placeholder="Nama pemilik">
                </div>
                <div class="mb-3">
                    <label class="form-label">NIM / NIK (opsional)</label>
                    <input id="manualNim" class="form-control" placeholder="NIM (opsional)">
                </div>
                <div class="mb-3">
                    <label class="form-label">Email (opsional)</label>
                    <input id="manualEmail" class="form-control" placeholder="email@domain">
                </div>
                <small class="text-muted">Perangkat ini tidak mendukung Web NFC di browser, gunakan input manual untuk demo.</small>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" onclick="manualSave()">Simpan</button>
            </div>
        </div>
    </div>
</div>
@endpush
