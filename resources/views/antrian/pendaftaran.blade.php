@extends('layouts.public-antrian')

@push('style-page')
<style>
    /* Sembunyikan elemen admin untuk halaman publik */
    .navbar, .sidebar, .footer { display: none !important; }
    .main-panel { width: 100% !important; padding: 0 !important; min-height: 100vh !important; }
    .page-body-wrapper { padding-top: 0 !important; }
    .content-wrapper { background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); display: flex; align-items: center; justify-content: center; min-height: 100vh; }

    /* Standard Card */
    .theme-card {
        background: #ffffff;
        border-radius: 15px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        padding: 40px;
        width: 100%;
        max-width: 500px;
        border-top: 5px solid #b66dff;
        animation: slideUp 0.6s ease forwards;
    }

    @keyframes slideUp {
        from { transform: translateY(20px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }

    .form-control {
        border: 1px solid #ebedf2;
        border-radius: 4px;
        padding: 12px 15px;
        font-size: 14px;
    }
    .form-control:focus {
        border-color: #b66dff;
        box-shadow: none;
    }
    .btn-gradient {
        background: linear-gradient(135deg, #b66dff 0%, #6a11cb 100%);
        color: white;
        border-radius: 4px;
        padding: 12px 15px;
        font-weight: bold;
        border: none;
    }
    .btn-gradient:hover {
        opacity: 0.9;
        color: white;
    }
</style>
@endpush

@section('content')
<div class="theme-card">
    <div class="text-center mb-4">
        <h2 class="fw-bold" style="color: #4a4a4a;">Pendaftaran Antrian</h2>
        <p class="text-muted">Silakan isi data diri Anda untuk mengambil nomor antrian</p>
    </div>

    <form action="{{ route('antrian.daftar') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label class="form-label fw-bold text-secondary">Nama Lengkap <span class="text-danger">*</span></label>
            <input type="text" name="nama_pengunjung" class="form-control" required placeholder="Contoh: Budi Santoso">
        </div>
        <div class="mb-4">
            <label class="form-label fw-bold text-secondary">NIM (Opsional)</label>
            <input type="text" name="nim" class="form-control" placeholder="Contoh: 1521115130...">
        </div>
        <div class="mb-4">
            <label class="form-label fw-bold text-secondary">Keperluan</label>
            <select name="keperluan" class="form-control">
                <option value="Pinjam Buku">Pinjam Buku</option>
                <option value="Kembalikan Buku">Kembalikan Buku</option>
                <option value="Administrasi">Administrasi</option>
                <option value="Lainnya">Lainnya</option>
            </select>
        </div>
        
        <button type="submit" class="btn btn-gradient w-100" onclick="btnLoading(this)">Ambil Nomor Antrian</button>
    </form>
</div>
@endsection
