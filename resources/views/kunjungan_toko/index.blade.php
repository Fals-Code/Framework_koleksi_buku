@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">List Toko (Geolocation)</h4>
                <p class="card-description">Daftar toko beserta titik koordinatnya.</p>
                
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Barcode</th>
                                <th>Nama Toko</th>
                                <th>Latitude</th>
                                <th>Longitude</th>
                                <th>Akurasi (m)</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tokos as $toko)
                            <tr>
                                <td>{{ $toko->barcode }}</td>
                                <td>{{ $toko->nama_toko }}</td>
                                <td>{{ $toko->latitude }}</td>
                                <td>{{ $toko->longitude }}</td>
                                <td>{{ $toko->accuracy }} m</td>
                                <td>
                                    <button class="btn btn-sm btn-info text-white" onclick="cetakBarcode('{{ $toko->barcode }}')">
                                        <i class="mdi mdi-printer"></i> Cetak Barcode
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">Belum ada data toko. Silakan input dari menu "Input Titik Awal Toko".</td>
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

@push('script-page')
<!-- Include barcode generator for cetak barcode -->
<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
<script>
    function cetakBarcode(barcode) {
        Swal.fire({
            title: 'Barcode Toko',
            html: '<svg id="barcode-canvas"></svg>',
            showCloseButton: true,
            showConfirmButton: true,
            confirmButtonText: '<i class="mdi mdi-printer"></i> Print',
            didOpen: () => {
                JsBarcode("#barcode-canvas", barcode, {
                    format: "CODE128",
                    displayValue: true,
                    fontSize: 20
                });
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Feature print bisa ditambahkan disini
                Swal.fire('Fitur Print (Simulasi)', 'Barcode berhasil dikirim ke printer!', 'success');
            }
        });
    }
</script>
@endpush
