@extends('layouts.app')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-home"></i>
        </span> Dashboard Vendor: {{ $vendor->nama_warung }}
    </h3>
</div>

<div class="row">
    <div class="col-md-4 stretch-card grid-margin">
        <div class="card bg-gradient-danger card-img-holder text-white">
            <div class="card-body">
                <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute" alt="circle-image" />
                <h4 class="font-weight-normal mb-3">Pendapatan Hari Ini <i class="mdi mdi-calendar-check mdi-24px float-right"></i></h4>
                <h2 class="mb-5">Rp {{ number_format($today_revenue, 0, ',', '.') }}</h2>
                <h6 class="card-text">Berdasarkan pesanan selesai hari ini</h6>
            </div>
        </div>
    </div>
    <div class="col-md-4 stretch-card grid-margin">
        <div class="card bg-gradient-info card-img-holder text-white">
            <div class="card-body">
                <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute" alt="circle-image" />
                <h4 class="font-weight-normal mb-3">Total Pesanan <i class="mdi mdi-chart-line mdi-24px float-right"></i></h4>
                <h2 class="mb-5">{{ $total_orders }}</h2>
                <h6 class="card-text">Semua status pesanan</h6>
            </div>
        </div>
    </div>
    <div class="col-md-4 stretch-card grid-margin">
        <div class="card bg-gradient-success card-img-holder text-white">
            <div class="card-body">
                <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute" alt="circle-image" />
                <h4 class="font-weight-normal mb-3">Total Pendapatan <i class="mdi mdi-cash mdi-24px float-right"></i></h4>
                <h2 class="mb-5">Rp {{ number_format($total_revenue, 0, ',', '.') }}</h2>
                <h6 class="card-text">Akumulasi pesanan selesai</h6>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="card-title mb-0">Antrean Pesanan (Aktif)</h4>
                    <span class="badge badge-gradient-info" id="live-indicator">
                        <i class="mdi mdi-access-point me-1"></i> LIVE
                    </span>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th> Order # </th>
                                <th> Pelanggan </th>
                                <th> Menu </th>
                                <th> Total </th>
                                <th> Status </th>
                                <th> Aksi </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pending_orders as $order)
                            <tr>
                                <td> <strong>#{{ $order->nomor_pesanan }}</strong> </td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $order->nama_pelanggan }}</div>
                                    @if($order->catatan)
                                        <small class="text-muted d-block mt-1"><em>"{{ $order->catatan }}"</em></small>
                                    @endif
                                </td>
                                <td>
                                    <ul class="list-unstyled mb-0">
                                        @foreach($order->detailPesanan as $detail)
                                        <li class="small">
                                            {{ $detail->menu->nama_makanan }} <strong>(x{{ $detail->qty }})</strong>
                                        </li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td> Rp {{ number_format($order->total_harga, 0, ',', '.') }} </td>
                                <td>
                                    @php
                                        $status_color = [
                                            'paid' => 'warning',
                                            'cooking' => 'info',
                                            'ready' => 'success',
                                            'pending' => 'secondary'
                                        ][$order->status] ?? 'dark';
                                    @endphp
                                    <label class="badge badge-gradient-{{ $status_color }}">
                                        {{ strtoupper($order->status) }}
                                    </label>
                                </td>
                                <td>
                                    <form action="{{ route('vendor.order.status', $order->id) }}" method="POST">
                                        @csrf
                                        @if($order->status == 'paid')
                                            <button type="submit" name="status" value="cooking" class="btn btn-gradient-warning btn-sm py-2 px-3">
                                                <i class="mdi mdi-pot-mix me-1"></i> Mulai Masak
                                            </button>
                                        @elseif($order->status == 'cooking')
                                            <button type="submit" name="status" value="ready" class="btn btn-gradient-info btn-sm py-2 px-3">
                                                <i class="mdi mdi-bell-ring me-1"></i> Siap Diambil
                                            </button>
                                        @elseif($order->status == 'ready')
                                            <button type="submit" name="status" value="completed" class="btn btn-gradient-success btn-sm py-2 px-3">
                                                <i class="mdi mdi-check-all me-1"></i> Selesai
                                            </button>
                                        @else
                                            <span class="text-muted small">Menunggu Pembayaran</span>
                                        @endif
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="6" class="text-center py-5 text-muted">Belum ada pesanan aktif.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Top Selling Section -->
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Menu Terlaris</h4>
                <div class="row mt-4">
                    @forelse($top_items as $item)
                    <div class="col-md-3">
                        <div class="d-flex align-items-center mb-3 p-3 bg-light rounded shadow-sm">
                            <div class="bg-gradient-primary rounded-circle p-3 me-3 text-white">
                                <span class="h4 font-weight-bold mb-0">{{ $loop->iteration }}</span>
                            </div>
                            <div>
                                <p class="mb-0 fw-bold">{{ $item->menu->nama_makanan }}</p>
                                <small class="text-muted">{{ $item->total_qty }} Porsi Terjual</small>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12 text-center text-muted py-3">Belum ada data penjualan.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Notification Sound & Script -->
<audio id="order-notification" preload="auto">
    <source src="https://assets.mixkit.co/active_storage/sfx/2869/2869-preview.mp3" type="audio/mpeg">
</audio>

@push('scripts')
<script>
    let lastKnownOrderCount = {{ $pending_orders->where('status', 'paid')->count() }};
    
    function checkNewOrders() {
        fetch("{{ route('vendor.api.orders.count') }}")
            .then(res => res.json())
            .then(data => {
                if (data.count > lastKnownOrderCount) {
                    const sound = document.getElementById('order-notification');
                    if (sound) {
                        sound.play().catch(e => console.log('Audio requires interaction'));
                    }
                    // Optional: Visual Notification
                    if (confirm('Ada pesanan baru! Lihat sekarang?')) {
                        location.reload();
                    } else {
                        location.reload();
                    }
                }
                lastKnownOrderCount = data.count;
            })
            .catch(err => console.error('Polling error:', err));
    }

    // Live update every 15 seconds
    setInterval(checkNewOrders, 15000);

    // Fade effect for live indicator
    setInterval(() => {
        const indicator = document.getElementById('live-indicator');
        if (indicator) {
            indicator.style.opacity = indicator.style.opacity == '0.5' ? '1' : '0.5';
        }
    }, 1000);
</script>
@endpush
@endsection
