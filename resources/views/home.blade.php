@extends('layouts.app')

@section('content')
<style>
    @keyframes floating {
        0% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
        100% { transform: translateY(0px); }
    }

    .transition-all {
    transition: all 0.3s ease;
}
.hover-shadow:hover {
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    transform: translateY(-2px);
}
.spinner-border-sm {
    width: 1.2rem;
    height: 1.2rem;
    border-width: 0.15em;
}

    .card-floating { animation: floating 4s ease-in-out infinite; }
    .glass-card {
        background: rgba(255, 255, 255, 0.4) !important;
        backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.2) !important;
    }

    .neon-text {
        text-shadow: 0 0 10px rgba(182, 109, 255, 0.5);
    }

    .progress-custom { height: 8px; border-radius: 10px; background: rgba(0,0,0,0.05); }

    .icon-overlay {
        position: absolute;
        bottom: -20px;
        right: -10px;
        font-size: 100px;
        color: rgba(255, 255, 255, 0.15);
        transform: rotate(-15deg);
    }
    .text-truncate-custom {
        display: block;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 100%;
    }
    .hover-shadow:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
    }
</style>

<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-cube-outline"></i>
        </span> 
        <span class="neon-text">Dashboard</span>
    </h3>
    <nav aria-label="breadcrumb">
        <div class="badge badge-outline-primary rounded-pill p-3">
            <i class="mdi mdi-refresh me-2"></i> Sistem Sinkron: <strong>Aktif</strong>
        </div>
    </nav>
</div>

