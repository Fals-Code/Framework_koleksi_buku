@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card border-0 shadow-lg text-center" style="border-radius: 25px;">
            <div class="card-body p-5">
                <div class="mb-4">
                    <i class="mdi mdi-check-circle text-success" style="font-size: 100px; animation: scaleIn 0.5s ease-out;"></i>
                </div>
                <h2 class="fw-bold text-dark mb-2">Pembayaran Berhasil!</h2>
                <p class="text-muted fs-5 mb-4">Terima kasih atas pesanan Anda. Silakan tunjukkan halaman ini saat mengambil pesanan.</p>

                <div class="bg-light p-4 rounded-3 mb-4 text-start">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small uppercase">Nama Pemesan</span>
                        <span class="fw-bold text-primary">{{ $pesanan->nama_pelanggan }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small uppercase">Nomor Pesanan</span>
                        <span class="fw-bold text-dark">{{ $pesanan->nomor_pesanan }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small uppercase">Tanggal</span>
                        <span class="fw-bold">{{ $pesanan->created_at->format('d M Y, H:i') }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small uppercase">Warung</span>
                        <span class="fw-bold">{{ $pesanan->vendor->nama_warung }}</span>
                    </div>
                    <hr>
                    <h6 class="fw-bold mb-3">Detail Menu:</h6>
                    @foreach($pesanan->detailPesanan as $item)
                    <div class="d-flex align-items-center mb-3">
                        <div class="me-3" style="width: 50px; height: 50px; overflow: hidden; border-radius: 8px;">
                            <img src="{{ $item->menu->foto ? asset('storage/' . $item->menu->foto) : 'https://placehold.co/100x100?text=' . urlencode($item->menu->nama_makanan) }}" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between">
                                <span class="fw-bold">{{ $item->menu->nama_makanan }}</span>
                                <span class="fw-bold">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                            </div>
                            <span class="text-muted small">x{{ $item->qty }} @ Rp {{ number_format($item->menu->harga, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    @endforeach

                    @if($pesanan->catatan)
                    <div class="alert alert-light border-start border-3 border-warning mt-3 mb-0">
                        <small class="text-muted d-block uppercase fw-bold mb-1">Catatan Pesanan:</small>
                        <p class="mb-0 italic">"{{ $pesanan->catatan }}"</p>
                    </div>
                    @endif
                    <hr>
                    <div class="d-flex justify-content-between">
                        <span class="fw-bold fs-5 text-dark">Total Bayar</span>
                        <span class="fw-bold fs-5 text-primary">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</span>
                    </div>
                </div>
                
                <div class="my-4 text-center">
                    <div class="d-inline-block p-3 bg-white shadow-sm rounded-4 border">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ $pesanan->nomor_pesanan }}" 
                             alt="QR Code Pesanan" 
                             style="width: 150px; height: 150px; border-radius: 10px;">
                    </div>
                    <p class="mt-2 text-muted small fw-bold">Tunjukkan QR ini kepada kasir</p>
                </div>

                <div class="alert alert-info border-0 rounded-pill py-3">
                    <i class="mdi mdi-information-outline me-2"></i>
                    Status: <strong class="text-uppercase">{{ $pesanan->status }}</strong>
                    @if($pesanan->status == 'pending' && !config('midtrans.is_production'))
                    <br>
                    <small>Selesaikan di <a href="https://simulator.sandbox.midtrans.com/" target="_blank" class="fw-bold text-dark">Simulator Midtrans</a></small>
                    @endif
                </div>

                <div class="mt-5 d-flex flex-wrap gap-3 justify-content-center">
                    <a href="{{ route('kantin.track', $pesanan->id) }}" class="btn btn-primary btn-lg rounded-pill px-4 py-3 shadow">
                        <i class="mdi mdi-map-marker-distance me-2"></i> Lacak Pesanan
                    </a>
                    <a href="{{ route('kantin.receipt', $pesanan->id) }}" target="_blank" class="btn btn-gradient-success btn-lg rounded-pill px-4 py-3 shadow">
                        <i class="mdi mdi-printer me-2"></i> Cetak Struk
                    </a>
                    <a href="{{ route('kantin.index') }}" class="btn btn-outline-secondary btn-lg rounded-pill px-4 py-3">
                        <i class="mdi mdi-arrow-left me-2"></i> Kembali ke Menu
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes scaleIn {
        0% { transform: scale(0); opacity: 0; }
        100% { transform: scale(1); opacity: 1; }
    }
    .uppercase { text-transform: uppercase; letter-spacing: 1px; }
</style>
@endsection
