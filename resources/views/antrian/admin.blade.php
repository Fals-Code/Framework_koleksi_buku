@extends('layouts.app')

@push('style-page')
<style>
    .admin-card {
        border-radius: 15px;
        border: none;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        transition: transform 0.2s;
    }
    .admin-card:hover {
        transform: translateY(-5px);
    }
    .current-number {
        font-size: 60px;
        font-weight: 900;
        background: linear-gradient(135deg, #b66dff 0%, #6a11cb 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    .queue-list {
        max-height: 400px;
        overflow-y: auto;
    }
    .list-group-item {
        border-radius: 10px !important;
        margin-bottom: 8px;
        border: 1px solid #eee;
        transition: all 0.2s;
    }
    .list-group-item:hover {
        background: #f8f9fa;
        border-color: #b66dff;
    }
</style>
@endpush

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="page-header">
            <h3 class="page-title">
                <span class="page-title-icon bg-gradient-primary text-white me-2">
                    <i class="mdi mdi-monitor-dashboard"></i>
                </span> Dashboard Antrian
            </h3>
            <nav aria-label="breadcrumb">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item active" aria-current="page">
                        <span></span>Overview <i class="mdi mdi-alert-circle-outline icon-sm text-primary align-middle"></i>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</div>

<div class="row">
    <!-- Kontrol Pemanggilan -->
    <div class="col-md-4 grid-margin stretch-card">
        <div class="card admin-card text-center">
            <div class="card-body d-flex flex-column justify-content-center align-items-center">
                <h4 class="card-title text-muted mb-4">Sedang Dipanggil</h4>
                <div id="currentNumberDisplay" class="current-number mb-3">
                    @if($antrianDipanggil->count() > 0)
                        {{ $antrianDipanggil->first()->nomor_antrian }}
                    @else
                        -
                    @endif
                </div>
                <h5 id="currentNameDisplay" class="fw-bold mb-4">
                    @if($antrianDipanggil->count() > 0)
                        {{ $antrianDipanggil->first()->nama_pengunjung }}
                    @else
                        Belum ada panggilan
                    @endif
                </h5>
                
                <button id="btnPanggil" class="btn btn-gradient-primary btn-lg rounded-pill px-4 py-3 w-100 mb-3 shadow">
                    <i class="mdi mdi-bullhorn me-2"></i> Panggil Berikutnya
                </button>
            </div>
        </div>
    </div>

    <!-- Daftar Menunggu -->
    <div class="col-md-4 grid-margin stretch-card">
        <div class="card admin-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="card-title mb-0">Antrian Menunggu</h4>
                    <span id="badgeMenunggu" class="badge badge-gradient-warning rounded-pill">{{ $antrianMenunggu->count() }}</span>
                </div>
                
                <div class="queue-list" id="listMenunggu">
                    @forelse($antrianMenunggu as $item)
                        <div class="list-group-item d-flex justify-content-between align-items-center" id="item-{{ $item->id }}">
                            <div>
                                <h5 class="mb-1 fw-bold">{{ $item->nomor_antrian }}</h5>
                                <small class="text-muted">{{ $item->nama_pengunjung }}</small>
                            </div>
                            <button class="btn btn-sm btn-outline-danger rounded-pill btn-skip" data-id="{{ $item->id }}" title="Lewati">
                                <i class="mdi mdi-close"></i>
                            </button>
                        </div>
                    @empty
                        <div class="text-center text-muted p-4" id="emptyMenunggu">
                            <i class="mdi mdi-inbox-multiple fs-1"></i>
                            <p class="mt-2">Tidak ada antrian</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Daftar Terlewat -->
    <div class="col-md-4 grid-margin stretch-card">
        <div class="card admin-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="card-title mb-0">Terlewat</h4>
                    <span id="badgeTerlewat" class="badge badge-gradient-danger rounded-pill">{{ $antrianTerlewat->count() }}</span>
                </div>
                
                <div class="queue-list" id="listTerlewat">
                    @forelse($antrianTerlewat as $item)
                        <div class="list-group-item d-flex justify-content-between align-items-center" id="item-{{ $item->id }}">
                            <div>
                                <h5 class="mb-1 fw-bold text-danger">{{ $item->nomor_antrian }}</h5>
                                <small class="text-muted">{{ $item->nama_pengunjung }}</small>
                            </div>
                            <button class="btn btn-sm btn-outline-success rounded-pill btn-recall" data-id="{{ $item->id }}" title="Panggil Ulang">
                                <i class="mdi mdi-reload"></i>
                            </button>
                        </div>
                    @empty
                        <div class="text-center text-muted p-4" id="emptyTerlewat">
                            <i class="mdi mdi-check-circle-outline fs-1"></i>
                            <p class="mt-2">Tidak ada yang terlewat</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script-page')
<script>
$(document).ready(function() {
    // Koneksi ke Server-Sent Events
    const sseUrl = "{{ route('antrian.sse.stream') }}";
    let source = new EventSource(sseUrl);

    source.addEventListener('queue-update', function(event) {
        const data = JSON.parse(event.data);
        updateUI(data);
    });

    source.onerror = function(error) {
        console.error('SSE Error:', error);
        // EventSource otomatis reconnect
    };

    function updateUI(data) {
        // Update dipanggil
        if (data.current_called) {
            $('#currentNumberDisplay').text(data.current_called.nomor_antrian);
            $('#currentNameDisplay').text(data.current_called.nama_pengunjung);
        } else {
            $('#currentNumberDisplay').text('-');
            $('#currentNameDisplay').text('Belum ada panggilan');
        }

        // Update list menunggu
        $('#badgeMenunggu').text(data.menunggu.length);
        let htmlMenunggu = '';
        if (data.menunggu.length > 0) {
            data.menunggu.forEach(item => {
                htmlMenunggu += `
                    <div class="list-group-item d-flex justify-content-between align-items-center" id="item-${item.id}">
                        <div>
                            <h5 class="mb-1 fw-bold">${item.nomor_antrian}</h5>
                            <small class="text-muted">${item.nama_pengunjung}</small>
                        </div>
                        <button class="btn btn-sm btn-outline-danger rounded-pill btn-skip" data-id="${item.id}" title="Lewati">
                            <i class="mdi mdi-close"></i>
                        </button>
                    </div>
                `;
            });
        } else {
            htmlMenunggu = `<div class="text-center text-muted p-4"><i class="mdi mdi-inbox-multiple fs-1"></i><p class="mt-2">Tidak ada antrian</p></div>`;
        }
        $('#listMenunggu').html(htmlMenunggu);

        // Update list terlewat
        $('#badgeTerlewat').text(data.terlewat.length);
        let htmlTerlewat = '';
        if (data.terlewat.length > 0) {
            data.terlewat.forEach(item => {
                htmlTerlewat += `
                    <div class="list-group-item d-flex justify-content-between align-items-center" id="item-${item.id}">
                        <div>
                            <h5 class="mb-1 fw-bold text-danger">${item.nomor_antrian}</h5>
                            <small class="text-muted">${item.nama_pengunjung}</small>
                        </div>
                        <button class="btn btn-sm btn-outline-success rounded-pill btn-recall" data-id="${item.id}" title="Panggil Ulang">
                            <i class="mdi mdi-reload"></i>
                        </button>
                    </div>
                `;
            });
        } else {
            htmlTerlewat = `<div class="text-center text-muted p-4"><i class="mdi mdi-check-circle-outline fs-1"></i><p class="mt-2">Tidak ada yang terlewat</p></div>`;
        }
        $('#listTerlewat').html(htmlTerlewat);
    }

    // Aksi tombol panggil
    $('#btnPanggil').click(function() {
        btnLoading(this);
        $.post("{{ route('antrian.panggil') }}", { _token: '{{ csrf_token() }}' })
            .done(function(res) {
                // Berhasil dipanggil, UI akan otomatis diupdate via SSE
            })
            .fail(function(err) {
                Swal.fire('Oops!', err.responseJSON?.message || 'Terjadi kesalahan', 'error');
            })
            .always(() => {
                $('#btnPanggil').removeClass('disabled').prop('disabled', false).html('<i class="mdi mdi-bullhorn me-2"></i> Panggil Berikutnya');
            });
    });

    // Aksi tombol skip
    $(document).on('click', '.btn-skip', function() {
        let id = $(this).data('id');
        $.post(`/antrian/admin/skip/${id}`, { _token: '{{ csrf_token() }}' });
    });

    // Aksi tombol recall
    $(document).on('click', '.btn-recall', function() {
        let id = $(this).data('id');
        $.post(`/antrian/admin/panggil-ulang/${id}`, { _token: '{{ csrf_token() }}' });
    });
});
</script>
@endpush

