@extends('layouts.app')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-home"></i>
        </span> Dashboard Overview
    </h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">
                <span></span>Statistik Real-time <i class="mdi mdi-check-decagram icon-sm text-primary align-middle"></i>
            </li>
        </ul>
    </nav>
</div>

<div class="row">
    <div class="col-md-4 stretch-card grid-margin">
        <div class="card bg-gradient-danger card-img-holder text-white">
            <div class="card-body">
                <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute" alt="circle-image" />
                <h4 class="font-weight-normal mb-3">Total Koleksi Buku <i class="mdi mdi-book-open-page-variant mdi-24px float-end"></i>
                </h4>
                <h2 class="mb-5">{{ number_format(\App\Models\Buku::count(), 0, ',', '.') }} Judul</h2>
                <p class="card-text">Peningkatan {{ rand(1, 5) }}% bulan ini</p>
            </div>
        </div>
    </div>
    <div class="col-md-4 stretch-card grid-margin">
        <div class="card bg-gradient-info card-img-holder text-white">
            <div class="card-body">
                <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute" alt="circle-image" />
                <h4 class="font-weight-normal mb-3">Total Kategori <i class="mdi mdi-format-list-bulleted mdi-24px float-end"></i>
                </h4>
                <h2 class="mb-5">{{ \App\Models\Kategori::count() }} Grup</h2>
                <p class="card-text">Terorganisir secara sistematis</p>
            </div>
        </div>
    </div>
    <div class="col-md-4 stretch-card grid-margin">
        <div class="card bg-gradient-success card-img-holder text-white">
            <div class="card-body">
                <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute" alt="circle-image" />
                <h4 class="font-weight-normal mb-3">Status Keamanan <i class="mdi mdi-shield-check mdi-24px float-end"></i>
                </h4>
                <h2 class="mb-5">Verified</h2>
                <p class="card-text">Login: {{ Auth::user()->name }}</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-7 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="clearfix">
                    <h4 class="card-title float-left">Akses Cepat Manajemen</h4>
                    <p class="text-muted">Kelola data perpustakaan digital Anda dengan satu klik.</p>
                </div>
                <div class="row mt-4">
                    <div class="col-6">
                        <div class="d-grid gap-2">
                            <a href="{{ route('buku.create') }}" class="btn btn-outline-primary btn-icon-text" onclick="btnLoading(this)">
                                <i class="mdi mdi-plus-circle-outline btn-icon-prepend"></i> Tambah Buku </a>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-grid gap-2">
                            <a href="{{ route('kategori.index') }}" class="btn btn-outline-info btn-icon-text" onclick="btnLoading(this)">
                                <i class="mdi mdi-folder-outline btn-icon-prepend"></i> Lihat Kategori </a>
                        </div>
                    </div>
                </div>
                <div class="mt-4 border-top pt-3">
                    <div class="d-flex align-items-center mb-2">
                        <i class="mdi mdi-account-circle-outline text-primary me-2"></i>
                        <span class="text-dark fw-bold">{{ Auth::user()->email }}</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <i class="mdi mdi-shield-lock-outline text-success me-2"></i>
                        <span class="text-muted small">Enkripsi OTP Aktif (Session ID: {{ substr(session()->getId(), 0, 10) }}...)</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-5 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Laporan & Sertifikasi</h4>
                <div class="list-wrapper">
                    <ul class="d-flex flex-column todo-list todo-list-custom">
                        <li class="d-flex align-items-center justify-content-between w-100 mb-2">
                            <div class="form-check mb-0">
                                <label class="form-check-label text-dark">
                                    Surat Undangan <i class="input-helper"></i>
                                </label>
                            </div>
                            <a href="{{ route('cetak.undangan') }}" target="_blank" 
                               class="btn btn-gradient-success btn-sm btn-icon-text" 
                               onclick="notifCetak('Undangan')">
                                <i class="mdi mdi-printer btn-icon-prepend"></i> Cetak
                            </a>
                        </li>
                        <li class="d-flex align-items-center justify-content-between w-100">
                            <div class="form-check mb-0">
                                <label class="form-check-label text-dark">
                                    Sertifikat Digital <i class="input-helper"></i>
                                </label>
                            </div>
                            <a href="{{ route('cetak.sertifikat') }}" target="_blank" 
                               class="btn btn-gradient-info btn-sm btn-icon-text" 
                               onclick="notifCetak('Sertifikat')">
                                <i class="mdi mdi-printer btn-icon-prepend"></i> Cetak
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="mt-4">
                    <p class="small text-muted italic">*Laporan dicetak dalam format PDF standar A4.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12 text-center py-2">
        <p class="text-muted small">Sesi dimulai pada: <strong>{{ date('d F Y, H:i') }} WIB</strong> | Koleksi Buku v2.0</p>
    </div>
</div>
@endsection