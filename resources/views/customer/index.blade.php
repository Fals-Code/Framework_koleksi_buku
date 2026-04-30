@extends('layouts.app')

@section('content')
<style>
    .content-wrapper { animation: fadeIn 0.6s ease-out; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

    .customer-card {
        border: none !important;
        border-radius: 20px !important;
        box-shadow: 0 8px 25px rgba(0,0,0,0.03) !important;
        background: #ffffff;
    }

    .table-modern thead th {
        background: #fcfcfc;
        border-bottom: 2px solid #f0f0f0 !important;
        color: #888;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 11px;
        letter-spacing: 1px;
    }

    .customer-photo {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 12px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }

    .empty-state {
        padding: 50px;
        text-align: center;
    }
    .empty-state i {
        font-size: 80px;
        color: #e0e0e0;
    }
</style>

<div class="page-header flex-wrap">
    <div class="header-left">
        <h3 class="page-title text-dark fw-bold">
            <span class="page-title-icon bg-gradient-primary text-white me-2 shadow-sm">
                <i class="mdi mdi-account-group"></i>
            </span> Data Customer
        </h3>
    </div>
    <div class="header-right d-flex flex-wrap mt-2 mt-sm-0">
        <a href="{{ route('customer.create1') }}" class="btn btn-gradient-primary btn-icon-text fw-bold shadow-sm rounded-pill px-4 me-2">
            <i class="mdi mdi-database-plus btn-icon-prepend"></i> Tambah (BLOB)
        </a>
        <a href="{{ route('customer.create2') }}" class="btn btn-gradient-info btn-icon-text fw-bold shadow-sm rounded-pill px-4">
            <i class="mdi mdi-file-image btn-icon-prepend"></i> Tambah (FILE)
        </a>
    </div>
</div>

<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card customer-card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-modern align-middle">
                        <thead>
                            <tr>
                                <th width="50">No</th>
                                <th>Foto</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Telepon</th>
                                <th>Dibuat</th>
                                <th width="100">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($customers as $customer)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    @if($customer->foto_blob)
                                        <img src="{{ route('customer.blob', $customer->id) }}" alt="Foto Blob" class="customer-photo">
                                    @elseif($customer->foto_path)
                                        <img src="{{ asset('storage/' . $customer->foto_path) }}" alt="Foto Path" class="customer-photo">
                                    @else
                                        <div class="bg-light d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; border-radius: 12px;">
                                            <i class="mdi mdi-account-circle text-muted" style="font-size: 30px;"></i>
                                        </div>
                                    @endif
                                </td>
                                <td class="fw-bold text-dark">{{ $customer->nama }}</td>
                                <td>{{ $customer->email ?? '-' }}</td>
                                <td>
                                    <span class="badge bg-light text-dark border">
                                        {{ $customer->telepon ?? '-' }}
                                    </span>
                                </td>
                                <td class="text-muted small">
                                    {{ $customer->created_at->format('d M Y') }}
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-outline-secondary rounded-pill">
                                        <i class="mdi mdi-pencil"></i>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7">
                                    <div class="empty-state">
                                        <i class="mdi mdi-account-off-outline"></i>
                                        <h5 class="mt-3 text-muted">Belum ada data customer</h5>
                                        <p class="text-muted small">Silakan tambahkan data customer melalui tombol di atas.</p>
                                    </div>
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
@endsection
