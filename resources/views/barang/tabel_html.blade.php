@extends('layouts.app')

@section('content')
<style>
    .content-wrapper { animation: fadeIn 0.6s ease-out; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

    .soal-card {
        border: none !important;
        border-radius: 20px !important;
        box-shadow: 0 8px 25px rgba(0,0,0,0.03) !important;
        background: #ffffff;
    }

    .form-control-custom {
        border-radius: 12px !important;
        border: 1.5px solid #f0f0f0 !important;
        padding: 12px 15px !important;
        transition: all 0.3s;
        background: #fff !important;
    }
    .form-control-custom:focus {
        border-color: #b66dff !important;
        box-shadow: 0 0 0 0.2rem rgba(182, 109, 255, 0.1) !important;
    }

    /* Tabel Styling */
    .table-barang {
        border-collapse: separate;
        border-spacing: 0;
        width: 100%;
    }
    .table-barang thead th {
        background: #fcfcfc;
        border-bottom: 2px solid #f0f0f0 !important;
        color: #888;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 11px;
        letter-spacing: 1px;
        padding: 14px 16px;
    }
    .table-barang tbody tr {
        cursor: pointer;
        transition: all 0.25s ease;
    }
    .table-barang tbody tr:hover {
        background-color: rgba(182, 109, 255, 0.06) !important;
        transform: scale(1.005);
    }
    .table-barang tbody td {
        padding: 13px 16px;
        vertical-align: middle;
        border-bottom: 1px solid #f5f5f5;
    }

    .price-tag {
        color: #27ae60;
        font-weight: 700;
        font-family: 'JetBrains Mono', monospace;
    }
    .id-tag {
        background: rgba(182, 109, 255, 0.1);
        color: #b66dff;
        padding: 4px 12px;
        border-radius: 8px;
        font-weight: bold;
        font-size: 12px;
        letter-spacing: 0.5px;
    }

    /* Empty state */
    .empty-state {
        padding: 50px 20px;
        text-align: center;
        color: #bbb;
    }
    .empty-state i { font-size: 48px; margin-bottom: 12px; display: block; }

    /* Spinner button */
    .btn-loading .btn-text { display: none; }
    .btn-loading .btn-spinner { display: inline-flex !important; align-items: center; }

    /* Row animation */
    @keyframes slideInRow {
        from { opacity: 0; transform: translateX(-15px); }
        to { opacity: 1; transform: translateX(0); }
    }
    .row-new { animation: slideInRow 0.4s ease-out; }

    /* Modal styling */
    .modal-content-custom {
        border: none;
        border-radius: 20px;
        overflow: hidden;
    }
    .modal-header-custom {
        background: linear-gradient(135deg, #b66dff 0%, #8e44ad 100%);
        color: #fff;
        border: none;
        padding: 20px 24px;
    }
    .modal-header-custom .btn-close { filter: invert(1); }

    /* Counter badge */
    .counter-badge {
        background: linear-gradient(135deg, #b66dff, #8e44ad);
        color: #fff;
        padding: 6px 16px;
        border-radius: 50px;
        font-weight: 700;
        font-size: 13px;
        letter-spacing: 0.3px;
    }
</style>

<div class="page-header flex-wrap">
    <div class="header-left">
        <h3 class="page-title text-dark fw-bold">
            <span class="page-title-icon bg-gradient-primary text-white me-2 shadow-sm">
                <i class="mdi mdi-table-large"></i>
            </span> Tabel HTML Biasa
        </h3>
    </div>
    <div class="header-right d-flex align-items-center mt-2 mt-sm-0">
        <span class="counter-badge" id="totalBarang">
            <i class="mdi mdi-package-variant-closed me-1"></i> 0 Barang
        </span>
    </div>
</div>

<div class="row">
    {{-- FORM TAMBAH BARANG (Soal 2) --}}
    <div class="col-md-4 grid-margin stretch-card">
        <div class="card soal-card">
            <div class="card-body">
                <h5 class="fw-bold mb-1 text-dark">
                    <i class="mdi mdi-plus-circle text-primary me-2"></i>Tambah Barang
                </h5>
                <p class="text-muted small mb-4">Soal 2 — Manipulasi DOM dengan jQuery</p>

                <form id="formTambahBarang" novalidate>
                    <div class="form-group mb-3">
                        <label class="small fw-bold text-muted">NAMA BARANG <span class="text-danger">*</span></label>
                        <input type="text" id="namaBarang" class="form-control form-control-custom" placeholder="Contoh: Kripik Tempe" required>
                        <div class="invalid-feedback">Nama barang wajib diisi.</div>
                    </div>
                    <div class="form-group mb-4">
                        <label class="small fw-bold text-muted">HARGA BARANG <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0" style="border-radius: 12px 0 0 12px;">Rp</span>
                            <input type="number" id="hargaBarang" class="form-control form-control-custom" style="border-radius: 0 12px 12px 0 !important;" placeholder="0" required min="1">
                            <div class="invalid-feedback">Harga barang wajib diisi.</div>
                        </div>
                    </div>

                    <button type="submit" id="btnSubmit" class="btn btn-gradient-primary btn-lg w-100 fw-bold shadow-sm rounded-pill py-3">
                        <span class="btn-text"><i class="mdi mdi-content-save me-2"></i>SIMPAN DATA</span>
                        <span class="btn-spinner d-none">
                            <span class="spinner-border spinner-border-sm me-2"></span> Menyimpan...
                        </span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- TABEL HTML BIASA (Soal 2 & 3) --}}
    <div class="col-md-8 grid-margin stretch-card">
        <div class="card soal-card">
            <div class="card-body">
                <h5 class="fw-bold mb-1 text-dark">
                    <i class="mdi mdi-format-list-bulleted text-info me-2"></i>Daftar Barang
                </h5>
                <p class="text-muted small mb-4">Klik baris untuk Ubah / Hapus (Soal 3)</p>

                <div class="table-responsive">
                    <table class="table table-barang" id="tabelBarangHTML">
                        <thead>
                            <tr>
                                <th width="80">ID</th>
                                <th>Nama Barang</th>
                                <th class="text-end" width="180">Harga</th>
                            </tr>
                        </thead>
                        <tbody id="bodyTabel">
                            @foreach($barang as $item)
                            <tr data-id="{{ $item->id_barang }}" data-nama="{{ $item->nama }}" data-harga="{{ $item->harga }}">
                                <td><span class="id-tag">#{{ $item->id_barang }}</span></td>
                                <td class="fw-bold text-dark">{{ $item->nama }}</td>
                                <td class="text-end"><span class="price-tag">Rp {{ number_format($item->harga, 0, ',', '.') }}</span></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="empty-state" id="emptyState">
                        <i class="mdi mdi-package-variant-closed"></i>
                        <p class="mb-0 fw-bold">Belum ada data barang</p>
                        <small>Tambahkan barang menggunakan form di samping</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL EDIT / HAPUS (Soal 3) --}}