<div class="row">
    <div class="col-md-4 stretch-card grid-margin">
        <div class="card bg-gradient-danger card-img-holder text-white card-floating">
            <div class="card-body">
                <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute" alt="circle-image" />
                <i class="mdi mdi-book-multiple icon-overlay"></i>
                <h4 class="font-weight-normal mb-3">Inventory Buku <i class="mdi mdi-chart-line mdi-24px float-end"></i></h4>
                <h2 class="mb-4 fw-bold">{{ number_format($totalBuku, 0, ',', '.') }} Judul</h2>
                <p class="small mb-0">Total pustaka terverifikasi</p>
            </div>
        </div>
    </div>

    <div class="col-md-4 stretch-card grid-margin">
        <div class="card bg-gradient-info card-img-holder text-white card-floating" style="animation-delay: 0.5s;">
            <div class="card-body">
                <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute" alt="circle-image" />
                <i class="mdi mdi-folder-open icon-overlay"></i>
                <h4 class="font-weight-normal mb-3">Sektor Kategori <i class="mdi mdi-filter-variant mdi-24px float-end"></i></h4>
                <h2 class="mb-4 fw-bold">{{ $totalKategori }} Kelompok</h2>
                <p class="small mb-0">Klaster data terintegrasi</p>
            </div>
        </div>
    </div>

    <div class="col-md-4 stretch-card grid-margin">
        <div class="card bg-gradient-success card-img-holder text-white card-floating" style="animation-delay: 1s;">
            <div class="card-body">
                <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute" alt="circle-image" />
                <i class="mdi mdi-database-check icon-overlay"></i>
                <h4 class="font-weight-normal mb-3">Smart Inventaris <i class="mdi mdi-tag-multiple mdi-24px float-end"></i></h4>
                <h2 class="mb-4 fw-bold">{{ number_format($stats['total_asset'], 0, ',', '.') }} Item</h2>
                <div class="d-flex align-items-center">
                    <div class="dot bg-white rounded-circle me-2" style="width: 10px; height: 10px;"></div>
                    <p class="small mb-0 text-uppercase fw-bold">Nilai: Rp {{ number_format($stats['total_nilai'], 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-7 grid-margin stretch-card">
        <div class="card glass-card shadow-lg">
            <div class="card-body">
                <h4 class="card-title fw-bold text-primary mb-4">
                    <i class="mdi mdi-flash-circle me-2"></i> Akses Cepat Terintegrasi
                </h4>
<div class="row g-3">
    <div class="col-sm-6">
        <div class="p-4 rounded-3 border bg-white d-flex align-items-center hover-shadow transition-all" 
             style="cursor: pointer;" 
             onclick="btnLoading(this); setTimeout(() => { location.href='{{ route('barang.index') }}' }, 50);">
            <div class="rounded-circle bg-light-primary p-3 me-3 text-primary icon-container">
                <i class="mdi mdi-plus-box mdi-24px"></i>
            </div>
            <div>
                <h6 class="mb-0 fw-bold menu-title">Kelola Barang</h6>
                <small class="text-muted">Database Aset</small>
            </div>
        </div>
    </div>

    <div class="col-sm-6">
        <div class="p-4 rounded-3 border bg-white d-flex align-items-center hover-shadow transition-all" 
             style="cursor: pointer;" 
             onclick="btnLoading(this); setTimeout(() => { window.open('{{ route('barang.cetak') }}', '_blank'); location.reload(); }, 100);">
            <div class="rounded-circle bg-light-info p-3 me-3 text-info icon-container">
                <i class="mdi mdi-barcode-scan mdi-24px"></i>
            </div>
            <div>
                <h6 class="mb-0 fw-bold menu-title">Cetak Label</h6>
                <small class="text-muted">Barcode Generator</small>
            </div>
        </div>
    </div>
</div>

                <div class="mt-4 p-3 bg-white rounded-3 border">
                    <h6 class="fw-bold mb-3"><i class="mdi mdi-chart-bar text-primary me-2"></i> Grafik Aktivitas Inventaris</h6>
                    <div style="height: 200px;">
                        <canvas id="inventoryChart"></canvas>
                    </div>
                </div>

                <div class="mt-4 p-3 rounded-4 bg-gradient-light border">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <div class="avatar me-3">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=b66dff&color=fff" class="rounded-circle" width="45">
                            </div>
                            <div>
                                <p class="mb-0 fw-bold">{{ Auth::user()->email }}</p>
                                <p class="small text-muted mb-0"><i class="mdi mdi-key-variant text-warning"></i> Secure Session ID: <span class="text-dark">{{ substr(session()->getId(), 0, 12) }}...</span></p>
                            </div>
                        </div>
                        <div class="badge badge-primary rounded-pill">Operator: {{ Auth::user()->name }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-5 grid-margin stretch-card">
        <div class="card shadow-lg border-0 bg-dark text-white" style="border-radius: 20px;">
            <div class="card-body">
                <h4 class="card-title text-white mb-4">Market & Analytics Center</h4>
                
                <div class="d-flex flex-column gap-3">
                    <div class="p-3 border border-secondary rounded-3 d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <i class="mdi mdi-star text-warning mdi-36px me-3"></i>
                            <div>
                                <h6 class="mb-0 text-white fw-bold">Produk Premium</h6>
                                <small class="text-secondary text-truncate-custom" style="max-width: 150px;">{{ $stats['termahal']->nama ?? '-' }}</small>
                            </div>
                        </div>
                        <span class="badge badge-outline-warning">Rp {{ number_format($stats['termahal']->harga ?? 0, 0, ',', '.') }}</span>
                    </div>

                    <div class="p-3 border border-secondary rounded-3 d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <i class="mdi mdi-tag-heart text-success mdi-36px me-3"></i>
                            <div>
                                <h6 class="mb-0 text-white fw-bold">Produk Ekonomis</h6>
                                <small class="text-secondary text-truncate-custom" style="max-width: 150px;">{{ $stats['termurah']->nama ?? '-' }}</small>
                            </div>
                        </div>
                        <span class="badge badge-outline-success">Rp {{ number_format($stats['termurah']->harga ?? 0, 0, ',', '.') }}</span>
                    </div>
                </div>

                <div class="mt-5 text-center p-3 bg-secondary rounded-3" style="--bs-bg-opacity: .1;">
                    <i class="mdi mdi-information-outline text-info"></i>
                    <p class="small mb-0 italic mt-2">Seluruh laporan diproses secara enkripsi oleh server Vokasi UNAIR.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12 text-center">
        <div class="py-3 px-4 d-inline-block rounded-pill bg-white shadow-sm border">
            <span class="text-muted small">
                <i class="mdi mdi-clock-fast text-primary me-1"></i> 
                Sistem dimulai: <span class="text-dark fw-bold">{{ date('d F Y') }}</span> 
                | Status: <span class="text-success fw-bold">ONLINE</span>
            </span>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var canvas = document.getElementById('inventoryChart');
        if (canvas) {
            var ctx = canvas.getContext('2d');
            
            var chartLabels = {!! json_encode($labels ?? []) !!};
            var chartData = {!! json_encode($totals ?? []) !!};

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: chartLabels,
                    datasets: [{
                        label: 'Input Barang',
                        data: chartData,
                        borderColor: '#b66dff',
                        backgroundColor: 'rgba(182, 109, 255, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true, ticks: { stepSize: 1 } },
                        x: { grid: { display: false } }
                    }
                }
            });
        }
    });
</script>
@endsection