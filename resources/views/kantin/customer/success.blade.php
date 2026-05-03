@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <!-- Premium Success Card -->
        <div class="card border-0 shadow-lg text-center overflow-hidden" style="border-radius: 35px; background: #fff;">
            <!-- Header Pattern -->
            <div style="height: 15px; background: linear-gradient(to right, #1e8e3e, #34a853);"></div>
            
            <div class="card-body p-5">
                <!-- Success Animated Icon -->
                <div class="mb-4">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle" style="width: 120px; height: 120px; background: #e6f4ea;">
                        <i class="mdi mdi-check-all text-success" style="font-size: 70px; animation: bounceIn 0.8s cubic-bezier(0.175, 0.885, 0.32, 1.275);"></i>
                    </div>
                </div>

                <h2 class="fw-bold text-dark mb-2">Yeay! Pembayaran Berhasil</h2>
                <p class="text-muted fs-5 mb-5">Pesanan kamu sudah diteruskan ke <strong>{{ $pesanan->vendor->nama_warung }}</strong>. Silakan tunggu sebentar ya!</p>

                <div class="row g-4 text-start">
                    <!-- Order Info -->
                    <div class="col-md-6">
                        <div class="p-4 bg-light rounded-4 h-100 border">
                            <h6 class="text-muted small fw-bold mb-3 uppercase tracking-wider">DETAIL TRANSAKSI</h6>
                            
                            <div class="mb-3">
                                <label class="x-small text-muted d-block mb-1">NOMOR PESANAN</label>
                                <span class="fw-bold text-dark fs-5">#{{ $pesanan->nomor_pesanan }}</span>
                            </div>
                            
                            <div class="mb-3">
                                <label class="x-small text-muted d-block mb-1">PEMESAN</label>
                                <span class="fw-bold text-dark">{{ $pesanan->nama_pelanggan }}</span>
                            </div>

                            <div class="mb-0">
                                <label class="x-small text-muted d-block mb-1">TOTAL PEMBAYARAN</label>
                                <span class="fw-bold text-primary fs-4">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- QR Code Area -->
                    <div class="col-md-6 text-center">
                        <div class="p-4 bg-white rounded-4 h-100 border shadow-sm d-flex flex-column align-items-center justify-content-center">
                            <h6 class="text-muted small fw-bold mb-3 uppercase tracking-wider">QR PENGAMBILAN</h6>
                            
                            <div class="p-3 bg-white border rounded-4 mb-3" style="box-shadow: 0 10px 20px rgba(0,0,0,0.05);">
                                <img src="{{ $qrCodeDataUri }}" 
                                     alt="QR Code" style="width: 150px; height: 150px; border-radius: 8px;">
                            </div>
                            
                            <p class="small text-muted fw-bold mb-0">Tunjukkan QR ini ke Penjual</p>
                        </div>
                    </div>
                </div>

                <!-- Status Badge -->
                <div class="mt-5 p-3 rounded-pill d-inline-flex align-items-center px-5" style="background: #e3f2fd; color: #1976d2;">
                    <span class="pulse-status me-2"></span>
                    <span class="fw-bold uppercase small tracking-wider">Status: {{ $pesanan->status }}</span>
                </div>

                <!-- Actions -->
                <div class="mt-5 d-flex flex-wrap gap-3 justify-content-center">
                    <a href="{{ route('kantin.history') }}" class="btn btn-outline-secondary btn-lg rounded-pill px-5 py-3">
                        <i class="mdi mdi-receipt me-2"></i> Riwayat Pesanan
                    </a>
                    <a href="{{ route('kantin.index') }}" class="btn btn-primary btn-lg rounded-pill px-5 py-3 shadow-lg">
                        Pesan Lagi <i class="mdi mdi-arrow-right ms-2"></i>
                    </a>
                </div>
            </div>
            
            <div class="card-footer bg-light border-0 py-4 opacity-75">
                <p class="mb-0 small">Butuh bantuan? Hubungi customer service kami di <span class="fw-bold">0812-3456-7890</span></p>
            </div>
        </div>
    </div>
</div>

@push('script-page')
<script>
    @if($pesanan->status == 'pending')
    // Polling status setiap 3 detik jika masih pending
    const checkInterval = setInterval(() => {
        fetch("{{ route('kantin.status', $pesanan->id) }}")
            .then(res => res.json())
            .then(data => {
                if (data.status === 'completed') {
                    clearInterval(checkInterval);
                    Swal.fire({
                        icon: 'success',
                        title: 'Pembayaran Dikonfirmasi!',
                        text: 'Status pesanan Anda telah diperbarui menjadi Lunas.',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.reload();
                    });
                }
            });
    }, 3000);
    @endif
</script>
@endpush

<style>
    @keyframes bounceIn {
        from { opacity: 0; transform: scale3d(0.3, 0.3, 0.3); }
        20% { transform: scale3d(1.1, 1.1, 1.1); }
        40% { transform: scale3d(0.9, 0.9, 0.9); }
        60% { opacity: 1; transform: scale3d(1.03, 1.03, 1.03); }
        80% { transform: scale3d(0.97, 0.97, 0.97); }
        to { opacity: 1; transform: scale3d(1, 1, 1); }
    }
    .uppercase { text-transform: uppercase; letter-spacing: 1px; }
    .x-small { font-size: 0.65rem; }
    .pulse-status {
        width: 10px; height: 10px;
        background: #1976d2;
        border-radius: 50%;
        box-shadow: 0 0 0 rgba(25, 118, 210, 0.4);
        animation: pulse-blue 2s infinite;
    }
    @keyframes pulse-blue {
        0% { box-shadow: 0 0 0 0 rgba(25, 118, 210, 0.4); }
        70% { box-shadow: 0 0 0 10px rgba(25, 118, 210, 0); }
        100% { box-shadow: 0 0 0 0 rgba(25, 118, 210, 0); }
    }
</style>
@endsection
