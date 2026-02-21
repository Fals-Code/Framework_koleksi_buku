@extends('layouts.app')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-tag-text-outline"></i>
        </span> Edit Kategori
    </h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('kategori.index') }}">Kategori</a></li>
            <li class="breadcrumb-item active" aria-current="page">Perbarui Data</li>
        </ul>
    </nav>
</div>

<div class="row">
    <div class="col-md-8 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Perbarui Nama Kategori</h4>
                <p class="card-description"> Perubahan nama kategori akan otomatis memperbarui pengelompokan pada buku terkait. </p>
                
                <form class="forms-sample" action="{{ route('kategori.update', $kategori->id) }}" method="POST" onsubmit="btnLoading(document.getElementById('btnUpdate'))">
                    @csrf
                    @method('PUT')
                    
                    <div class="form-group">
                        <label for="nama_kategori">Nama Kategori</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-gradient-primary text-white">
                                    <i class="mdi mdi-label"></i>
                                </span>
                            </div>
                            <input type="text" class="form-control @error('nama_kategori') is-invalid @enderror" id="nama_kategori" name="nama_kategori" value="{{ old('nama_kategori', $kategori->nama_kategori) }}" required>
                            @error('nama_kategori')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" id="btnUpdate" class="btn btn-gradient-primary me-2 btn-icon-text">
                            <i class="mdi mdi-file-check btn-icon-prepend"></i> Update Kategori
                        </button>
                        <a href="{{ route('kategori.index') }}" class="btn btn-light btn-icon-text">
                            <i class="mdi mdi-arrow-left btn-icon-prepend"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4 grid-margin stretch-card">
        <div class="card bg-gradient-warning text-white text-center card-img-holder">
            <div class="card-body">
                <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute" alt="circle-image" />
                <h4 class="font-weight-normal mb-3">Perhatian <i class="mdi mdi-alert-octagon mdi-24px float-end"></i></h4>
                <p>Pastikan nama kategori tetap relevan agar koleksi buku Anda tidak sulit ditemukan oleh admin lain.</p>
            </div>
        </div>
    </div>
</div>
@endsection