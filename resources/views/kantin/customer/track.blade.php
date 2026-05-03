@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <!-- Header with Gradient -->
                <div class="bg-gradient-primary p-5 text-white text-center position-relative">
                    <div class="mb-3">
                        <i class="mdi mdi-map-marker-distance mdi-48px"></i>
                    </div>
                    <h2 class="fw-bold mb-1">Lacak Pesanan</h2>
                    <p class="opacity-75 mb-0">Nomor Pesanan: <span class="fw-bold">{{ $pesanan->nomor_pesanan }}</span></p>
                    
                    <!-- Decorative Circles -->
                    <div class="position-absolute top-0 start-0 p-4 opacity-25">
                        <i class="mdi mdi-circle-outline mdi-48px"></i>
                    </div>
                </div>

                <div class="card-body p-4 p-md-5">
                    <!-- Status Stepper -->
                    <div class="order-tracking-steps mb-5">
                        @php
                            $status_map = [
                                'pending'   => 0,
                                'lunas'      => 1,
                                'paid'      => 1,
                                'cooking'   => 2,
                                'ready'     => 3,
                                'completed' => 4,
                                'cancelled' => -1
                            ];
                            $current_step = $status_map[$pesanan->status] ?? 0;
                        @endphp

                        @if($pesanan->status == 'cancelled')
                            <div class="alert alert-danger rounded-3 d-flex align-items-center">
                                <i class="mdi mdi-alert-circle mdi-24px me-3"></i>
                                <div>
                                    <strong>Pesanan Dibatalkan</strong>
                                    <p class="mb-0 small">Maaf, pesanan Anda telah dibatalkan atau kedaluwarsa.</p>
                                </div>
                            </div>
                        @elseif($pesanan->status == 'pending')
                            <div class="alert alert-warning rounded-4 border-0 p-4 mb-4 shadow-sm">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="bg-white rounded-circle p-2 me-3">
                                        <i class="mdi mdi-clock-alert text-warning fs-3"></i>
                                    </div>
                                    <div>
                                        <h5 class="fw-bold text-dark mb-0">Menunggu Pembayaran</h5>
                                        <p class="text-muted small mb-0">Segera selesaikan pembayaran agar pesanan diproses.</p>
                                    </div>
                                </div>
                                <button class="btn btn-gradient-primary w-100 rounded-pill py-3 fw-bold shadow-lg" onclick="payNow()">
                                    <i class="mdi mdi-credit-card-outline me-2"></i> BAYAR SEKARANG
                                </button>
                            </div>
                        @else
                            <div class="tracking-stepper d-flex justify-content-between position-relative">
                                <div class="step-line position-absolute top-50 start-0 end-0 translate-middle-y bg-light" style="height: 4px; z-index: 0;">
                                    <div class="progress-bar bg-primary" id="tracking-progress" style="height: 100%; width: {{ ($current_step / 4) * 100 }}%; transition: width 0.5s ease;"></div>
                                </div>
                                
                                @foreach(['Sudah Bayar', 'Dimasak', 'Siap', 'Selesai'] as $index => $label)
                                    @php $step_num = $index + 1; @endphp
                                    <div class="step-item text-center position-relative" style="z-index: 1;">
                                        <div class="step-icon rounded-circle bg-white border border-4 {{ $current_step >= $step_num ? 'border-primary text-primary' : 'border-light text-muted' }} d-flex align-items-center justify-content-center mb-2 mx-auto" style="width: 50px; height: 50px; transition: all 0.3s ease;">
                                            <i class="mdi {{ [1=>'mdi-cash-check', 2=>'mdi-pot-steam', 3=>'mdi-bell-ring', 4=>'mdi-check-all'][$step_num] ?? 'mdi-circle' }} mdi-24px"></i>
                                        </div>
                                        <span class="small fw-bold {{ $current_step >= $step_num ? 'text-primary' : 'text-muted' }}">{{ $label }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- Order Details Summary -->
                    <div class="row g-4 mt-4">
                        <div class="col-md-6">
                            <h5 class="fw-bold mb-3 d-flex align-items-center">
                                <i class="mdi mdi-store text-primary me-2"></i> Vendor
                            </h5>
                            <div class="p-3 bg-light rounded-3">
                                <p class="mb-1 fw-bold text-dark">{{ $pesanan->vendor->nama_warung }}</p>
                                <p class="mb-0 small text-muted">{{ $pesanan->vendor->lokasi ?? 'Lantai 1 Kantin' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h5 class="fw-bold mb-3 d-flex align-items-center">
                                <i class="mdi mdi-account text-primary me-2"></i> Pelanggan
                            </h5>
                            <div class="p-3 bg-light rounded-3">
                                <p class="mb-1 fw-bold text-dark">{{ $pesanan->nama_pelanggan }}</p>
                                <p class="mb-0 small text-muted">Status: <span id="status-badge" class="badge badge-sm badge-gradient-info">{{ strtoupper($pesanan->status) }}</span></p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 p-4 border rounded-4">
                        <h5 class="fw-bold mb-3">Ringkasan Menu</h5>
                        <ul class="list-group list-group-flush">
                            @foreach($pesanan->detailPesanan as $detail)
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0 bg-transparent">
                                <div class="d-flex align-items-center">
                                    <div class="bg-light rounded-3 p-1 me-3">
                                        @if($detail->menu->foto)
                                            <img src="{{ asset('storage/' . $detail->menu->foto) }}" alt="" style="width: 40px; height: 40px; object-fit: cover;" class="rounded-2">
                                        @else
                                            <div class="bg-primary text-white d-flex align-items-center justify-content-center rounded-2" style="width: 40px; height: 40px;">
                                                <i class="mdi mdi-food-fork-drink"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="mb-0 fw-bold">{{ $detail->menu->nama_makanan }}</p>
                                        <small class="text-muted">x{{ $detail->qty }}</small>
                                    </div>
                                </div>
                                <span class="fw-bold">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</span>
                            </li>
                            @endforeach
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0 bg-transparent pt-3">
                                <span class="h5 fw-bold mb-0">Total Bayar</span>
                                <span class="h5 fw-bold text-primary mb-0">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</span>
                            </li>
                        </ul>
                    </div>

                    <!-- QR Code Section for Vendor Validation -->
                    <div class="mt-5 text-center p-4 bg-light rounded-4 border-dashed border-2">
                        <h6 class="fw-bold mb-3 text-dark">QR CODE VALIDASI</h6>
                        <div class="d-inline-block p-3 bg-white rounded-3 shadow-sm mb-3">
                            <img src="{{ $qrCodeDataUri }}" 
                                 alt="QR Code Pesanan" 
                                 style="width: 180px; height: 180px;">
                        </div>
                        <p class="small text-muted mb-0">Tunjukkan QR ini kepada penjual untuk pengambilan pesanan.</p>
                        <div class="badge bg-white text-dark border mt-2 px-3 py-2 rounded-pill fw-bold">
                            ID: {{ $pesanan->id }}
                        </div>
                    </div>

                    <div class="text-center mt-5">
                        <p class="text-muted small mb-4">Halaman ini akan otomatis diperbarui saat status pesanan berubah.</p>
                        <a href="{{ route('kantin.index') }}" class="btn btn-outline-primary btn-lg rounded-pill px-5">
                            <i class="mdi mdi-arrow-left me-2"></i> Kembali ke Menu
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
}
.badge-gradient-info {
    background: linear-gradient(to right, #36d1dc, #5b86e5);
}
.order-tracking-steps {
    padding: 20px 0;
}
.tracking-stepper .step-item {
    width: 25%;
}
</style>

@push('script-page')
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
<script>
    function payNow() {
        if (typeof snap !== 'undefined') {
            snap.pay("{{ $pesanan->snap_token }}", {
                onSuccess: function() { location.reload(); },
                onPending: function() { location.reload(); },
                onError: function() { location.reload(); },
                onClose: function() { 
                    Swal.fire({
                        icon: 'info',
                        title: 'Pembayaran Tertunda',
                        text: 'Silakan klik tombol "Bayar Sekarang" kembali jika ingin melanjutkan pembayaran.'
                    });
                }
            });
        }
    }

    function checkStatus() {
        fetch("{{ route('kantin.status', $pesanan->id) }}")
            .then(response => response.json())
            .then(data => {
                if (data.status !== "{{ $pesanan->status }}") {
                    location.reload();
                }
            })
            .catch(error => console.error('Error tracking status:', error));
    }

    // Poll every 10 seconds
    setInterval(checkStatus, 10000);
</script>
@endpush
@endsection
