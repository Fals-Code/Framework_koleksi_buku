@extends('layouts.app')

@section('content')
<style>
    .btn-loading {
        position: relative;
        color: transparent !important;
        pointer-events: none;
    }

    .btn-loading::after {
        content: "";
        position: absolute;
        width: 20px;
        height: 20px;
        top: 50%;
        left: 50%;
        margin-top: -10px;
        margin-left: -10px;
        border: 3px solid rgba(255,255,255,0.3);
        border-radius: 50%;
        border-top-color: #fff;
        animation: spin 0.8s linear infinite;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    .animated-content { animation: fadeInUp 0.6s ease-out; }
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .glass-form-card {
        background: rgba(255, 255, 255, 0.9) !important;
        border-radius: 20px !important;
        border: 1px solid rgba(182, 109, 255, 0.1) !important;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05) !important;
    }

    .btn-neon:hover {
        box-shadow: 0 0 20px rgba(182, 109, 255, 0.6);
        transform: translateY(-2px);
    }

    .tips-card {
        border-radius: 20px !important;
        background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%) !important;
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

                    <form class="forms-sample" action="{{ route('kategori.store') }}" method="POST" id="categoryForm">
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
                <div class="card-body p-4 text-center">
                    <i class="mdi mdi-lightbulb-on-outline" style="font-size: 5rem; opacity: 0.5;"></i>
                    <h4 class="mt-3">Quality Control</h4>
                    <p class="small">Kategori yang terstruktur membantu pengelompokan buku menjadi lebih rapi dan mudah ditemukan.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function btnLoading(btn) {
        if (btn.type === 'submit') {
            const form = btn.closest('form');
            if (!form.checkValidity()) return;
        }
        
        btn.classList.add('btn-loading');
        if(btn.tagName === 'A') {
            btn.style.pointerEvents = 'none';
        }
    }

    document.getElementById('categoryForm').addEventListener('submit', function() {
        btnLoading(document.getElementById('btnSubmit'));
    });
</script>
@endsection