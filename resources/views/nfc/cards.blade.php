@extends('layouts.app')

@section('content')
<div class="page-header flex-wrap">
    <div class="header-left">
        <h3 class="page-title text-dark fw-bold">
            <span class="page-title-icon bg-gradient-primary text-white me-2 shadow-sm">
                <i class="mdi mdi-card-account-details"></i>
            </span> Manajemen Kartu NFC
        </h3>
    </div>
    <div class="header-right d-flex flex-wrap mt-2 mt-sm-0">
        <a href="{{ route('nfc.write') }}" class="btn btn-gradient-primary mt-2 mt-sm-0 btn-icon-text">
            <i class="mdi mdi-plus-circle btn-icon-prepend"></i> Registrasi Kartu Baru
        </a>
    </div>
</div>

<div class="card" style="border-radius: 20px;">
    <div class="card-body p-4">
        <div class="table-responsive">
            <table class="table table-hover" id="cardsTable">
                <thead class="bg-light">
                    <tr>
                        <th>No</th>
                        <th>Serial Number</th>
                        <th>Nama Anggota</th>
                        <th>NIM</th>
                        <th>Status</th>
                        <th>Tanggal Registrasi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($cards as $index => $card)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><span class="badge bg-dark rounded-pill font-monospace px-3">{{ $card->serial_number }}</span></td>
                        <td class="fw-bold">{{ $card->nama_anggota }}</td>
                        <td>{{ $card->nim ?? '-' }}</td>
                        <td>
                            @if($card->is_active)
                                <span class="badge bg-success rounded-pill px-3">Aktif</span>
                            @else
                                <span class="badge bg-danger rounded-pill px-3">Non-Aktif</span>
                            @endif
                        </td>
                        <td class="text-muted small">{{ $card->created_at->format('d M Y H:i') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">Belum ada kartu NFC yang terdaftar.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('script-page')
<script>
    $(document).ready(function() {
        $('#cardsTable').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.11.5/i18n/id.json"
            }
        });
    });
</script>
@endpush
