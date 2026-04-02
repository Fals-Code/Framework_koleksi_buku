@extends('layouts.app')

@section('content')
<div class="page-header">
    <h3 class="page-title"> Riwayat Pesanan </h3>
</div>

<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Semua Pesanan</h4>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th> Tanggal </th>
                                <th> Pelanggan </th>
                                <th> Order # </th>
                                <th> Menu & Qty </th>
                                <th> Jam </th>
                                <th> Total </th>
                                <th> Status </th>
                                <th> Aksi </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                            <tr>
                                <td> {{ $order->created_at->format('d M Y') }} </td>
                                <td> 
                                    <div class="fw-bold">{{ $order->nama_pelanggan }}</div>
                                    @if($order->catatan)
                                    <div class="small text-muted italic">"{{ $order->catatan }}"</div>
                                    @endif
                                </td>
                                <td> {{ $order->nomor_pesanan }} </td>
                                <td>
                                    <ul class="list-unstyled mb-0">
                                        @foreach($order->detailPesanan as $detail)
                                        <li class="small">
                                            <i class="mdi mdi-check-circle-outline text-success"></i> 
                                            {{ $detail->menu->nama_makanan }} <strong>(x{{ $detail->qty }})</strong>
                                        </li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td> {{ $order->created_at->format('H:i') }} </td>
                                <td> Rp {{ number_format($order->total_harga, 0, ',', '.') }} </td>
                                <td>
                                    <label class="badge badge-{{ 
                                        $order->status == 'completed' ? 'success' : 
                                        ($order->status == 'cancelled' ? 'danger' : 'info') 
                                    }}">
                                        {{ strtoupper($order->status) }}
                                    </label>
                                </td>
                                <td>
                                    @if(!in_array($order->status, ['completed', 'cancelled']))
                                    <form action="{{ route('vendor.order.status', $order->id) }}" method="POST">
                                        @csrf
                                        @if($order->status == 'paid')
                                            <button type="submit" name="status" value="cooking" class="btn btn-gradient-warning btn-sm py-2 px-3">
                                                Mulai Masak
                                            </button>
                                        @elseif($order->status == 'cooking')
                                            <button type="submit" name="status" value="ready" class="btn btn-gradient-info btn-sm py-2 px-3">
                                                Siap Diambil
                                            </button>
                                        @elseif($order->status == 'ready')
                                            <button type="submit" name="status" value="completed" class="btn btn-gradient-success btn-sm py-2 px-3">
                                                Selesai
                                            </button>
                                        @else
                                            <span class="text-muted small">Menunggu Pembayaran</span>
                                        @endif
                                    </form>
                                    @else
                                    <span class="badge badge-outline-secondary">No Action</span>
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
@endsection