<div class="modal fade" id="modalDetailBarang" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-content-custom shadow-lg">
            <div class="modal-header modal-header-custom">
                <h5 class="modal-title fw-bold">
                    <i class="mdi mdi-pencil-box-outline me-2"></i>Detail / Ubah Barang
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="formEditBarangHTML" novalidate>
                    <div class="form-group mb-3">
                        <label class="small fw-bold text-muted">ID BARANG</label>
                        <input type="text" id="modal_id" class="form-control form-control-custom bg-light" readonly>
                    </div>
                    <div class="form-group mb-3">
                        <label class="small fw-bold text-muted">NAMA BARANG <span class="text-danger">*</span></label>
                        <input type="text" id="modal_nama" class="form-control form-control-custom" required>
                        <div class="invalid-feedback">Nama barang wajib diisi.</div>
                    </div>
                    <div class="form-group mb-4">
                        <label class="small fw-bold text-muted">HARGA BARANG <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0" style="border-radius: 12px 0 0 12px;">Rp</span>
                            <input type="number" id="modal_harga" class="form-control form-control-custom" style="border-radius: 0 12px 12px 0 !important;" required min="1">
                            <div class="invalid-feedback">Harga barang wajib diisi.</div>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="button" id="btnUbah" class="btn btn-gradient-primary w-100 fw-bold rounded-pill py-2">
                            <i class="mdi mdi-check-circle me-1"></i> UBAH
                        </button>
                        <button type="button" id="btnHapus" class="btn btn-gradient-danger w-100 fw-bold rounded-pill py-2">
                            <i class="mdi mdi-delete me-1"></i> HAPUS
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('script-page')
<script>
$(document).ready(function() {

    // ============================================
    // SOAL 2: Auto-increment ID dan Tambah Data
    // ============================================
    // Set auto-increment mulai dari ID terakhir yang sudah ada di database
    let autoId = {{ $barang->count() > 0 ? $barang->last()->id_barang : 0 }};

    // Format angka ke Rupiah
    function formatRupiah(angka) {
        return new Intl.NumberFormat('id-ID').format(angka);
    }

    // Update counter badge
    function updateCounter() {
        let total = $('#bodyTabel tr').length;
        $('#totalBarang').html('<i class="mdi mdi-package-variant-closed me-1"></i> ' + total + ' Barang');
        // Toggle empty state
        if (total > 0) {
            $('#emptyState').hide();
        } else {
            $('#emptyState').show();
        }
    }

    // Handle form submit
    $('#formTambahBarang').on('submit', function(e) {
        e.preventDefault();

        let form = this;

        // Validasi menggunakan checkValidity() dan reportValidity()
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        let nama  = $('#namaBarang').val().trim();
        let harga = $('#hargaBarang').val();
        let btn   = $('#btnSubmit');

        // Tampilkan spinner/loader pada tombol (mencegah double submit)
        btn.prop('disabled', true);
        btn.addClass('btn-loading');

        // Simulasi delay (seolah proses penyimpanan)
        setTimeout(function() {
            autoId++;

            // Tambahkan baris baru ke tabel HTML
            let newRow = `
                <tr class="row-new" data-id="${autoId}" data-nama="${nama}" data-harga="${harga}">
                    <td><span class="id-tag">#${autoId}</span></td>
                    <td class="fw-bold text-dark">${nama}</td>
                    <td class="text-end"><span class="price-tag">Rp ${formatRupiah(harga)}</span></td>
                </tr>
            `;
            $('#bodyTabel').append(newRow);

            // Kosongkan kembali input
            $('#namaBarang').val('');
            $('#hargaBarang').val('');
            form.classList.remove('was-validated');

            // Kembalikan tombol ke normal
            btn.prop('disabled', false);
            btn.removeClass('btn-loading');

            // Update counter
            updateCounter();

            // Notifikasi sukses
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: nama + ' berhasil ditambahkan.',
                timer: 1500,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
        }, 800);
    });

    // ============================================
    // SOAL 3: Klik baris → Modal Edit/Hapus
    // ============================================

    // Variabel untuk menyimpan referensi baris yang sedang dipilih
    let selectedRow = null;

    // Event: Klik baris pada tabel → buka modal
    $('#bodyTabel').on('click', 'tr', function() {
        selectedRow = $(this);

        let id    = selectedRow.data('id');
        let nama  = selectedRow.data('nama');
        let harga = selectedRow.data('harga');

        // Isi form modal dengan data baris yang diklik
        $('#modal_id').val(id);
        $('#modal_nama').val(nama);
        $('#modal_harga').val(harga);

        // Reset validation state
        $('#formEditBarangHTML')[0].classList.remove('was-validated');

        // Tampilkan modal
        $('#modalDetailBarang').modal('show');
    });

    // Tombol UBAH di modal
    $('#btnUbah').on('click', function() {
        let form = $('#formEditBarangHTML')[0];

        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        let namaBaru  = $('#modal_nama').val().trim();
        let hargaBaru = $('#modal_harga').val();

        if (selectedRow) {
            // Update data-attribute pada baris
            selectedRow.data('nama', namaBaru);
            selectedRow.data('harga', hargaBaru);

            // Update tampilan sel pada baris tabel
            selectedRow.find('td:eq(1)').text(namaBaru);
            selectedRow.find('td:eq(2)').html('<span class="price-tag">Rp ' + formatRupiah(hargaBaru) + '</span>');

            // Animasi flash
            selectedRow.css('background-color', 'rgba(182, 109, 255, 0.15)');
            setTimeout(() => selectedRow.css('background-color', ''), 600);

            // Tutup modal
            $('#modalDetailBarang').modal('hide');

            Swal.fire({
                icon: 'success',
                title: 'Diperbarui!',
                text: 'Data barang berhasil diubah.',
                timer: 1500,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
        }
    });

    // Tombol HAPUS di modal
    $('#btnHapus').on('click', function() {
        if (selectedRow) {
            let nama = selectedRow.data('nama');

            Swal.fire({
                title: 'Hapus Barang?',
                html: 'Yakin ingin menghapus <b>' + nama + '</b>?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e74c3c',
                cancelButtonColor: '#95a5a6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Animasi hapus
                    selectedRow.fadeOut(400, function() {
                        $(this).remove();
                        updateCounter();
                    });

                    // Tutup modal
                    $('#modalDetailBarang').modal('hide');

                    Swal.fire({
                        icon: 'success',
                        title: 'Terhapus!',
                        text: nama + ' berhasil dihapus.',
                        timer: 1500,
                        showConfirmButton: false,
                        toast: true,
                        position: 'top-end'
                    });
                }
            });
        }
    });

    // Inisialisasi awal
    updateCounter();
});
</script>
@endpush
