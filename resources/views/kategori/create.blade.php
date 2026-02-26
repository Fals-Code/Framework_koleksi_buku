@extends('layouts.app')

@section('content')
<style>
    .animated-content {
        animation: fadeInUp 0.6s ease-out;
    }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .glass-form-card {
        background: rgba(255, 255, 255, 0.9) !important;
        border-radius: 20px !important;
        border: 1px solid rgba(182, 109, 255, 0.1) !important;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05) !important;
        overflow: hidden;
    }

    .custom-input-group {
        transition: all 0.3s ease;
        border-radius: 10px;
        overflow: hidden;
    }

    .form-control-lg:focus {
        border-color: #b66dff !important;
        box-shadow: 0 0 15px rgba(182, 109, 255, 0.2) !important;
        background-color: #fff !important;
    }

    .btn-neon {
        transition: all 0.4s ease;
        position: relative;
        overflow: hidden;
    }

    .btn-neon:hover {
        box-shadow: 0 0 20px rgba(182, 109, 255, 0.6);
        transform: translateY(-2px);
    }

    .tips-card {
        border-radius: 20px !important;
        background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%) !important;
        border: none !important;
        position: relative;
    }

    .floating-icon-bg {
        position: absolute;
        right: -10px;
        bottom: -10px;
        font-size: 8rem;
        color: rgba(255, 255, 255, 0.1);
        transform: rotate(-15deg);
    }
</style>

<div class="animated-content">
    <div class="page-header">
        <h3 class="page-title text-primary fw-bold">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-tag-plus"></i>
            </span> Master Data Kategori
        </h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb border-0 p-0 bg-transparent">
                <li class="breadcrumb-item"><a href="{{ route('kategori.index') }}" class="text-decoration-none text-muted">Kategori</a></li>
                <li class="breadcrumb-item active fw-bold text-primary" aria-current="page">Entry Baru</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class="col-md-8 grid-margin stretch-card">
            <div class="card glass-form-card">
                <div class="card-body p-5">
                    <div class="d-flex align-items-center mb-4">
                        <div class="bg-light-primary rounded-circle p-3 me-3">
                            <i class="mdi mdi-database-plus text-primary mdi-24px"></i>
                        </div>
                        <div>
                            <h4 class="card-title mb-1">Registrasi Kategori Baru</h4>
                            <p class="text-muted small">Pastikan nama kategori belum terdaftar dalam sistem.</p>
                        </div>
                    </div>

                    <form class="forms-sample" action="{{ route('kategori.store') }}" method="POST" onsubmit="btnLoading(document.getElementById('btnSubmit'))">
                        @csrf
                        
                        <div class="form-group mb-4">
                            <label for="nama_kategori" class="fw-bold text-dark">Nama Label Kategori</label>
                            <div class="input-group custom-input-group shadow-sm">
                                <span class="input-group-text bg-white border-end-0">
                                    <i class="mdi mdi-format-list-bulleted-type text-primary"></i>
                                </span>
                                <input type="text" 
                                       class="form-control form-control-lg border-start-0 ps-0" 
                                       id="nama_kategori" 
                                       name="nama_kategori" 
                                       placeholder="Misal: Arsitektur, Jurnalistik, Kedokteran..." 
                                       required 
                                       autocomplete="off">
                            </div>
                            <small class="text-muted mt-2 d-block">
                                <i class="mdi mdi-information-outline me-1"></i> Gunakan huruf kapital di setiap awal kata (Title Case).
                            </small>
                        </div>

                        <hr class="my-4" style="opacity: 0.1;">

                        <div class="d-flex justify-content-start align-items-center">
                            <button type="submit" id="btnSubmit" class="btn btn-gradient-primary btn-lg btn-icon-text btn-neon me-3 px-4">
                                <i class="mdi mdi-content-save-all btn-icon-prepend"></i> Finalisasi & Simpan
                            </button>
                            <a href="{{ route('kategori.index') }}" class="btn btn-outline-secondary btn-lg px-4" onclick="btnLoading(this)">
                                <i class="mdi mdi-close-circle-outline"></i> Batalkan
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 grid-margin stretch-card">
            <div class="card tips-card text-white shadow-lg">
                <div class="card-body p-4">
                    <i class="mdi mdi-lightbulb-on-outline floating-icon-bg"></i>
                    
                    <div class="d-flex align-items-center mb-4">
                        <div class="rounded-circle bg-white p-2 me-3" style="--bs-bg-opacity: 0.2;">
                            <i class="mdi mdi-shield-check text-white"></i>
                        </div>
                        <h4 class="mb-0">Quality Control</h4>
                    </div>
                    
                    <div class="tips-item mb-4">
                        <h6 class="fw-bold mb-2 text-warning">Efisiensi Pencarian</h6>
                        <p class="small" style="line-height: 1.6; opacity: 0.9;">
                            Kategori yang terstruktur membantu algoritma pencarian bekerja 40% lebih cepat bagi mahasiswa.
                        </p>
                    </div>

                    <div class="tips-item mb-4">
                        <h6 class="fw-bold mb-2 text-warning">Penamaan Unik</h6>
                        <p class="small" style="line-height: 1.6; opacity: 0.9;">
                            Hindari penamaan yang mirip (Contoh: "Sains" dan "Ilmu Sains"). Pilih satu yang paling baku.
                        </p>
                    </div>

                    <div class="mt-5 pt-3 border-top border-secondary">
                        <div class="d-flex align-items-center">
                            <i class="mdi mdi-account-circle me-2"></i>
                            <span class="small italic">Operator: {{ Auth::user()->name }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection