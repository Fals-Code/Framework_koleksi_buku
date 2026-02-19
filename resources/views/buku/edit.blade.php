@extends('layouts.app')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-book-edit"></i>
        </span> Edit Buku
    </h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('buku.index') }}" onclick="btnLoading(this)">Buku</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit Data</li>
        </ul>
    </nav>
</div>

<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Perbarui Informasi Buku</h4>
                <p class="card-description"> Pastikan kode buku tidak diubah jika tidak diperlukan. </p>
                
                <form class="forms-sample" action="{{ route('buku.update', $buku->idbuku) }}" method="POST" onsubmit="btnLoading(document.getElementById('btnUpdateBuku'))">
                    @csrf
                    @method('PUT')
                    
                    <div class="form-group">
                        <label for="kode">Kode Buku</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-gradient-primary text-white">
                                    <i class="mdi mdi-barcode"></i>
                                </span>
                            </div>
                            <input type="text" class="form-control" id="kode" name="kode" value="{{ $buku->kode }}" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="judul">Judul Buku</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-gradient-primary text-white">
                                    <i class="mdi mdi-format-title"></i>
                                </span>
                            </div>
                            <input type="text" class="form-control" id="judul" name="judul" value="{{ $buku->judul }}" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="pengarang">Pengarang</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-gradient-primary text-white">
                                            <i class="mdi mdi-account-edit"></i>
                                        </span>
                                    </div>
                                    <input type="text" class="form-control" id="pengarang" name="pengarang" value="{{ $buku->pengarang }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="idkategori">Kategori</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-gradient-primary text-white">
                                            <i class="mdi mdi-tag-multiple"></i>
                                        </span>
                                    </div>
                                    <select class="form-control" id="idkategori" name="idkategori" required>
                                        @foreach($kategoris as $kategori)
                                            <option value="{{ $kategori->idkategori }}" {{ $buku->idkategori == $kategori->idkategori ? 'selected' : '' }}>
                                                {{ $kategori->nama_kategori }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" id="btnUpdateBuku" class="btn btn-gradient-primary me-2 btn-icon-text">
                            <i class="mdi mdi-file-check btn-icon-prepend"></i> Update Buku
                        </button>
                        <a href="{{ route('buku.index') }}" class="btn btn-light btn-icon-text" onclick="btnLoading(this)">
                            <i class="mdi mdi-arrow-left btn-icon-prepend"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection