@extends('layouts.app')

@section('content')
<div class="page-header">
  <h3 class="page-title"> Tambah Buku </h3>
</div>
<div class="row">
  <div class="col-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <form class="forms-sample" action="{{ route('buku.store') }}" method="POST">
          @csrf
          <div class="form-group">
            <label for="kode">Kode Buku</label>
            <input type="text" class="form-control" id="kode" name="kode" placeholder="Kode" required>
          </div>
          <div class="form-group">
            <label for="judul">Judul Buku</label>
            <input type="text" class="form-control" id="judul" name="judul" placeholder="Judul" required>
          </div>
          <div class="form-group">
            <label for="pengarang">Pengarang</label>
            <input type="text" class="form-control" id="pengarang" name="pengarang" placeholder="Pengarang" required>
          </div>
<div class="form-group">
    <label for="idkategori">Kategori</label>
    <select class="form-control" id="idkategori" name="idkategori" required>
        <option value="">-- Pilih Kategori --</option>
        @foreach($kategoris as $kategori)
            <option value="{{ $kategori->idkategori }}">{{ $kategori->nama_kategori }}</option>
        @endforeach
    </select>
</div>
          <button type="submit" class="btn btn-gradient-primary me-2">Submit</button>
          <a href="{{ route('buku.index') }}" class="btn btn-light">Cancel</a>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection