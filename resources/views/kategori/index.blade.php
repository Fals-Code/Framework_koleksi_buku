@extends('layouts.app')

@section('content')
<div class="page-header">
  <h3 class="page-title"> Daftar Kategori </h3>
  <nav aria-label="breadcrumb">
    <a href="{{ route('kategori.create') }}" class="btn btn-gradient-primary btn-fw">+ Tambah Kategori</a>
  </nav>
</div>
<div class="row">
  <div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <table class="table table-striped">
          <thead>
            <tr>
              <th> No </th>
              <th> Nama Kategori </th>
              <th> Aksi </th>
            </tr>
          </thead>
          <tbody>
            @foreach($kategoris as $key => $kategori)
            <tr>
              <td> {{ $key+1 }} </td>
              <td> {{ $kategori->nama_kategori }} </td>
              <td>
                <a href="{{ route('kategori.edit', $kategori->idkategori) }}" class="btn btn-gradient-warning btn-sm">Edit</a>
                <form action="{{ route('kategori.destroy', $kategori->idkategori) }}" method="POST" style="display:inline-block">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-gradient-danger btn-sm" onclick="return confirm('Yakin hapus?')">Hapus</button>
                </form>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection