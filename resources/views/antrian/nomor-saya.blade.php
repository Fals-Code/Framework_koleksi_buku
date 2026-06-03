@extends('layouts.app')

@push('style-page')
<style>
    /* Sembunyikan elemen admin untuk halaman publik */
    .navbar, .sidebar, .footer { display: none !important; }
    .main-panel { width: 100% !important; padding: 0 !important; min-height: 100vh !important; }
    .page-body-wrapper { padding-top: 0 !important; }
    .content-wrapper { background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); display: flex; align-items: center; justify-content: center; min-height: 100vh; }

    .ticket-card {
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        padding: 50px 40px;
        width: 100%;
        max-width: 450px;
        text-align: center;
        position: relative;
        overflow: hidden;
        animation: popIn 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
    }

    .ticket-card::before {
<<<<<<< HEAD
=======
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 6px;
        background: linear-gradient(135deg, #b66dff 0%, #6a11cb 100%);
    }

>>>>>>> 6971a8567b4f20cdd3de32b96134e7267a53c467
    @keyframes popIn {
        from { transform: scale(0.8); opacity: 0; }
        to { transform: scale(1); opacity: 1; }
    }

    .number-display {
        font-size: 80px;
        font-weight: 900;
        background: linear-gradient(135deg, #b66dff 0%, #6a11cb 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        line-height: 1.2;
    }

    .print-btn {
        background: linear-gradient(135deg, #b66dff 0%, #6a11cb 100%);
        color: white;
        border: none;
        padding: 12px 20px;
        border-radius: 4px;
        font-weight: bold;
        margin-top: 20px;
    }
    .print-btn:hover {
        opacity: 0.9;
        color: white;
    }
</style>
@endpush

@section('content')
<div class="ticket-card">
    <h4 class="text-uppercase text-muted fw-bold mb-2">Nomor Antrian Anda</h4>
    
    <div class="number-display">
        {{ $antrian->nomor_antrian }}
    </div>
    
    <h5 class="fw-bold mt-4" style="color: #333;">{{ $antrian->nama_pengunjung }}</h5>
    @if($antrian->nim)
        <p class="text-muted mb-1">{{ $antrian->nim }}</p>
    @endif
    <p class="badge bg-gradient-primary rounded-pill px-3 py-2 mt-2">{{ $antrian->keperluan }}</p>
    
    <div class="mt-4 pt-4 border-top">
        <small class="text-muted d-block">Terdaftar pada: {{ $antrian->waktu_daftar->format('d M Y, H:i') }}</small>
        <small class="text-muted d-block mt-1">Silakan tunggu panggilan di ruang tunggu.</small>
    </div>

    <button class="print-btn w-100" onclick="window.print()">
        <i class="mdi mdi-printer me-2"></i> Cetak Tiket
    </button>
</div>
@endsection
