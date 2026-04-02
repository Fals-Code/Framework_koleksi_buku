@extends('layouts.app')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-format-list-bulleted"></i>
        </span> Daftar Menu
    </h3>
    <nav aria-label="breadcrumb">
        <a href="{{ route('vendor.menu.create') }}" class="btn btn-gradient-primary btn-icon-text">
            <i class="mdi mdi-plus btn-icon-prepend"></i> Tambah Menu
        </a>
    </nav>
</div>

<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Menu Anda</h4>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th> Foto </th>
                                <th> Nama Menu </th>
                                <th> Harga </th>
                                <th> Stok </th>
                                <th> Aksi </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($menus as $menu)
                            <tr>
                                <td>
                                    <img src="{{ $menu->foto ? asset('storage/' . $menu->foto) : asset('assets/images/no-image.jpg') }}" class="me-2" alt="image" style="width: 50px; height: 50px; border-radius: 5px;">
                                </td>
                                <td> {{ $menu->nama_makanan }} </td>
                                <td> Rp {{ number_format($menu->harga, 0, ',', '.') }} </td>
                                <td> {{ $menu->stok }} </td>
                                <td>
                                    <a href="{{ route('vendor.menu.edit', $menu->id) }}" class="btn btn-sm btn-outline-info">Edit</a>
                                    <button class="btn btn-sm btn-outline-danger" onclick="confirmDelete('{{ route('vendor.menu.destroy', $menu->id) }}')">Hapus</button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
