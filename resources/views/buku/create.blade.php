@extends('layouts.app')

@section('content')
<style>
    /* Animasi Fade In */
    .fade-in-up {
        animation: fadeInUp 0.5s ease-out;
    }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .card-modern {
        border: none;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
    }

    .form-section-title {
        border-left: 4px solid #b66dff;
        padding-left: 15px;
        margin-bottom: 25px;
        font-weight: bold;
        color: #343a40;
    }

    .input-group-text-custom {
        border: none;
        width: 45px;
        justify-content: center;
        border-radius: 10px 0 0 10px !important;
    }

    .form-control-modern {
        border: 1px solid #ebedf2;
        border-radius: 0 10px 10px 0 !important;
        padding: 12px 15px;
        transition: all 0.3s;
    }

    .form-control-modern:focus {
        border-color: #b66dff;
        box-shadow: 0 0 0 0.2rem rgba(182, 109, 255, 0.15);
    }

    .readonly-custom {
        background-color: #f8f9fa !important;
        font-weight: bold;
        color: #b66dff;
        letter-spacing: 1px;
    }

    .info-box {
        background: linear-gradient(135deg, #3a3f51 0%, #212529 100%);
        color: white;
        border-radius: 15px;
        padding: 20px;
        height: 100%;
    }
</style>

<div class="page-header fade-in-up">
    <h3 class="page-title text-primary fw-bold">
        <span class="page-title-icon bg-gradient-primary text-white me-2 shadow-sm">
            <i class="mdi mdi-book-plus"></i>
        </span> Katalog Digital <span class="text-muted fw-light">/ Entri Buku Baru</span>
    </h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb border-0">
            <li class="breadcrumb-item"><a href="{{ route('buku.index') }}" class="text-decoration-none text-primary">Koleksi</a></li>
            <li class="breadcrumb-item active" aria-current="page">Registrasi Baru</li>
        </ol>
    </nav>
</div>

<div class="row fade-in-up">
    <div class="col-md-8 grid-margin stretch-card">
        <div class="card card-modern">
            <div class="card-body p-5">
                <h4 class="form-section-title">Data Bibliografi</h4>
                <p class="text-muted mb-4 small">Lengkapi formulir di bawah ini dengan informasi buku yang akurat.</p>
                
                <form class="forms-sample" action="{{ route('buku.store') }}" method="POST" id="formTambahBuku" onsubmit="btnLoading(document.getElementById('btnSubmitBuku'))">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="fw-bold mb-2">Kategori Koleksi</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-gradient-primary text-white input-group-text-custom">
                                        <i class="mdi mdi-tag-multiple"></i>
                                    </span>
                                    <select class="form-control form-control-modern" id="selectKategori" name="idkategori" required>
                                        <option value="">-- Pilih Kategori --</option>
                                        @foreach($kategoris as $kategori)
                                            <option value="{{ $kategori->id }}" data-nama="{{ $kategori->nama_kategori }}">
                                                {{ $kategori->nama_kategori }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="fw-bold mb-2">ID Register (Otomatis)</label>
                                <div class="input-group shadow-sm">
                                    <span class="input-group-text bg-dark text-white input-group-text-custom">
                                        <i id="iconKode" class="mdi mdi-barcode-scan"></i>
                                    </span>
                                    <input type="text" class="form-control form-control-modern readonly-custom" id="inputKode" name="kode" placeholder="Menunggu kategori..." readonly required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-4">
                        <label class="fw-bold mb-2">Judul Lengkap Buku</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 input-group-text-custom text-primary">
                                <i class="mdi mdi-format-title"></i>
                            </span>
                            <input type="text" class="form-control form-control-modern" id="judul" name="judul" placeholder="Contoh: Belajar Laravel 11 Pro Max" required>
                        </div>
                    </div>

                    <div class="form-group mb-5">
                        <label class="fw-bold mb-2">Nama Penulis / Pengarang</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 input-group-text-custom text-primary">
                                <i class="mdi mdi-account-edit"></i>
                            </span>
                            <input type="text" class="form-control form-control-modern" id="pengarang" name="pengarang" placeholder="Nama lengkap penulis..." required>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center border-top pt-4">
                        <a href="{{ route('buku.index') }}" class="btn btn-light btn-lg px-4 rounded-pill shadow-sm" onclick="btnLoading(this)">
                            <i class="mdi mdi-arrow-left me-1"></i> Kembali
                        </a>
                        <button type="submit" id="btnSubmitBuku" class="btn btn-gradient-primary btn-lg px-5 rounded-pill shadow">
                            <i class="mdi mdi-content-save-all me-1"></i> Simpan Koleksi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4 grid-margin stretch-card">
        <div class="info-box shadow-lg d-flex flex-column justify-content-between">
            <div>
                <h4 class="text-warning fw-bold mb-4"><i class="mdi mdi-robot me-2"></i>Smart Assistant</h4>
                <ul class="list-unstyled">
                    <li class="mb-4 d-flex">
                        <i class="mdi mdi-check-circle-outline text-success me-3 mdi-24px"></i>
                        <p class="small mb-0">Kode buku di-generate secara otomatis berdasarkan klasifikasi yang dipilih untuk menghindari duplikasi.</p>
                    </li>
                    <li class="mb-4 d-flex">
                        <i class="mdi mdi-shield-check-outline text-info me-3 mdi-24px"></i>
                        <p class="small mb-0">Pastikan penulisan judul menggunakan format <b>Title Case</b> untuk menjaga kerapihan database.</p>
                    </li>
                </ul>
            </div>
            
            <div class="p-3 rounded-3" style="background: rgba(255,255,255,0.05); border: 1px dashed rgba(255,255,255,0.2);">
                <p class="x-small mb-0 italic text-center text-muted">"Buku adalah jendela dunia, dan database adalah raknya."</p>
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
                    inputKode.value = "GEN-CODE-WAIT...";
                    iconKode.className = "mdi mdi-loading mdi-spin text-warning"; 
                    
                    fetch("{{ url('get-next-kode') }}/" + idKategori)
                        .then(response => response.json())
                        .then(data => {
                            setTimeout(() => {
                                inputKode.value = data.kode;
                                iconKode.className = "mdi mdi-check-decagram text-success";
                            }, 500);
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            inputKode.value = "SERVER ERROR";
                            iconKode.className = "mdi mdi-alert-circle text-danger";
                        });
                } else {
                    inputKode.value = "";
                    iconKode.className = "mdi mdi-barcode-scan";
                }
            });
        }
    });
</script>
@endsection