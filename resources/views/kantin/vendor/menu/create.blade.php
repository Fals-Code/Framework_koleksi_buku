@extends('layouts.app')

@section('content')
<div class="page-header">
    <h3 class="page-title"> Tambah Menu Baru </h3>
</div>

<div class="row">
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <form class="forms-sample" action="{{ route('vendor.menu.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="nama_makanan">Nama Makanan/Minuman</label>
                        <input type="text" class="form-control" name="nama_makanan" id="nama_makanan" placeholder="Contoh: Nasi Goreng" required>
                    </div>
                    <div class="form-group">
                        <label for="deskripsi">Deskripsi (Opsional)</label>
                        <textarea class="form-control" name="deskripsi" id="deskripsi" rows="4"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="harga">Harga (Rp)</label>
                        <input type="number" class="form-control" name="harga" id="harga" placeholder="15000" required>
                    </div>

                    <div class="form-group">
                        <label for="stok">Stok Awal</label>
                        <input type="number" class="form-control" name="stok" id="stok" placeholder="50" required>
                    </div>
                    <div class="form-group">
                        <label>Foto Menu</label>
                        <input type="file" name="foto" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-gradient-primary me-2">Simpan Menu</button>
                    <a href="{{ route('vendor.menu.index') }}" class="btn btn-light">Batal</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
