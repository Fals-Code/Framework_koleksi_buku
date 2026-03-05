@extends('layouts.app')

@section('content')
<style>

    #tabelBarang tbody tr {
    cursor: pointer;
    transition: background 0.2s;
}
#tabelBarang tbody tr:hover {
    background-color: rgba(182, 109, 255, 0.05) !important;
}
    /* Fix Z-Index agar Modal tidak tertutup backdrop hitam */
    .modal { z-index: 1060 !important; }
    .modal-backdrop { z-index: 1050 !important; }

    .content-wrapper { animation: fadeIn 0.8s ease-in-out; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    
    .barang-card {
        border: none !important; border-radius: 25px !important;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05) !important;
        transition: all 0.3s ease; background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
    }
    .bg-gradient-sultan { background: linear-gradient(to right, #da8cff, #9a55ff) !important; }

    .table-modern thead th {
        background: #f8f9fa; border: none !important; color: #343a40;
        font-weight: 700; text-transform: uppercase; letter-spacing: 1px;
        font-size: 0.75rem; padding: 15px !important;
    }
    .table-modern tbody td { padding: 12px 15px !important; vertical-align: middle !important; border-top: 1px solid #f2f2f2 !important; }
    
    .price-tag { color: #2ecc71; font-weight: 800; font-family: 'Monaco', monospace; }
    .id-tag { background: #f0edf7; color: #b66dff; padding: 4px 10px; border-radius: 8px; font-weight: bold; font-size: 0.8rem; }
    
    .badge-dot {
        height: 8px; width: 8px; background-color: #b66dff;
        border-radius: 50%; display: inline-block;
        box-shadow: 0 0 8px rgba(182, 109, 255, 0.8);
    }

    .custom-checkbox { width: 18px; height: 18px; border-radius: 4px; cursor: pointer; accent-color: #b66dff; }
    .form-control-custom { border-radius: 12px !important; border: 1.5px solid #ebebeb !important; padding: 12px 15px !important; }
</style>

<div class="page-header flex-wrap">
    <div class="header-left">
        <h3 class="page-title text-dark fw-bold">
            <span class="page-title-icon bg-gradient-sultan text-white me-2 shadow-sm">
                <i class="mdi mdi-tag-multiple"></i>
            </span> Smart UMKM Labeler
        </h3>
    </div>
    <div class="header-right d-flex flex-wrap mt-2 mt-sm-0">
        <div class="d-flex align-items-center me-3">
            <span class="badge badge-gradient-primary p-2 px-3 shadow-sm" id="selectedCount">0 Barang Terpilih</span>
        </div>
        <button type="button" id="btnBukaModalCetak" class="btn btn-gradient-info btn-icon-text fw-bold shadow-sm rounded-pill">
            <i class="mdi mdi-printer btn-icon-prepend"></i> Cetak Massal
        </button>
    </div>
</div>

<div class="row">
    <div class="col-md-4 grid-margin stretch-card">
        <div class="card barang-card">
            <div class="card-body">
                <h4 class="card-title mb-4"><i class="mdi mdi-database-plus text-primary me-2"></i>Input Inventaris</h4>
                <form action="{{ route('barang.store') }}" method="POST">
                    @csrf
                    <div class="form-group mb-3">
                        <label class="small fw-bold text-muted text-uppercase">Nama Produk</label>
                        <input type="text" name="nama" class="form-control form-control-custom" placeholder="Kripik Tempe" required>
                    </div>
                    <div class="form-group mb-4">
                        <label class="small fw-bold text-muted text-uppercase">Harga Jual</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0">Rp</span>
                            <input type="number" name="harga" class="form-control form-control-custom" placeholder="0" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-gradient-primary btn-lg w-100 fw-bold shadow rounded-pill">SIMPAN DATA</button>
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
                                    <th width="30"><input type="checkbox" id="checkAll" class="custom-checkbox"></th>
                                    <th>ID Barang</th>
                                    <th>Produk</th>
                                    <th class="text-end">Harga</th>
                                    <th>Ditambahkan</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
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
                <button type="submit" form="formCetakLabel" class="btn btn-gradient-info w-100 btn-lg fw-bold rounded-pill">GENERATE PDF</button>
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
    $(document).ready(function() {
        window.selectedIds = new Set();

        var table = $('#tabelBarang').DataTable({
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
                { data: 'timestamp', name: 'timestamp' },
                { 
                    data: 'id_barang',
                    orderable: false,
                    className: 'text-center',
                    render: id => `<button type="button" onclick="hapusBarang(${id})" class="btn btn-link p-0 text-danger"><i class="mdi mdi-delete-outline fs-5"></i></button>`
                }
            ],
            order: [[4, 'desc']],
            language: {
                processing: '<div class="spinner-grow text-primary"></div>',
                paginate: { previous: "←", next: "→" }
            },
            drawCallback: function() {
                $('.barang-checkbox').addClass('custom-checkbox').each(function() {
                    if (window.selectedIds.has($(this).val())) $(this).prop('checked', true);
                });
                updateUI();
            }
        });

        $('#tabelBarang tbody').on('click', 'td:not(:first-child):not(:last-child)', function() {
            var data = table.row($(this).parents('tr')).data();
            $('#edit_id').val(data.id_barang);
            $('#edit_nama').val(data.nama);
            $('#edit_harga').val(data.harga.toString().replace(/[^0-9]/g, '')); 
            $('#modalEditBarang').modal('show');
        });
$(document).on('click', '#btnUpdate', function(e) {
    e.preventDefault();
    let btn = $(this);
    let id = $('#edit_id').val();
    let nama = $('#edit_nama').val();
    let harga = $('#edit_harga').val();

    if(!nama || !harga) {
        document.getElementById('formEditBarang').reportValidity();
        return;
    }

    btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span>');

    $.ajax({
        url: "/barang/" + id,
        type: "POST", // Tetap POST
        data: {
            _token: "{{ csrf_token() }}",
            _method: "PUT", // Tapi Laravel membacanya sebagai PUT
            nama: nama,
            harga: harga
        },
        success: function(response) {
            $('#modalEditBarang').modal('hide');
            // 'table' harus sesuai dengan nama variabel DataTable Anda
            if ($.fn.DataTable.isDataTable('#tabelBarang')) {
                $('#tabelBarang').DataTable().ajax.reload(null, false);
            }
            Swal.fire('Berhasil!', 'Data diperbarui', 'success');
        },
        error: function(xhr) {
            console.error(xhr.responseText); 
            Swal.fire('Error 500', 'Terjadi kesalahan di server. Cek log Laravel!', 'error');
        },
        complete: function() {
            btn.prop('disabled', false).text('UBAH');
        }
    });
});

        $(document).on('click', '#btnHapusModal', function() {
            let id = $('#edit_id').val();
            $('#modalEditBarang').modal('hide');
            hapusBarang(id);
        });

        function updateUI() {
            let count = window.selectedIds.size;
            $('#selectedCount').text(count + ' Terpilih').toggleClass('badge-gradient-danger', count === 0);
        }

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

        $('input[name="x_coord"], input[name="y_coord"]').on('input', function() {
            $('#slotPreview').text(`(${$('input[name="x_coord"]').val()}, ${$('input[name="y_coord"]').val()})`);
        });

        $('#formCetakLabel').submit(function() {
            $('.temp-ids').remove();
            window.selectedIds.forEach(id => $(this).append(`<input type="hidden" class="temp-ids" name="ids[]" value="${id}">`));
            $('#modalKoordinat').modal('hide');
            return true;
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