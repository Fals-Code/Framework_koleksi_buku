@extends('layouts.app')

@section('content')
<style>
    :root {
        --purple-brand: #b66dff;
        --purple-light: rgba(182, 109, 255, 0.1);
        --glass-bg: rgba(255, 255, 255, 0.9);
        --card-shadow: 0 10px 30px rgba(0,0,0,0.08);
    }
    
    .history-card {
        border-radius: 20px;
        border: none;
        transition: all 0.3s ease;
        overflow: hidden;
    }
    
    .history-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--card-shadow);
    }

    .status-badge {
        padding: 5px 12px;
        border-radius: 10px;
        font-weight: 700;
        font-size: 0.75rem;
        text-transform: uppercase;
    }

    .status-pending { background: #fff8e1; color: #ff8f00; }
    .status-paid, .status-cooking { background: #e8f0fe; color: #1a73e8; }
    .status-ready { background: #e6f4ea; color: #1e8e3e; }
    .status-completed { background: #f1f3f4; color: #5f6368; }
    .status-cancelled { background: #fce8e6; color: #d93025; }

    .order-number {
        font-family: 'JetBrains Mono', monospace;
        font-weight: 700;
        color: var(--purple-brand);
    }

    .vendor-name {
        font-weight: 800;
        font-size: 1.1rem;
    }

    .order-date {
        font-size: 0.75rem;
        color: #888;
    }

    .btn-action {
        border-radius: 10px;
        font-weight: 700;
        transition: all 0.2s;
    }

    .empty-state {
        padding: 4rem 2rem;
        text-align: center;
    }
</style>

<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-history"></i>
        </span> Riwayat Pesanan
    </h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('kantin.index') }}" class="btn btn-inverse-primary btn-sm rounded-pill px-3">
                    <i class="mdi mdi-plus me-1"></i> Pesan Baru
                </a>
            </li>
        </ul>
    </nav>
</div>

<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                @forelse($pesanan as $order)
                <div class="card history-card border mb-4">
                    <div class="card-body p-4">
                        <div class="row align-items-center">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <div class="d-flex align-items-center mb-2">
                                    <span class="status-badge status-{{ $order->status }} me-2">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                    <span class="order-number">#{{ $order->nomor_pesanan }}</span>
                                </div>
                                <h5 class="vendor-name mb-1">{{ $order->vendor->nama_warung }}</h5>
                                <p class="order-date mb-0">
                                    <i class="mdi mdi-calendar-clock me-1"></i>
                                    {{ $order->created_at->translatedFormat('d F Y, H:i') }}
                                </p>
                            </div>
                            <div class="col-md-3 mb-3 mb-md-0">
                                <span class="text-muted d-block small">Total Pembayaran</span>
                                <h4 class="fw-bold text-dark mb-0">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</h4>
                            </div>
                            <div class="col-md-3 text-md-end">
                                <div class="d-grid d-md-flex justify-content-md-end gap-2">
                                    @if(in_array($order->status, ['pending', 'paid', 'cooking', 'ready']))
                                        <a href="{{ route('kantin.track', $order->id) }}" class="btn btn-gradient-primary btn-sm btn-action">
                                            <i class="mdi mdi-map-marker me-1"></i> Lacak
                                        </a>
                                    @endif
                                    <a href="{{ route('kantin.receipt', $order->id) }}" class="btn btn-inverse-dark btn-sm btn-action">
                                        <i class="mdi mdi-file-document me-1"></i> Struk
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-3 pt-3 border-top">
                            <div class="row">
                                <div class="col-12">
                                    <span class="small text-muted">Item:</span>
                                    <p class="small mb-0 text-dark">
                                        @foreach($order->detailPesanan as $detail)
                                            {{ $detail->qty }}x {{ $order->detailPesanan->count() > 1 && !$loop->last ? $detail->menu->nama_makanan . ', ' : $detail->menu->nama_makanan }}
                                        @endforeach
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="empty-state">
                    <i class="mdi mdi-basket-off-outline text-muted" style="font-size: 5rem; opacity: 0.3;"></i>
                    <h4 class="mt-4">Belum Ada Pesanan</h4>
                    <p class="text-muted">Anda belum melakukan pemesanan di kantin. Yuk jajan!</p>
                    <a href="{{ route('kantin.index') }}" class="btn btn-gradient-primary rounded-pill px-4 mt-2">Pesan Sekarang</a>
                </div>
                @endforelse

                <div class="d-flex justify-content-center mt-4">
                    {{ $pesanan->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
