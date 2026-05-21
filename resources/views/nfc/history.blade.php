@extends('layouts.app')

@section('content')
<div class="page-header flex-wrap">
    <div class="header-left">
        <h3 class="page-title text-dark fw-bold">
            <span class="page-title-icon bg-gradient-primary text-white me-2 shadow-sm">
                <i class="mdi mdi-history"></i>
            </span> Riwayat Transaksi NFC
        </h3>
    </div>
</div>

<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card" style="border-radius: 20px;">
            <div class="card-body p-4">
                <ul class="nav nav-pills mb-4" id="historyTabs" role="tablist">
                    <li class="nav-item">
                        <button class="nav-link active rounded-pill px-4" data-bs-toggle="pill" data-bs-target="#tab-peminjaman" type="button">Riwayat Peminjaman</button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link rounded-pill px-4" data-bs-toggle="pill" data-bs-target="#tab-kunjungan" type="button">Riwayat Kunjungan</button>
                    </li>
                </ul>

                <div class="tab-content" id="historyTabContent">
                    <!-- Tab Peminjaman -->
                    <div class="tab-pane fade show active" id="tab-peminjaman">
                        <div class="table-responsive">
                            <table class="table table-hover" id="peminjamanTable">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Tanggal Pinjam</th>
                                        <th>Peminjam (Kartu)</th>
                                        <th>Buku</th>
                                        <th>Status</th>
                                        <th>Tanggal Kembali</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($peminjamans as $pinjam)
                                    <tr>
                                        <td>{{ $pinjam->tanggal_pinjam->format('d M Y H:i') }}</td>
                                        <td>
                                            <div class="fw-bold">{{ $pinjam->nfcCard->nama_anggota }}</div>
                                            <small class="text-muted">{{ $pinjam->nfcCard->serial_number }}</small>
                                        </td>
                                        <td>{{ $pinjam->buku->judul }}</td>
                                        <td>
                                            @if($pinjam->status === 'dipinjam')
                                                <span class="badge bg-warning text-dark rounded-pill">Dipinjam</span>
                                            @else
                                                <span class="badge bg-success rounded-pill">Dikembalikan</span>
                                            @endif
                                        </td>
                                        <td>{{ $pinjam->tanggal_kembali ? $pinjam->tanggal_kembali->format('d M Y H:i') : '-' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Tab Kunjungan -->
                    <div class="tab-pane fade" id="tab-kunjungan">
                        <div class="table-responsive">
                            <table class="table table-hover" id="kunjunganTable">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Anggota (Kartu)</th>
                                        <th>Waktu Masuk</th>
                                        <th>Waktu Keluar</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($kunjungans as $kunj)
                                    <tr>
                                        <td>{{ $kunj->waktu_masuk->format('d M Y') }}</td>
                                        <td>
                                            <div class="fw-bold">{{ $kunj->nfcCard->nama_anggota }}</div>
                                            <small class="text-muted">{{ $kunj->nfcCard->serial_number }}</small>
                                        </td>
                                        <td><span class="text-success fw-bold"><i class="mdi mdi-login me-1"></i> {{ $kunj->waktu_masuk->format('H:i:s') }}</span></td>
                                        <td>
                                            @if($kunj->waktu_keluar)
                                                <span class="text-danger fw-bold"><i class="mdi mdi-logout me-1"></i> {{ $kunj->waktu_keluar->format('H:i:s') }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(!$kunj->waktu_keluar)
                                                <span class="badge bg-info rounded-pill">Masih di dalam</span>
                                            @else
                                                <span class="badge bg-secondary rounded-pill">Selesai</span>
                                            @endif
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
    </div>
</div>
@endsection

@push('script-page')
<script>
    $(document).ready(function() {
        $('#peminjamanTable').DataTable({ "language": { "url": "//cdn.datatables.net/plug-ins/1.11.5/i18n/id.json" }});
        $('#kunjunganTable').DataTable({ "language": { "url": "//cdn.datatables.net/plug-ins/1.11.5/i18n/id.json" }});
    });
</script>
@endpush
