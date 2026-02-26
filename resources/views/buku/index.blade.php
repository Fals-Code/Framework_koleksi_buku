@extends('layouts.app')

@section('content')
<style>
    .table-modern thead th {
        border-top: 0;
        border-bottom-width: 1px;
        font-weight: 700;
        font-size: 0.75rem;
        text-transform: uppercase;
        color: #4b49ac;
        background-color: #f8f9fa;
        padding: 15px !important;
    }

    .table-modern tbody td {
        padding: 18px 15px !important;
        vertical-align: middle;
        font-size: 0.875rem;
    }

    .book-title-cell {
        font-weight: 600;
        color: #343a40;
        line-height: 1.4;
    }

    .badge-category {
        padding: 0.5rem 0.8rem;
        border-radius: 6px;
        font-weight: 500;
        letter-spacing: 0.3px;
    }

    .btn-action {
        width: 35px;
        height: 35px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px !important;
        transition: all 0.2s;
    }

    .btn-action:hover {
        transform: translateY(-2px);
    }

    .code-tag {
        background: #f0f0ff;
        color: #b66dff;
        padding: 4px 8px;
        border-radius: 4px;
        font-family: 'Courier New', Courier, monospace;
        font-weight: bold;
    }
</style>

<div class="page-header flex-wrap">
    <div class="header-left">
        <h3 class="page-title text-primary fw-bold">
            <span class="page-title-icon bg-gradient-primary text-white me-2 shadow">
                <i class="mdi mdi-book-open-page-variant menu-icon"></i>
            </span> Manajemen Pustaka
        </h3>
        <p class="text-muted small mb-0 mt-1">Sistem informasi koleksi buku digital perpustakaan.</p>
    </div>
    <div class="header-right d-flex align-items-center mt-md-0 mt-3">
        <a href="{{ route('buku.create') }}" class="btn btn-gradient-primary btn-icon-text shadow-sm" onclick="btnLoading(this)">
            <i class="mdi mdi-book-plus btn-icon-prepend"></i> Registrasi Buku Baru 
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="card-title mb-0">Database Judul Terdaftar</h4>
                    <div class="search-box">
                        <div class="input-group input-group-sm">
                            <input type="text" class="form-control border-primary" placeholder="Cari judul atau pengarang...">
                            <button class="btn btn-primary btn-sm"><i class="mdi mdi-magnify"></i></button>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover table-modern">
                        <thead>
                            <tr>
                                <th style="width: 5%">No</th>
                                <th style="width: 15%">Kode Buku</th>
                                <th style="width: 30%">Detail Buku</th>
                                <th style="width: 20%">Penulis</th>
                                <th style="width: 15%">Kategori</th>
                                <th class="text-center" style="width: 15%">Opsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bukus as $key => $buku)
                            <tr>
                                <td>{{ $key+1 }}</td>
                                <td>
                                    <span class="code-tag">{{ $buku->kode }}</span>
                                </td>
                                <td>
                                    <div class="book-title-cell text-wrap" style="max-width: 280px;">
                                        {{ $buku->judul }}
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-light-info rounded-circle me-2 d-flex align-items-center justify-content-center" style="width: 30px; height: 30px;">
                                            <i class="mdi mdi-account text-info small"></i>
                                        </div>
                                        <span>{{ $buku->pengarang }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-category {{ $buku->kategori ? 'badge-gradient-info' : 'badge-gradient-secondary text-white' }}">
                                        <i class="mdi mdi-tag-outline me-1"></i>
                                        {{ $buku->kategori->nama_kategori ?? 'Uncategorized' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="{{ route('buku.edit', $buku->id) }}" 
                                           class="btn btn-inverse-warning btn-action" 
                                           title="Edit Data"
                                           onclick="btnLoading(this)">
                                            <i class="mdi mdi-pencil"></i>
                                        </a>
                                        
                                        <form action="{{ route('buku.destroy', $buku->id) }}" 
                                              method="POST" 
                                              onsubmit="return confirmDeleteBuku(this)">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-inverse-danger btn-action" title="Hapus Data">
                                                <i class="mdi mdi-trash-can"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="mdi mdi-book-open-variant mdi-48px d-block mb-2 opacity-25"></i>
                                    <p>Belum ada data buku dalam database ini.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-4 pt-3 border-top">
                    <p class="text-muted small mb-3 mb-md-0">
                        Menampilkan <strong>{{ $bukus->count() }}</strong> entri buku dalam sistem.
                    </p>
                    <div class="pagination-container">
                        @if(method_exists($bukus, 'links'))
                            {{ $bukus->links() }}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script-page')
<script>
    function confirmDeleteBuku(form) {
        if (confirm('Sistem Keamanan: Anda yakin ingin menghapus data buku ini secara permanen?')) {
            const btn = form.querySelector('button[type="submit"]');
            btnLoading(btn);
            return true;
        }
        return false;
    }
</script>
@endpush