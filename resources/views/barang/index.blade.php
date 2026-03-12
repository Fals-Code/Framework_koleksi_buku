@extends('layouts.app')

@section('content')
<style>
    .content-wrapper { animation: fadeIn 0.6s ease-out; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

    .barang-card {
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

    #tabelBarang tbody tr { cursor: pointer; transition: 0.2s; }
    #tabelBarang tbody tr:hover { background-color: rgba(182, 109, 255, 0.03) !important; }
    
    .table-modern thead th {
        background: #fcfcfc;
        border-bottom: 2px solid #f0f0f0 !important;
        color: #888;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 11px;
        letter-spacing: 1px;
    }

    .price-tag { color: #27ae60; font-weight: 700; font-family: 'JetBrains Mono', monospace; }
    .id-tag { 
        background: rgba(182, 109, 255, 0.1); 
        color: #b66dff; 
        padding: 4px 10px; 
        border-radius: 6px; 
        font-weight: bold; 
        font-size: 12px;
    }

.processing-overlay {
    position: absolute;
    top: 0; left: 0; right: 0; bottom: 0;
    background: rgba(255, 255, 255, 0.7);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 10;
    border-radius: 20px;
    backdrop-filter: blur(2px);
}
</style>

<div class="page-header flex-wrap">
    <div class="header-left">
        <h3 class="page-title text-dark fw-bold">
            <span class="page-title-icon bg-gradient-primary text-white me-2 shadow-sm">
                <i class="mdi mdi-tag-multiple"></i>
            </span> Smart UMKM Labeler
        </h3>
    </div>
    <div class="header-right d-flex flex-wrap mt-2 mt-sm-0">
        <div class="d-flex align-items-center me-3">
            <span class="badge bg-light text-primary p-2 px-3 border" id="selectedCount" style="border-radius: 10px;">0 Barang Terpilih</span>
        </div>
        <button type="button" id="btnBukaModalCetak" class="btn btn-gradient-info btn-icon-text fw-bold shadow-sm rounded-pill px-4">
            <i class="mdi mdi-printer btn-icon-prepend"></i> Cetak Massal
        </button>
    </div>
</div>

<div class="row">
    <div class="col-md-4 grid-margin stretch-card">
        <div class="card barang-card">
            <div class="card-body">
                <h5 class="fw-bold mb-4 text-dark">
                    <i class="mdi mdi-database-plus text-primary me-2"></i>Tambah Inventaris
                </h5>
                <form action="{{ route('barang.store') }}" method="POST">
                    @csrf
                    <div class="form-group mb-3">
                        <label class="small fw-bold text-muted">NAMA PRODUK</label>
                        <input type="text" name="nama" class="form-control form-control-custom" placeholder="Contoh: Kripik Tempe" required>
                    </div>
                    <div class="form-group mb-4">
                        <label class="small fw-bold text-muted">HARGA JUAL</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0" style="border-radius: 12px 0 0 12px;">Rp</span>
                            <input type="number" name="harga" class="form-control form-control-custom" style="border-radius: 0 12px 12px 0 !important;" placeholder="0" required>
                        </div>
                    </div>
<button type="submit" id="btnSimpanBarang" onclick="handleSimpanLoading(this)" class="btn btn-gradient-primary btn-lg w-100 fw-bold shadow-sm rounded-pill py-3">
    <span class="btn-text"><i class="mdi mdi-content-save me-2"></i>SIMPAN DATA</span>
</button>
                </form>
            </div>
        </div>
    </div>

<div class="col-md-8 grid-margin stretch-card">
        <div class="card barang-card">
            <div class="card-body">
                <div class="table-responsive">
                    <form id="formCetakLabel" action="{{ route('barang.cetak') }}" method="POST" target="_blank">
                        @csrf
                        <table class="table table-modern w-100" id="tabelBarang">
<thead>
    <tr>
        <th width="30"><input type="checkbox" id="checkAll"></th> <th>Ref ID</th> <th>Produk</th> <th class ="text-end">Harga</th> <th>Added At</th> </tr>
</thead>
                            <tbody></tbody>
                        </table>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalKoordinat" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content shadow-lg" style="border-radius: 30px; border:none;">
            <div class="modal-header bg-gradient-info text-white border-0 py-4">
                <h5 class="modal-title fw-bold mx-auto">Mapping Posisi</h5>
            </div>
            <div class="modal-body p-4 bg-white">
                <div class="row g-3">
                    <div class="col-6">
                        <label class="small fw-bold">KOLOM (X)</label>
                        <input type="number" form="formCetakLabel" name="x_coord" class="form-control form-control-custom text-center fw-bold" min="1" max="5" value="1">
                    </div>
                    <div class="col-6">
                        <label class="small fw-bold">BARIS (Y)</label>
                        <input type="number" form="formCetakLabel" name="y_coord" class="form-control form-control-custom text-center fw-bold" min="1" max="8" value="1">
                    </div>
                </div>
                <div class="alert alert-light mt-3 small border-0 text-center">
                    Slot Awal: <b class="text-info" id="slotPreview">(1, 1)</b>
                </div>
            </div>
            <div class="modal-footer border-0 p-3 bg-light" style="border-radius: 0 0 30px 30px;">
                <button type="submit" id="btnGeneratePDF" form="formCetakLabel" class="btn btn-gradient-info w-100 btn-lg fw-bold rounded-pill">
    GENERATE PDF
</button>
                <button type="button" class="btn btn-link w-100 text-muted small" data-bs-dismiss="modal">Batal</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEditBarang" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 20px;">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">Detail / Ubah Barang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formEditBarang">
                    <div class="form-group mb-3">
                        <label class="small fw-bold">ID BARANG</label>
                        <input type="text" id="edit_id" class="form-control form-control-custom bg-light" readonly> </div>
                    <div class="form-group mb-3">
                        <label class="small fw-bold">NAMA BARANG</label>
                        <input type="text" id="edit_nama" class="form-control form-control-custom" required> </div>
                    <div class="form-group mb-4">
                        <label class="small fw-bold">HARGA BARANG</label>
                        <input type="number" id="edit_harga" class="form-control form-control-custom" required> </div>
                    
                    <div class="d-flex gap-2">
                        <button type="button" id="btnUpdate" class="btn btn-gradient-primary w-100 fw-bold rounded-pill">UBAH</button> <button type="button" id="btnHapusModal" class="btn btn-gradient-danger w-100 fw-bold rounded-pill">HAPUS</button> </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('script-page')
<script>
    function handleSimpanLoading(btn) {
        const form = btn.closest('form');
        if (form.checkValidity()) {
            btn.classList.add('btn-loading');
        }
    }
    $(document).ready(function() {
        window.selectedIds = new Set();

        const tableBarang = $('#tabelBarang').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('barang.index') }}",
            columns: [
                { data: 'checkbox', orderable: false, searchable: false, className: 'text-center' },
                { 
                    data: 'id_barang', 
                    name: 'id_barang',
                    render: d => `<span class="id-tag">#${d}</span>` 
                },
                { data: 'nama', name: 'nama', className: 'fw-bold text-dark' },
                { 
                    data: 'harga', 
                    name: 'harga',
                    className: 'text-end', 
                    render: d => `<span class="price-tag">${d}</span>` 
                },
                { data: 'timestamp', name: 'timestamp' }
            ],
            order: [[4, 'desc']],
            language: {
                processing: '<div class="spinner-grow text-primary"></div>',
                paginate: { previous: "←", next: "→" }
            },
            drawCallback: function() {
                $('.barang-checkbox').addClass('custom-checkbox').each(function() {
                    if (window.selectedIds.has($(this).val())) {
                        $(this).prop('checked', true);
                    }
                });
                updateUI();
            }
        });

        $('#tabelBarang tbody').on('click', 'td:not(:first-child)', function() {
            const data = tableBarang.row($(this).parents('tr')).data();
            if(data) {
                $('#edit_id').val(data.id_barang);
                $('#edit_nama').val(data.nama);
                // Menghapus format Rupiah agar hanya angka yang masuk ke input
                const cleanHarga = data.harga.toString().replace(/[^0-9]/g, '');
                $('#edit_harga').val(cleanHarga); 
                $('#modalEditBarang').modal('show');
            }
        });

        $('form[action*="barang.store"]').submit(function() {
            let btn = $(this).find('button[type="submit"]');
            btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span> Menyimpan...');
            return true;
        });

        $('#formCetakLabel').submit(function() {
            let btn = $('#btnGeneratePDF');
            let originalText = btn.html();
            $('.temp-ids').remove();
            window.selectedIds.forEach(id => $(this).append(`<input type="hidden" class="temp-ids" name="ids[]" value="${id}">`));
            
            btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span> Rendering...');
            setTimeout(function() {
                btn.prop('disabled', false).html(originalText);
                $('#modalKoordinat').modal('hide');
            }, 3000);
            return true;
        });

        $('#tabelBarang tbody').on('change', '.barang-checkbox', function() {
            this.checked ? window.selectedIds.add($(this).val()) : window.selectedIds.delete($(this).val());
            updateUI();
        });

        $('#checkAll').on('click', function() {
            $('.barang-checkbox').prop('checked', this.checked).trigger('change');
        });

        $('#btnBukaModalCetak').click(function() {
            if (window.selectedIds.size === 0) {
                Swal.fire({ icon: 'warning', title: 'Pilih Barang!', text: 'Centang minimal satu barang.' });
            } else {
                $('#modalKoordinat').modal('show');
            }
        });

        $('#btnHapusModal').on('click', function() {
            let id = $('#edit_id').val();
            $('#modalEditBarang').modal('hide');
            hapusBarang(id);
        });

        $('input[name="x_coord"], input[name="y_coord"]').on('input', function() {
            $('#slotPreview').text(`(${$('input[name="x_coord"]').val()}, ${$('input[name="y_coord"]').val()})`);
        });
    });

    function updateUI() {
        let count = window.selectedIds.size;
        $('#selectedCount').text(count + ' Terpilih').toggleClass('badge-gradient-danger', count === 0);
    }

    $(document).on('click', '#btnUpdate', function(e) {
        e.preventDefault();
        let id = $('#edit_id').val();
        Swal.fire({ title: 'Memproses...', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); } });

        $.ajax({
            url: "/barang/" + id,
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                _method: "PUT",
                nama: $('#edit_nama').val(),
                harga: $('#edit_harga').val()
            },
            success: function() {
                Swal.close();
                $('#modalEditBarang').modal('hide');
                $('#tabelBarang').DataTable().ajax.reload(null, false);
                Swal.fire('Berhasil!', 'Data diperbarui.', 'success');
            },
            error: function() { Swal.fire('Gagal!', 'Terjadi kesalahan.', 'error'); }
        });
    });

    function hapusBarang(id) {
        Swal.fire({
            title: 'Hapus data?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#b66dff',
            confirmButtonText: 'Ya, Hapus!'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({ title: 'Menghapus...', didOpen: () => { Swal.showLoading(); } });
                $.ajax({
                    url: "/barang/" + id,
                    type: "DELETE",
                    data: { _token: "{{ csrf_token() }}" },
                    success: function() {
                        $('#tabelBarang').DataTable().ajax.reload(null, false);
                        Swal.fire('Terhapus!', 'Data berhasil dibuang.', 'success');
                    }
                });
            }
        });
    }
</script>
@endpush