@extends('layouts.app')

@section('content')
<style>
.id-capsule {
        position: absolute;
        top: 25px;
        right: 25px;
        display: flex;
        align-items: center;
        gap: 10px;
        background: rgba(182, 109, 255, 0.05);
        padding: 6px 16px;
        border-radius: 100px;
        border: 1px solid rgba(182, 109, 255, 0.2);
        z-index: 10;
        backdrop-filter: blur(4px);
    }

    .id-label {
        font-size: 10px;
        font-weight: 800;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 1.5px;
    }

    .id-value {
        font-family: 'JetBrains Mono', 'Fira Code', 'Consolas', monospace;
        font-size: 13px;
        font-weight: 700;
        color: #b66dff;
    }
    @keyframes border-glow-purple {
        0% { box-shadow: 0 0 5px rgba(182, 109, 255, 0.2); }
        50% { box-shadow: 0 0 15px rgba(182, 109, 255, 0.4); }
        100% { box-shadow: 0 0 5px rgba(182, 109, 255, 0.2); }
    }

    .edit-mode-card {
        background: #ffffff !important;
        border-radius: 20px !important;
        border: none !important;
        border-left: 6px solid #b66dff !important;
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
    }

    .form-group:hover label {
        color: #b66dff;
        transform: translateX(5px);
        transition: all 0.3s;
    }

    .form-control-edit {
        border-radius: 12px !important;
        padding: 12px 18px !important;
        border: 1px solid #ebedf2 !important;
        background-color: #fcfcfd !important;
        transition: all 0.3s ease;
    }

    .form-control-edit:focus {
        background-color: #ffffff !important;
        border-color: #b66dff !important;
        box-shadow: 0 8px 20px rgba(182, 109, 255, 0.15) !important;
    }

    .analytics-card {
        background: linear-gradient(45deg, #191c24 0%, #2c2e33 100%) !important;
        overflow: hidden;
    }

    .impact-item {
        transition: transform 0.3s;
        cursor: default;
    }

    .impact-item:hover {
        transform: scale(1.02);
        background: rgba(255,255,255,0.05);
        border-radius: 10px;
    }
</style>

<div class="page-header flex-wrap">
    <div class="header-left">
        <h3 class="page-title text-dark fw-bold">
            <span class="page-title-icon bg-gradient-primary text-white me-2 shadow">
                <i class="mdi mdi-book-open-page-variant"></i>
            </span> Book Modification
        </h3>
        <p class="text-muted small mt-2 mb-0">Sinkronisasi data literatur ke dalam sistem perpustakaan digital.</p>
    </div>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('buku.index') }}" class="text-primary">Koleksi</a></li>
            <li class="breadcrumb-item active" aria-current="page">Modification Engine</li>
        </ol>
    </nav>
</div>

<div class="row">
    <div class="col-md-8 grid-margin stretch-card">
        <div class="card edit-mode-card shadow-lg position-relative">
<div class="id-capsule">
    <span class="id-label">Ref Code</span>
    <span class="id-value">{{ $buku->kode }}</span>
</div>
            
            <div class="card-body p-4 p-md-5">
                <div class="d-flex align-items-center mb-4">
                    <div class="rounded-circle bg-light-purple p-3 me-3">
                        <i class="mdi mdi-lead-pencil text-primary mdi-24px"></i>
                    </div>
                    <div>
                        <h4 class="card-title mb-0">Detail Modifikasi Buku</h4>
                        <p class="text-muted small mb-0">Perbarui metadata buku untuk memastikan keakuratan katalog.</p>
                    </div>
                </div>
                
                <form class="forms-sample" action="{{ route('buku.update', $buku->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="form-group mb-4">
                        <label class="fw-bold mb-2 text-dark">Judul Literatur</label>
                        <div class="input-group shadow-sm" style="border-radius: 12px;">
                            <div class="input-group-prepend">
                                <span class="input-group-text input-group-text-purple">
                                    <i class="mdi mdi-format-title"></i>
                                </span>
                            </div>
                            <input type="text" name="judul" class="form-control form-control-edit @error('judul') is-invalid @enderror" 
                                   value="{{ old('judul', $buku->judul) }}" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="fw-bold mb-2 text-dark">Klasifikasi Kategori</label>
                                <div class="input-group shadow-sm" style="border-radius: 12px;">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text input-group-text-purple">
                                            <i class="mdi mdi-tag-multiple"></i>
                                        </span>
                                    </div>
                                    <select name="idkategori" class="form-control form-control-edit" required>
                                        @foreach($kategoris as $kat)
                                            <option value="{{ $kat->id }}" {{ $buku->idkategori == $kat->id ? 'selected' : '' }}>
                                                {{ $kat->nama_kategori }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="fw-bold mb-2 text-dark">Otoritas Penulis</label>
                                <div class="input-group shadow-sm" style="border-radius: 12px;">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text input-group-text-purple">
                                            <i class="mdi mdi-account-edit"></i>
                                        </span>
                                    </div>
                                    <input type="text" name="penulis" class="form-control form-control-edit" 
                                           value="{{ old('penulis', $buku->penulis) }}" required>
                                </div>
                            </div>
                        </div>
                    </div>

<div class="mt-5 d-flex gap-2">
    <button type="submit" class="btn btn-gradient-primary btn-lg text-white px-4 fw-bold shadow-sm" onclick="btnLoading(this)">
        <i class="mdi mdi-content-save btn-icon-prepend"></i> Simpan Perubahan
    </button>
    
    <a href="{{ route('buku.index') }}" class="btn btn-outline-secondary btn-lg px-4 shadow-sm" onclick="btnLoading(this)">
        <i class="mdi mdi-arrow-left"></i> Batal
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
                <h4 class="font-weight-normal mb-4">System Analytics <i class="mdi mdi-chart-bubble mdi-24px float-end text-primary"></i></h4>
                
                <div class="impact-item mb-4 d-flex align-items-start p-2">
                    <i class="mdi mdi-book-variant text-primary me-3 mt-1 mdi-18px"></i>
                    <p class="small mb-0">Status Koleksi: <br><span class="badge badge-outline-success mt-1">Active Record</span></p>
                </div>

                <div class="impact-item mb-4 d-flex align-items-start p-2 border-top pt-3" style="border-color: rgba(255,255,255,0.1) !important;">
                    <i class="mdi mdi-link-variant text-info me-3 mt-1 mdi-18px"></i>
                    <p class="small mb-0">Relasi Database: <br>Terhubung dengan tabel <strong>Kategoris</strong> dan <strong>Log Aktivitas</strong>.</p>
                </div>

                <div class="p-3 rounded-3 mt-4" style="background: rgba(182, 109, 255, 0.1); border: 1px dashed rgba(182, 109, 255, 0.5);">
                    <p class="small mb-0 text-center italic text-primary" style="font-size: 0.75rem;">
                        <i class="mdi mdi-information-variant me-1"></i>Pembaruan data buku akan langsung di-cache oleh sistem untuk performa maksimal.<i class="mdi mdi-quote-close ms-1"></i>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection