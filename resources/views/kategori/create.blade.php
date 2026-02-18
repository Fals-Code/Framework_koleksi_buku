@extends('layouts.app')

@section('content')
<div class="page-header">
  <h3 class="page-title"> Tambah Kategori </h3>
</div>
<div class="row">
  <div class="col-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <form class="forms-sample" action="{{ route('kategori.store') }}" method="POST">
          @csrf
          <div class="form-group">
            <label for="nama_kategori">Nama Kategori</label>
            <input type="text" class="form-control" id="nama_kategori" name="nama_kategori" placeholder="Nama Kategori" required>
          </div>
          <button type="submit" class="btn btn-gradient-primary me-2">Submit</button>
          <a href="{{ route('kategori.index') }}" class="btn btn-light">Cancel</a>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection