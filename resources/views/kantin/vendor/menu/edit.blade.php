@extends('layouts.app')

@section('content')
<div class="page-header">
    <h3 class="page-title"> Edit Menu: {{ $menu->nama_makanan }} </h3>
</div>

<div class="row">
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <form class="forms-sample" action="{{ route('vendor.menu.update', $menu->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="nama_makanan">Nama Makanan/Minuman</label>
                        <input type="text" class="form-control" name="nama_makanan" id="nama_makanan" value="{{ $menu->nama_makanan }}" required>
                    </div>
                    <div class="form-group">
                        <label for="deskripsi">Deskripsi (Opsional)</label>
                        <textarea class="form-control" name="deskripsi" id="deskripsi" rows="4">{{ $menu->deskripsi }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="harga">Harga (Rp)</label>
                        <input type="number" class="form-control" name="harga" id="harga" value="{{ $menu->harga }}" required>
                    </div>

                    <div class="form-group">
                        <label for="stok">Stok</label>
                        <input type="number" class="form-control" name="stok" id="stok" value="{{ $menu->stok }}" required>
                    </div>
                    <div class="form-group">
                        <label>Foto Menu (Biarkan kosong jika tidak ingin mengubah)</label>
                        <input type="file" name="foto" class="form-control">
                        @if($menu->foto)
                        <div class="mt-2">
                            <img src="{{ asset('storage/' . $menu->foto) }}" width="100" class="rounded">
                        </div>
                        @endif
                    </div>
                    <button type="submit" class="btn btn-gradient-primary me-2">Update Menu</button>
                    <a href="{{ route('vendor.menu.index') }}" class="btn btn-light">Batal</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
