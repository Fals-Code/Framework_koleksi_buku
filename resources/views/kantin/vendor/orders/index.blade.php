@extends('layouts.app')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-history"></i>
        </span> Riwayat Pesanan
    </h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">
                <span></span>Vendor Management <i class="mdi mdi-check icon-sm text-primary align-middle"></i>
            </li>
        </ul>
    </nav>
</div>

<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="card-title mb-0">Semua Pesanan Anda</h4>
                    <div class="btn-group" role="group" aria-label="Status Filters">
                        <button type="button" class="btn btn-inverse-primary btn-sm active-filter" onclick="filterStatus('all')">Semua</button>
                        <button type="button" class="btn btn-inverse-warning btn-sm" onclick="filterStatus('paid')">Baru</button>
                        <button type="button" class="btn btn-inverse-info btn-sm" onclick="filterStatus('cooking')">Dimasak</button>
                        <button type="button" class="btn btn-inverse-success btn-sm" onclick="filterStatus('ready')">Siap</button>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover" id="orders-table">
                        <thead>
                            <tr class="bg-light">
                                <th class="py-3"> Info Pesanan </th>
                                <th class="py-3"> Pelanggan </th>
                                <th class="py-3"> Menu & Qty </th>
                                <th class="py-3"> Total </th>
                                <th class="py-3 text-center"> Status </th>
                                <th class="py-3 text-center"> Aksi </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                            <tr class="order-row" data-status="{{ $order->status }}">
                                <td> 
                                    <div class="fw-bold text-primary" style="font-size: 1rem;">#{{ $order->nomor_pesanan }}</div>
                                    <div class="small text-muted">
                                        <i class="mdi mdi-calendar-outline me-1"></i> {{ $order->created_at->format('d M Y') }}
                                        <span class="ms-2"><i class="mdi mdi-clock-outline me-1"></i> {{ $order->created_at->format('H:i') }}</span>
                                    </div>
                                </td>
                                <td> 
                                    <div class="fw-bold text-dark">{{ $order->nama_pelanggan }}</div>
                                    @if($order->catatan)
                                    <div class="small text-muted italic">
                                        <i class="mdi mdi-message-text-outline me-1"></i> "{{ $order->catatan }}"
                                    </div>
                                    @endif
                                </td>
                                <td>
                                    <ul class="list-unstyled mb-0">
                                        @foreach($order->detailPesanan as $detail)
                                        <li class="small d-flex justify-content-between mb-1">
                                            <span><i class="mdi mdi-circle-medium text-primary"></i> {{ $detail->menu->nama_makanan }}</span>
                                            <span class="fw-bold">x{{ $detail->qty }}</span>
                                        </li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td> 
                                    <div class="fw-bold text-dark">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</div>
                                </td>
                                <td class="text-center">
                                    @php
                                        $status_config = [
                                            'completed' => ['color' => 'success', 'icon' => 'check-all'],
                                            'cancelled' => ['color' => 'danger', 'icon' => 'close-circle'],
                                            'paid' => ['color' => 'warning', 'icon' => 'cash-check'],
                                            'cooking' => ['color' => 'info', 'icon' => 'pot-steam'],
                                            'ready' => ['color' => 'success', 'icon' => 'bell-ring'],
                                            'pending' => ['color' => 'secondary', 'icon' => 'clock'],
                                        ][$order->status] ?? ['color' => 'dark', 'icon' => 'help-circle'];
                                    @endphp
                                    <label class="badge badge-gradient-{{ $status_config['color'] }} px-3 py-2 rounded-pill shadow-sm">
                                        <i class="mdi mdi-{{ $status_config['icon'] }} me-1"></i>
                                        {{ strtoupper($order->status) }}
                                    </label>
                                </td>
                                <td class="text-center">
                                    @if(!in_array($order->status, ['completed', 'cancelled']))
                                    <form action="{{ route('vendor.order.status', $order->id) }}" method="POST" id="form-status-{{ $order->id }}">
                                        @csrf
                                        @if($order->status == 'paid')
                                            <button type="button" onclick="btnLoading('#btn-cook-{{ $order->id }}')" id="btn-cook-{{ $order->id }}" name="status" value="cooking" class="btn btn-gradient-warning btn-sm py-2 px-3 shadow-sm rounded-pill">
                                                Mulai Masak
                                            </button>
                                            <input type="hidden" name="status" value="cooking">
                                        @elseif($order->status == 'cooking')
                                            <button type="button" onclick="btnLoading('#btn-ready-{{ $order->id }}')" id="btn-ready-{{ $order->id }}" name="status" value="ready" class="btn btn-gradient-info btn-sm py-2 px-3 shadow-sm rounded-pill">
                                                Siap Diambil
                                            </button>
                                            <input type="hidden" name="status" value="ready">
                                        @elseif($order->status == 'ready')
                                            <button type="button" onclick="btnLoading('#btn-done-{{ $order->id }}')" id="btn-done-{{ $order->id }}" name="status" value="completed" class="btn btn-gradient-success btn-sm py-2 px-3 shadow-sm rounded-pill">
                                                Selesai
                                            </button>
                                            <input type="hidden" name="status" value="completed">
                                        @else
                                            <span class="text-muted small">Menunggu</span>
                                        @endif
                                    </form>
                                    @else
                                    <span class="badge badge-outline-secondary rounded-pill">No Action</span>
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

<audio id="order-notification" preload="auto">
    <source src="https://assets.mixkit.co/active_storage/sfx/2869/2869-preview.mp3" type="audio/mpeg">
</audio>

@push('scripts')
<script>
    function filterStatus(status) {
        // Update button visual
        $('.btn-group .btn').removeClass('active-filter');
        event.currentTarget.classList.add('active-filter');
        
        if (status === 'all') {
            $('.order-row').show();
        } else {
            $('.order-row').hide();
            $(`.order-row[data-status="${status}"]`).show();
        }
    }

    let lastKnownOrderCount = {{ $orders->where('status', 'paid')->count() }};
    
    function checkNewOrders() {
        fetch("{{ route('vendor.api.orders.count') }}")
            .then(res => res.json())
            .then(data => {
                if (data.count > lastKnownOrderCount) {
                    const sound = document.getElementById('order-notification');
                    if (sound) sound.play().catch(e => console.log('Audio error'));
                    
                    Swal.fire({
                        title: 'Pesanan Baru!',
                        text: 'Ada pesanan baru yang harus segera dimasak.',
                        icon: 'info',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 5000,
                        timerProgressBar: true
                    });
                    
                    setTimeout(() => location.reload(), 2000);
                }
                lastKnownOrderCount = data.count;
            })
            .catch(err => console.error('Polling error:', err));
    }

    // Auto refresh every 15s
    setInterval(checkNewOrders, 15000);
</script>

<style>
    .active-filter {
        box-shadow: 0 0 0 2px rgba(182, 109, 255, 0.4);
        font-weight: bold;
    }
    .order-row {
        transition: all 0.3s ease;
    }
    .order-row:hover {
        background-color: #fcfaff !important;
        transform: scale(1.002);
    }
</style>
@endpush
@endsection
