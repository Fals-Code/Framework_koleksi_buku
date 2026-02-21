@extends('layouts.app')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-format-list-bulleted"></i>
        </span> Manajemen Kategori
    </h3>
    <nav aria-label="breadcrumb">
        <a href="{{ route('kategori.create') }}" class="btn btn-gradient-primary btn-icon-text">
            <i class="mdi mdi-plus btn-icon-prepend"></i> Tambah Kategori
        </a>
    </nav>
</div>

<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Daftar Kategori Buku</h4>
                <p class="card-description"> Kelola kategori untuk mempermudah pengelompokan koleksi pustaka. </p>

                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr class="bg-light">
                                <th style="width: 10%"> No </th>
                                <th style="width: 60%"> Nama Kategori </th>
                                <th style="width: 30%" class="text-center"> Aksi </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($kategoris as $key => $kategori)
                            <tr>
                                <td> {{ $key+1 }} </td>
                                <td class="font-weight-bold"> 
                                    <i class="mdi mdi-tag-outline text-primary me-2"></i> {{ $kategori->nama_kategori }} 
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center">
                                        <a href="{{ route('kategori.edit', $kategori->id) }}" class="btn btn-gradient-warning btn-sm btn-icon-text me-2">
                                            <i class="mdi mdi-pencil btn-icon-prepend"></i> Edit
                                        </a>
                                        
                                        <form action="{{ route('kategori.destroy', $kategori->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-gradient-danger btn-sm btn-icon-text">
                                                <i class="mdi mdi-delete btn-icon-prepend"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted">Belum ada data kategori.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 text-muted small">
                    Total Kategori: <strong>{{ $kategoris->count() }}</strong> data ditemukan.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection