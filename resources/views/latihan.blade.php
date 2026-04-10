@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-map-marker"></i>
            </span> Latihan Studi Kasus
        </h3>
        <nav aria-label="breadcrumb">
            <ul class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">
                    <span></span>Studi Kasus 4 <i class="mdi mdi-check icon-sm text-primary align-middle"></i>
                </li>
            </ul>
        </nav>
    </div>

    <div class="row">
        <!-- Card Pertama: Select HTML Biasa -->
        <div class="col-md-6 grid-margin stretch-card">
            <div class="card shadow-sm" style="border-radius: 15px;">
                <div class="card-body">
                    <h4 class="card-title text-primary">Select</h4>
                    <p class="card-description text-muted">Studi kasus menggunakan elemen <code>&lt;select&gt;</code> HTML standar.</p>
                    
                    <div class="form-group mb-4">
                        <label class="fw-bold mb-2">Kota:</label>
                        <div class="input-group">
                            <input type="text" id="inputKotaBiasa" class="form-control" placeholder="Masukkan nama kota..." style="border-radius: 10px 0 0 10px;">
                            <button type="button" id="btnTambahKotaBiasa" class="btn btn-gradient-primary fw-bold" style="border-radius: 0 10px 10px 0;">TAMBAHKAN</button>
                        </div>
                    </div>

                    <div class="form-group mb-4">
                        <label class="fw-bold mb-2">Select Kota:</label>
                        <select id="selectKotaBiasa" class="form-control text-dark" style="border-radius: 10px; height: 45px;">
                            <option value="">-- Pilih Kota --</option>
                        </select>
                    </div>
                    
                    <div class="p-3 bg-light rounded-3 text-center border">
                        <span class="small text-muted d-block mb-1">Kota Terpilih:</span>
                        <b id="displayKotaBiasa" class="text-primary h5">-</b>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card Kedua: Select2 -->
        <div class="col-md-6 grid-margin stretch-card">
            <div class="card shadow-sm" style="border-radius: 15px;">
                <div class="card-body">
                    <h4 class="card-title text-info">select 2</h4>
                    <p class="card-description text-muted">Studi kasus menggunakan library <code>Select2</code> untuk fitur yang lebih kaya.</p>

                    <div class="form-group mb-4">
                        <label class="fw-bold mb-2">Kota:</label>
                        <div class="input-group">
                            <input type="text" id="inputKotaSelect2" class="form-control" placeholder="Masukkan nama kota..." style="border-radius: 10px 0 0 10px;">
                            <button type="button" id="btnTambahKotaSelect2" class="btn btn-gradient-info text-white fw-bold" style="border-radius: 0 10px 10px 0;">TAMBAHKAN</button>
                        </div>
                    </div>

                    <div class="form-group mb-4">
                        <label class="fw-bold mb-2">Select Kota:</label>
                        <select id="selectKotaSelect2" class="form-control select2-kota w-100">
                            <option value="">-- Pilih Kota --</option>
                        </select>
                    </div>

                    <div class="p-3 bg-light rounded-3 text-center border">
                        <span class="small text-muted d-block mb-1">Kota Terpilih:</span>
                        <b id="displayKotaSelect2" class="text-info h5">-</b>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script-page')
<!-- Select2 CSS & JS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function() {
    // --- Card Pertama: Select Biasa ---
    
    // Logika Penambahan Opsi Kota (Biasa)
    $('#btnTambahKotaBiasa').on('click', function() {
        let namaKota = $('#inputKotaBiasa').val().trim();
        
        if(namaKota !== "") {
            // Menggunakan .append() sesuai instruksi
            $('#selectKotaBiasa').append(`<option value="${namaKota}">${namaKota}</option>`);
            
            // Reset input
            $('#inputKotaBiasa').val('');
            
            // Notifikasi (Optional Premium UI)
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: 'Kota ' + namaKota + ' ditambahkan.',
                timer: 1000,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
        }
    });

    // Logika Event Change (Biasa) - Menggunakan $(this).val() sesuai instruksi
    $('#selectKotaBiasa').on('change', function() {
        let terpilih = $(this).val();
        $('#displayKotaBiasa').text(terpilih || "-");
    });


    // --- Card Kedua: Select2 ---

    // Inisialisasi Select2
    $('.select2-kota').select2({
        placeholder: "-- Pilih Kota --",
        allowClear: true
    });

    // Logika Penambahan Opsi Kota (Select2)
    $('#btnTambahKotaSelect2').on('click', function() {
        let namaKota = $('#inputKotaSelect2').val().trim();
        
        if(namaKota !== "") {
            // Menggunakan .append() dan trigger change agar Select2 terupdate
            let newOption = new Option(namaKota, namaKota, false, false);
            $('#selectKotaSelect2').append(newOption).trigger('change');
            
            // Reset input
            $('#inputKotaSelect2').val('');
            
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: 'Kota ' + namaKota + ' ditambahkan ke Select2.',
                timer: 1000,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
        }
    });

    // Logika Event Change (Select2) - Menggunakan $(this).val() sesuai instruksi
    $('#selectKotaSelect2').on('change', function() {
        let terpilih = $(this).val();
        $('#displayKotaSelect2').text(terpilih || "-");
    });
});
</script>

<style>
    /* Styling khusus agar Select2 serasi dengan tema premium Bootstrap 5 */
    .select2-container--default .select2-selection--single {
        border: 1px solid #ebedf2;
        height: 45px;
        border-radius: 10px;
        padding-top: 8px;
        font-size: 0.9rem;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 42px;
    }
    .select2-container--default .select2-selection--single .select2-selection__placeholder {
        color: #6c757d;
    }
</style>
@endpush