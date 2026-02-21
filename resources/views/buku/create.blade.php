@extends('layouts.app')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-book-plus"></i>
        </span> Tambah Koleksi Buku
    </h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('buku.index') }}" onclick="btnLoading(this)">Buku</a></li>
            <li class="breadcrumb-item active" aria-current="page">Tambah Baru</li>
        </ul>
    </nav>
</div>

<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Informasi Detail Buku</h4>
                <p class="card-description"> Pilih kategori untuk menghasilkan kode buku otomatis. </p>
                
                <form class="forms-sample" action="{{ route('buku.store') }}" method="POST" onsubmit="btnLoading(document.getElementById('btnSubmitBuku'))">
                    @csrf
                    
                    <div class="form-group">
                        <label for="idkategori">Kategori</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-gradient-primary text-white">
                                    <i class="mdi mdi-tag-multiple"></i>
                                </span>
                            </div>
                            <select class="form-control" id="selectKategori" name="idkategori" required>
    <option value="">-- Pilih Kategori --</option>
    @foreach($kategoris as $kategori)
        <option value="{{ $kategori->id }}" data-nama="{{ $kategori->nama_kategori }}">
            {{ $kategori->nama_kategori }}
        </option>
    @endforeach
</select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="kode">Kode Buku (Otomatis)</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-gradient-primary text-white">
                                    <i id="iconKode" class="mdi mdi-barcode"></i>
                                </span>
                            </div>
                            <input type="text" class="form-control" id="inputKode" name="kode" placeholder="Otomatis terisi..." readonly required>
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
                            <input type="text" class="form-control" id="judul" name="judul" placeholder="Judul Lengkap" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="pengarang">Pengarang</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-gradient-primary text-white">
                                    <i class="mdi mdi-account-edit"></i>
                                </span>
                            </div>
                            <input type="text" class="form-control" id="pengarang" name="pengarang" placeholder="Nama Penulis" required>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" id="btnSubmitBuku" class="btn btn-gradient-primary me-2 btn-icon-text">
                            <i class="mdi mdi-file-check btn-icon-prepend"></i> Simpan Buku
                        </button>
                        <a href="{{ route('buku.index') }}" class="btn btn-light btn-icon-text" onclick="btnLoading(this)">
                            <i class="mdi mdi-arrow-left btn-icon-prepend"></i> Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectKategori = document.getElementById('selectKategori');
        const inputKode = document.getElementById('inputKode');
        const iconKode = document.getElementById('iconKode');

        if(selectKategori) {
            selectKategori.addEventListener('change', function() {
                const idKategori = this.value;
                
                if (idKategori) {
                    inputKode.value = "Menghasilkan kode...";
                    iconKode.className = "mdi mdi-refresh mdi-spin"; // Efek putar pada icon
                    
                    fetch("{{ url('get-next-kode') }}/" + idKategori)
                        .then(response => response.json())
                        .then(data => {
                            inputKode.value = data.kode;
                            iconKode.className = "mdi mdi-barcode"; // Kembalikan icon semula
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            inputKode.value = "Gagal memuat kode";
                            iconKode.className = "mdi mdi-alert-circle text-danger";
                        });
                } else {
                    inputKode.value = "";
                    iconKode.className = "mdi mdi-barcode";
                }
            });
        }
    });
</script>
@endsection