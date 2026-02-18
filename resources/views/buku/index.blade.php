@extends('layouts.app')

@section('content')
<div class="page-header">
  <h3 class="page-title"> Daftar Buku </h3>
  <nav aria-label="breadcrumb">
    <a href="{{ route('buku.create') }}" class="btn btn-gradient-primary btn-fw">+ Tambah Buku</a>
  </nav>
</div>
<div class="row">
  <div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <table class="table table-bordered">
          <thead>
            <tr>
              <th> No </th>
              <th> Kode </th>
              <th> Judul </th>
              <th> Pengarang </th>
              <th> Kategori </th>
              <th> Aksi </th>
            </tr>
          </thead>
          <tbody>
            @foreach($bukus as $key => $buku)
            <tr>
              <td> {{ $key+1 }} </td>
              <td> {{ $buku->kode }} </td>
              <td> {{ $buku->judul }} </td>
              <td> {{ $buku->pengarang }} </td>
              <td> {{ $buku->kategori->nama_kategori }} </td>
              <td>
                <a href="{{ route('buku.edit', $buku->idbuku) }}" class="btn btn-gradient-warning btn-sm">Edit</a>
                <form action="{{ route('buku.destroy', $buku->idbuku) }}" method="POST" style="display:inline-block">
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