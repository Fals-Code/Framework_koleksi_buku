@extends('layouts.app')

@section('content')
<style>
    @keyframes border-glow {
        0% { box-shadow: 0 0 5px rgba(255, 159, 67, 0.2); }
        50% { box-shadow: 0 0 20px rgba(255, 159, 67, 0.5); }
        100% { box-shadow: 0 0 5px rgba(255, 159, 67, 0.2); }
    }

    .edit-mode-card {
        background: rgba(255, 255, 255, 0.95) !important;
        border-radius: 20px !important;
        border-left: 5px solid #ff9f43 !important;
        transition: all 0.3s ease;
    }

    .edit-mode-card:hover {
        transform: translateY(-5px);
    }

    .form-control-edit {
        border-radius: 12px !important;
        padding: 12px 15px !important;
        border: 1px solid #e0e0e0 !important;
        height: auto !important;
        transition: all 0.2s;
    }

    .form-control-edit:focus {
        border-color: #ff9f43 !important;
        animation: border-glow 2s infinite;
        background-color: #fffaf5 !important;
        outline: none;
    }

    .status-badge-edit {
        position: absolute;
        top: -10px;
        right: 20px;
        background: linear-gradient(135deg, #ff9f43 0%, #ff6b6b 100%);
        color: white;
        padding: 5px 18px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: bold;
        z-index: 10;
        box-shadow: 0 4px 10px rgba(255, 107, 107, 0.3);
    }

    .bg-light-warning {
        background-color: #fff4e5;
    }

    .impact-item {
        transition: all 0.3s ease;
    }

    .impact-item:hover {
        background: rgba(255, 255, 255, 0.05);
        border-radius: 10px;
    }

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
        border-top-color: #fff; /* Spinner putih di atas background oranye */
        animation: spin 0.8s linear infinite;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    @keyframes border-glow {
        0% { box-shadow: 0 0 5px rgba(255, 159, 67, 0.2); }
        50% { box-shadow: 0 0 20px rgba(255, 159, 67, 0.5); }
        100% { box-shadow: 0 0 5px rgba(255, 159, 67, 0.2); }
    }

    .edit-mode-card {
        background: rgba(255, 255, 255, 0.95) !important;
        border-radius: 20px !important;
        border-left: 5px solid #ff9f43 !important;
        transition: all 0.3s ease;
    }
</style>

<div class="page-header flex-wrap">
    <div class="header-left">
        <h3 class="page-title text-dark fw-bold">
            <span class="page-title-icon bg-gradient-warning text-white me-2 shadow">
                <i class="mdi mdi-pencil-lock"></i>
            </span> Core Modification
        </h3>
        <p class="text-muted small mt-2 mb-0">Sinkronisasi data master kategori ke seluruh modul sistem.</p>
    </div>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('kategori.index') }}" class="text-warning">Kategori</a></li>
            <li class="breadcrumb-item active" aria-current="page">Modification Engine</li>
        </ol>
    </nav>
</div>

<div class="row">
    <div class="col-md-8 grid-margin stretch-card">
        <div class="card edit-mode-card shadow-lg position-relative">
            <div class="status-badge-edit text-uppercase">ID DOKUMEN: #CAT-{{ $kategori->id }}</div>
            
            <div class="card-body p-4 p-md-5">
                <div class="d-flex align-items-center mb-4">
                    <div class="rounded-circle bg-light-warning p-3 me-3">
                        <i class="mdi mdi-database-edit text-warning mdi-24px"></i>
                    </div>
                    <div>
                        <h4 class="card-title mb-0">Edit Informasi Kategori</h4>
                        <p class="text-muted small mb-0">Pastikan data yang dimasukkan sudah sesuai dengan klasifikasi perpustakaan.</p>
                    </div>
                </div>
                
                <form id="editKategoriForm" class="forms-sample" action="{{ route('kategori.update', $kategori->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="form-group mb-4">
                        <label for="nama_kategori" class="fw-bold mb-2 text-dark">Identitas Kategori Baru</label>
                        <div class="input-group shadow-sm" style="border-radius: 12px; overflow: hidden;">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-gradient-warning text-white border-0" style="border-radius: 12px 0 0 12px; height: 100%;">
                                    <i class="mdi mdi-label-variant-outline"></i>
                                </span>
                            </div>
                            <input type="text" 
                                   class="form-control form-control-edit @error('nama_kategori') is-invalid @enderror" 
                                   id="nama_kategori" 
                                   name="nama_kategori" 
                                   value="{{ old('nama_kategori', $kategori->nama_kategori) }}" 
                                   required>
                        </div>
                        @error('nama_kategori')
                            <div class="invalid-feedback d-block mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mt-5 d-flex gap-2">
                        <button type="submit" id="btnUpdate" class="btn btn-gradient-warning btn-lg text-white px-4 fw-bold shadow-sm">
                            <i class="mdi mdi-refresh btn-icon-prepend"></i> Terapkan Perubahan
                        </button>
                        <a href="{{ route('kategori.index') }}" class="btn btn-outline-secondary btn-lg px-4 shadow-sm" onclick="btnLoading(this)">
                            <i class="mdi mdi-arrow-left btn-icon-prepend"></i> Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4 grid-margin stretch-card">
        <div class="card bg-dark text-white card-img-holder shadow-lg border-0" style="border-radius: 20px;">
            <div class="card-body">
                <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute" alt="circle-image" />
                <h4 class="font-weight-normal mb-4">Impact Analysis <i class="mdi mdi-radar mdi-24px float-end text-warning"></i></h4>
                
                <div class="impact-item mb-4 d-flex align-items-start p-2">
                    <i class="mdi mdi-check-circle text-success me-3 mt-1 mdi-18px"></i>
                    <p class="small mb-0">
                        @php
                            $count = \App\Models\Buku::where('idkategori', $kategori->id)->count();
                        @endphp
                        Aksi ini akan memperbarui metadata pada <strong>{{ $count }} koleksi buku</strong> terkait secara otomatis.
                    </p>
                </div>

                <div class="impact-item mb-4 d-flex align-items-start p-2 border-top pt-3" style="border-color: rgba(255,255,255,0.1) !important;">
                    <i class="mdi mdi-shield-check text-info me-3 mt-1 mdi-18px"></i>
                    <p class="small mb-0">Data integritas akan dipertahankan melalui mekanisme <em>Relational Mapping</em> database.</p>
                </div>

                <div class="p-3 bg-secondary rounded-3 mt-4" style="--bs-bg-opacity: .15; border: 1px dashed rgba(255,255,255,0.3);">
                    <p class="small mb-0 text-center italic text-warning" style="font-size: 0.75rem;">
                        <i class="mdi mdi-quote-open me-1"></i>Setiap perubahan data akan tercatat dalam System Audit Log.<i class="mdi mdi-quote-close ms-1"></i>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function btnLoading(btn) {
        btn.classList.add('btn-loading');
        if(btn.tagName === 'A') {
            btn.style.pointerEvents = 'none';
        }
    }

    document.getElementById('editKategoriForm').addEventListener('submit', function(e) {
        const btn = document.getElementById('btnUpdate');

        if (this.checkValidity()) {
            btnLoading(btn);
        }
    });
</script>
@endsection