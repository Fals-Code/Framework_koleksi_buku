@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-archive"></i>
            </span> Warehouse & Vendor Management
        </h3>
        <nav aria-label="breadcrumb">
            <ul class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">
                    <span></span>Studi Kasus 4 <i class="mdi mdi-alert-circle-outline icon-sm text-primary align-middle"></i>
                </li>
            </ul>
        </nav>
    </div>

    <div class="row">
        <div class="col-md-6 grid-margin stretch-card">
            <div class="card shadow-sm" style="border-radius: 15px;">
                <div class="card-body">
                    <h4 class="card-title text-primary">Select</h4>
                    <p class="card-description text-muted">Input <code>ID RAK</code> penyimpanan secara dinamis.</p>
                    
                    <div class="form-group mb-4">
                        <label class="fw-bold mb-2">Nama Rak Baru:</label>
                        <div class="input-group">
                            <input type="text" id="inputRak" class="form-control" placeholder="Contoh: RAK-A1" style="border-radius: 10px 0 0 10px;">
                            <button type="button" id="btnTambahRak" class="btn btn-gradient-primary fw-bold" style="border-radius: 0 10px 10px 0;">TAMBAH</button>
                        </div>
                    </div>

                    <div class="form-group mb-4">
                        <label class="fw-bold mb-2">Daftar Lokasi Rak:</label>
                        <select id="selectRak" class="form-control text-dark" style="border-radius: 10px; height: 45px;">
                            <option value="">-- Pilih Lokasi Rak --</option>
                        </select>
                    </div>
                    
                    <div class="p-3 bg-light rounded-3 text-center border">
                        <span class="small text-muted d-block mb-1">Lokasi Rak Terpilih:</span>
                        <b id="terpilihRak" class="text-primary h5">-</b>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 grid-margin stretch-card">
            <div class="card shadow-sm" style="border-radius: 15px;">
                <div class="card-body">
                    <h4 class="card-title text-info">select 2</h4>
                    <p class="card-description text-muted">Input <code>SUPPLIER</code> dengan fitur pencarian cepat.</p>

                    <div class="form-group mb-4">
                        <label class="fw-bold mb-2">Nama Supplier Baru:</label>
                        <div class="input-group">
                            <input type="text" id="inputVendor" class="form-control" placeholder="Contoh: PT. Maju Jaya" style="border-radius: 10px 0 0 10px;">
                            <button type="button" id="btnTambahVendor" class="btn btn-gradient-info text-white fw-bold" style="border-radius: 0 10px 10px 0;">REGISTRASI</button>
                        </div>
                    </div>

                    <div class="form-group mb-4">
                        <label class="fw-bold mb-2">Cari Nama Supplier:</label>
                        <select id="selectVendor" class="form-control select2-custom w-100">
                            <option value="">-- Ketik Nama PT --</option>
                        </select>
                    </div>

                    <div class="p-3 bg-light rounded-3 text-center border">
                        <span class="small text-muted d-block mb-1">Supplier Terpilih:</span>
                        <b id="terpilihVendor" class="text-info h5">-</b>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script-page')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function() {
    $('#btnTambahRak').click(function() {
        let val = $('#inputRak').val().trim();
        if(val !== "") {
            // Sesuai modul: Gunakan new Option(text, value)
            $('#selectRak').append(new Option(val, val));
            $('#inputRak').val('');
            
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'Rak ' + val + ' ditambahkan ke daftar.',
                timer: 1500,
                showConfirmButton: false
            });
        }
    });

    $('#selectRak').change(function() {
        $('#terpilihRak').text($(this).val() || "-");
    });

    $('.select2-custom').select2({
        placeholder: "-- Cari Nama PT --",
        allowClear: true
    });

    $('#btnTambahVendor').click(function() {
        let val = $('#inputVendor').val().trim();
        if(val !== "") {
            let newOption = new Option(val, val, false, false);
            $('#selectVendor').append(newOption).trigger('change');
            $('#inputVendor').val('');

            Swal.fire({
                icon: 'success',
                title: 'Registrasi Berhasil',
                text: 'Vendor ' + val + ' kini tersedia di Select2.',
                timer: 1500,
                showConfirmButton: false
            });
        }
    });

    $('#selectVendor').on('change', function() {
        $('#terpilihVendor').text($(this).val() || "-");
    });
});
</script>

<style>
    .select2-container--default .select2-selection--single {
        border: 1px solid #ebedf2;
        height: 45px;
        border-radius: 10px;
        padding-top: 8px;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 42px;
    }
</style>
@endpush