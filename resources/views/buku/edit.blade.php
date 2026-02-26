@extends('layouts.app')

@section('content')
<style>
    @keyframes border-glow-purple {
        0% { box-shadow: 0 0 5px rgba(182, 109, 255, 0.2); }
        50% { box-shadow: 0 0 20px rgba(182, 109, 255, 0.5); }
        100% { box-shadow: 0 0 5px rgba(182, 109, 255, 0.2); }
    }

    .edit-mode-card {
        background: rgba(255, 255, 255, 0.95) !important;
        border-radius: 20px !important;
        border-left: 5px solid #b66dff !important; /* Warna Ungu Buku */
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
        border-color: #b66dff !important;
        animation: border-glow-purple 2s infinite;
        background-color: #f8f0ff !important;
        outline: none;
    }

    .status-badge-edit {
        position: absolute;
        top: -10px;
        right: 20px;
        background: linear-gradient(135deg, #b66dff 0%, #6a11cb 100%);
        color: white;
        padding: 5px 18px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: bold;
        z-index: 10;
        box-shadow: 0 4px 10px rgba(182, 109, 255, 0.3);
    }

    .bg-light-purple {
        background-color: #f3e5f5;
    }

    .input-group-text-purple {
        background: linear-gradient(135deg, #b66dff 0%, #8e24aa 100%);
        color: white;
        border: none;
        border-radius: 12px 0 0 12px !important;
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
            <div class="status-badge-edit text-uppercase">UUID: #BKS-{{ $buku->id }}</div>
            
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
                        <button type="submit" class="btn btn-gradient-primary btn-lg text-white px-4 fw-bold shadow-sm">
                            <i class="mdi mdi-content-save btn-icon-prepend"></i> Simpan Perubahan
                        </button>
                        <a href="{{ route('buku.index') }}" class="btn btn-outline-secondary btn-lg px-4 shadow-sm">
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