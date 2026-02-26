@extends('layouts.app')

@section('content')
<style>
    .table-container {
        background: white;
        border-radius: 15px;
        overflow: hidden;
    }

    .custom-table thead th {
        background-color: #f8f9fa;
        border-top: none !important;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 1px;
        font-weight: 700;
        color: #343a40;
        padding: 20px 15px !important;
    }

    .custom-table tbody tr {
        transition: all 0.3s ease;
    }

    .custom-table tbody tr:hover {
        background-color: rgba(182, 109, 255, 0.05) !important;
        transform: scale(1.002);
    }

    .action-btn {
        transition: all 0.2s;
        border-radius: 8px !important;
        width: 35px;
        height: 35px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .action-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }

    .search-wrapper {
        position: relative;
        max-width: 300px;
    }

    .search-wrapper i {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #b66dff;
    }

    .search-input {
        padding-left: 35px !important;
        border-radius: 20px !important;
        border: 1px solid #ebedf2 !important;
    }

    .bg-light-primary {
        background-color: #f3e8ff;
    }
</style>

<div class="page-header flex-wrap">
    <div class="header-left">
        <h3 class="page-title text-primary fw-bold">
            <span class="page-title-icon bg-gradient-primary text-white me-2 shadow-sm">
                <i class="mdi mdi-format-list-bulleted"></i>
            </span> Koleksi Kategori
        </h3>
        <p class="text-muted small mt-2 mb-0">Total terdapat <strong>{{ $kategoris->count() }}</strong> klasifikasi dalam database.</p>
    </div>
    <div class="header-right d-flex align-items-center mt-md-0 mt-3">
        <a href="{{ route('kategori.create') }}" class="btn btn-gradient-primary btn-icon-text shadow-sm px-4" onclick="btnLoading(this)">
            <i class="mdi mdi-plus-circle btn-icon-prepend"></i> Tambah Kategori 
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="d-md-flex justify-content-between align-items-center mb-4">
                    <h4 class="card-title mb-3 mb-md-0">Database Master Kategori</h4>
                    <div class="search-wrapper">
                        <i class="mdi mdi-magnify"></i>
                        <input type="text" id="searchInput" class="form-control search-input" placeholder="Cari kategori...">
                    </div>
                </div>

                <div class="table-responsive table-container">
                    <table class="table custom-table" id="categoryTable">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 100px;">No</th>
                                <th>KATEGORI</th>
                                <th class="text-center">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($kategoris as $key => $kategori)
                            <tr>
                                <td class="text-center">
                                    <span class="badge badge-light text-dark fw-bold rounded-pill border">
                                        {{ str_pad($key + 1, 2, '0', STR_PAD_LEFT) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-light-primary p-2 rounded-3 me-3 text-primary shadow-sm">
                                            <i class="mdi mdi-bookmark-outline"></i>
                                        </div>
                                        <div>
                                            <span class="fw-bold d-block text-dark">{{ $kategori->nama_kategori }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="{{ route('kategori.edit', $kategori->id ?? $kategori->idkategori) }}" 
                                           class="btn btn-inverse-warning btn-icon action-btn" 
                                           title="Edit Data"
                                           onclick="btnLoading(this)">
                                            <i class="mdi mdi-pencil-outline"></i>
                                        </a>
                                        
                                        <form action="{{ route('kategori.destroy', $kategori->id ?? $kategori->idkategori) }}" 
                                              method="POST" 
                                              class="d-inline" 
                                              onsubmit="return confirm('Sistem Keamanan: Anda yakin ingin menghapus data ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-inverse-danger btn-icon action-btn" title="Hapus Data">
                                                <i class="mdi mdi-trash-can-outline"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center py-5">
                                    <i class="mdi mdi-database-off mdi-48px text-muted"></i>
                                    <h5 class="text-muted mt-3">Data Kosong</h5>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Script Search Sederhana --}}
<script>
    document.getElementById('searchInput').addEventListener('keyup', function() {
        let filter = this.value.toUpperCase();
        let rows = document.querySelector("#categoryTable tbody").rows;
        
        for (let i = 0; i < rows.length; i++) {
            let col = rows[i].cells[1].textContent.toUpperCase();
            if (col.indexOf(filter) > -1) {
                rows[i].style.display = "";
            } else {
                rows[i].style.display = "none";
            }      
        }
    });
</script>
@endsection