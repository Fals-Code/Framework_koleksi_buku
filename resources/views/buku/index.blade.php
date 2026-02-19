@extends('layouts.app')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-book-open-page-variant"></i>
        </span> Kelola Koleksi Buku
    </h3>
    <nav aria-label="breadcrumb">
        <a href="{{ route('buku.create') }}" class="btn btn-gradient-primary btn-icon-text" onclick="btnLoading(this)">
            <i class="mdi mdi-plus btn-icon-prepend"></i> Tambah Buku
        </a>
    </nav>
</div>

<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Daftar Judul Buku</h4>
                <p class="card-description"> Kelola informasi buku, pengarang, dan kategori pustaka Anda. </p>

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="bg-light">
                            <tr>
                                <th class="font-weight-bold"> No </th>
                                <th class="font-weight-bold"> Kode </th>
                                <th class="font-weight-bold"> Judul Buku </th>
                                <th class="font-weight-bold"> Pengarang </th>
                                <th class="font-weight-bold"> Kategori </th>
                                <th class="font-weight-bold text-center"> Aksi </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($bukus as $key => $buku)
                            <tr>
                                <td> {{ $key+1 }} </td>
                                <td class="text-primary font-weight-bold"> {{ $buku->kode }} </td>
                                <td class="text-wrap" style="max-width: 250px;"> {{ $buku->judul }} </td>
                                <td> 
                                    <i class="mdi mdi-account text-muted me-1"></i> {{ $buku->pengarang }} 
                                </td>
                                <td>
                                    <label class="badge badge-gradient-info text-dark">
                                        {{ $buku->kategori->nama_kategori }}
                                    </label>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center">
                                        <a href="{{ route('buku.edit', $buku->idbuku) }}" class="btn btn-gradient-warning btn-sm btn-icon-text me-2" onclick="btnLoading(this)">
                                            <i class="mdi mdi-pencil btn-icon-prepend"></i> Edit
                                        </a>
                                        <form action="{{ route('buku.destroy', $buku->idbuku) }}" method="POST" class="d-inline" onsubmit="return confirmDeleteBuku(this)">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-gradient-danger btn-sm btn-icon-text">
                                                <i class="mdi mdi-delete btn-icon-prepend"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-4 text-muted small">
                    Menampilkan total <strong>{{ $bukus->count() }}</strong> judul buku terdaftar.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script-page')
<script>
    function confirmDeleteBuku(form) {
        if (confirm('Apakah Anda yakin ingin menghapus buku ini?')) {
            const btn = form.querySelector('button[type="submit"]');
            btnLoading(btn);
            return true;
        }
        return false;
    }
</script>
@endpush