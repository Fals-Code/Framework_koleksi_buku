@extends('layouts.app')

@section('content')
<style>
    .content-wrapper { animation: fadeIn 0.6s ease-out; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

    .kasir-card {
        border: none !important;
        border-radius: 20px !important;
        box-shadow: 0 8px 25px rgba(0,0,0,0.03) !important;
        background: #ffffff;
    }

    .form-control-kasir {
        border-radius: 12px !important;
        border: 1.5px solid #f0f0f0 !important;
        padding: 12px 15px !important;
        transition: all 0.3s;
        background: #fff !important;
        font-size: 14px;
    }
    .form-control-kasir:focus {
        border-color: #6a11cb !important;
        box-shadow: 0 0 0 0.2rem rgba(106, 17, 203, 0.1) !important;
    }
    .form-control-kasir[readonly] {
        background: #fafafa !important;
        color: #888;
    }

    /* Tabel transaksi */
    .table-trx thead th {
        background: linear-gradient(135deg, #6a11cb 0%, #b66dff 100%);
        color: #fff;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 11px;
        letter-spacing: 1px;
        padding: 14px 16px;
        border: none;
    }
    .table-trx tbody tr {
        transition: all 0.2s;
    }
    .table-trx tbody tr:hover {
        background-color: rgba(106, 17, 203, 0.04) !important;
    }
    .table-trx tbody td {
        padding: 13px 16px;
        vertical-align: middle;
        border-bottom: 1px solid #f5f5f5;
    }

    .kode-tag {
        background: rgba(106, 17, 203, 0.1);
        color: #6a11cb;
        padding: 4px 12px;
        border-radius: 8px;
        font-weight: bold;
        font-size: 12px;
        font-family: 'JetBrains Mono', monospace;
    }
    .price-val {
        color: #27ae60;
        font-weight: 700;
        font-family: 'JetBrains Mono', monospace;
    }
    .subtotal-val {
        color: #e67e22;
        font-weight: 700;
        font-family: 'JetBrains Mono', monospace;
    }

    /* Total bar */
    .total-bar {
        background: linear-gradient(135deg, #6a11cb 0%, #b66dff 100%);
        border-radius: 16px;
        padding: 20px 28px;
        color: #fff;
    }
    .total-bar .label { font-size: 14px; opacity: 0.85; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; }
    .total-bar .amount { font-size: 32px; font-weight: 800; font-family: 'JetBrains Mono', monospace; }

    /* Empty state */
    .empty-trx {
        padding: 50px 20px;
        text-align: center;
        color: #ccc;
    }
    .empty-trx i { font-size: 52px; margin-bottom: 12px; display: block; }

    /* Btn loading */
    .btn-loading .btn-text { display: none; }
    .btn-loading .btn-spinner { display: inline-flex !important; align-items: center; }

    /* Row animation */
    @keyframes slideIn { from { opacity: 0; transform: translateX(-10px); } to { opacity: 1; transform: translateX(0); } }
    .row-anim { animation: slideIn 0.35s ease-out; }

    /* Hapus button */
    .btn-hapus-row {
        width: 30px; height: 30px;
        border-radius: 50%;
        border: none;
        background: rgba(231, 76, 60, 0.1);
        color: #e74c3c;
        font-size: 16px;
        display: flex; align-items: center; justify-content: center;
        transition: all 0.2s;
        cursor: pointer;
    }
    .btn-hapus-row:hover {
        background: #e74c3c;
        color: #fff;
        transform: scale(1.1);
    }

    /* Input kode focus highlight */
    #kodeBarang {
        font-family: 'JetBrains Mono', monospace;
        font-weight: 700;
        font-size: 16px;
        letter-spacing: 1px;
    }

    /* Loader pada input kode */
    .input-kode-wrapper {
        position: relative;
    }
    .input-kode-wrapper .kode-spinner {
        position: absolute;
        right: 14px;
        top: 50%;
        transform: translateY(-50%);
        display: none;
    }
    .input-kode-wrapper.loading .kode-spinner {
        display: block;
    }
    .input-kode-wrapper.loading #kodeBarang {
        padding-right: 40px !important;
        background: #f8f8ff !important;
    }

    /* Status indicator */
    .search-status {
        font-size: 11px;
        margin-top: 4px;
        min-height: 16px;
    }

    /* Autocomplete Suggestions */
    #suggestionContainer {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        z-index: 1000;
        background: white;
        border-radius: 12px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        border: 1px solid #eee;
        margin-top: 5px;
        max-height: 300px;
        overflow-y: auto;
        display: none;
    }
    .suggestion-item {
        padding: 12px 15px;
        cursor: pointer;
        transition: all 0.2s;
        border-bottom: 1px solid #f8f8f8;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .suggestion-item:last-child { border-bottom: none; }
    .suggestion-item:hover {
        background: #f0f7ff;
        padding-left: 20px;
    }
    .suggestion-item .item-name {
        font-weight: 600;
        color: #333;
    }
    .suggestion-item .item-code {
        font-family: 'JetBrains Mono', monospace;
        font-size: 11px;
        color: #6a11cb;
        background: rgba(106, 17, 203, 0.05);
        padding: 2px 6px;
        border-radius: 4px;
    }
    .suggestion-item .item-price {
        font-family: 'JetBrains Mono', monospace;
        font-weight: 700;
        color: #27ae60;
    }

    /* Qty Input in Table */
    .qty-input-table {
        width: 70px !important;
        padding: 5px 8px !important;
        border-radius: 8px !important;
        text-align: center;
        border: 1.5px solid #eee !important;
        font-weight: bold;
    }
    .qty-input-table:focus {
        border-color: #6a11cb !important;
        box-shadow: none !important;
    }
</style>

<div class="page-header flex-wrap">
    <div class="header-left">
        <h3 class="page-title text-dark fw-bold">
            <span class="page-title-icon bg-gradient-primary text-white me-2 shadow-sm">
                <i class="mdi mdi-cash-register"></i>
            </span> Kasir POS
        </h3>
    </div>
    <div class="header-right d-flex align-items-center mt-2 mt-sm-0">
        <span class="badge bg-light text-dark p-2 px-3" style="border-radius: 10px;">
            <i class="mdi mdi-clock-outline me-1"></i> <span id="jamSekarang"></span>
        </span>
    </div>
</div>

<div class="row">
    {{-- FORM INPUT BARANG --}}
    <div class="col-12 grid-margin">
        <div class="card kasir-card">
            <div class="card-body">
                <h5 class="fw-bold mb-1 text-dark">
                    <i class="mdi mdi-barcode-scan text-primary me-2"></i>Input Barang
                </h5>
                <p class="text-muted small mb-4">Tekan <kbd>Enter</kbd> di Kode Barang untuk mencari otomatis via Axios</p>

                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="small fw-bold text-muted">KODE BARANG</label>
                        <div class="input-kode-wrapper" id="kodeWrapper">
                            <input type="text" id="kodeBarang" class="form-control form-control-kasir" placeholder="Ketik nama atau kode..." autocomplete="off" autofocus>
                            <div id="suggestionContainer"></div>
                            <div class="kode-spinner">
                                <span class="spinner-border spinner-border-sm text-primary"></span>
                            </div>
                        </div>
                        <div class="search-status" id="searchStatus"></div>
                    </div>
                    <div class="col-md-3">
                        <label class="small fw-bold text-muted">NAMA BARANG</label>
                        <input type="text" id="namaBarang" class="form-control form-control-kasir" readonly placeholder="-">
                    </div>
                    <div class="col-md-2">
                        <label class="small fw-bold text-muted">HARGA</label>
                        <input type="text" id="hargaBarang" class="form-control form-control-kasir" readonly placeholder="-">
                    </div>
                    <div class="col-md-2">
                        <label class="small fw-bold text-muted">JUMLAH</label>
                        <input type="number" id="jumlahBarang" class="form-control form-control-kasir text-center fw-bold" value="1" min="1">
                    </div>
                    <div class="col-md-2">
                        <button type="button" id="btnTambahItem" class="btn btn-gradient-primary w-100 fw-bold rounded-pill py-3" disabled>
                            <i class="mdi mdi-cart-plus me-1"></i> TAMBAH
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- TABEL TRANSAKSI --}}
    <div class="col-12 grid-margin">
        <div class="card kasir-card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-trx mb-0" id="tabelTransaksi">
                        <thead>
                            <tr>
                                <th width="60">No</th>
                                <th width="120">Kode</th>
                                <th>Nama Barang</th>
                                <th class="text-end" width="140">Harga</th>
                                <th class="text-center" width="80">Qty</th>
                                <th class="text-end" width="160">Subtotal</th>
                                <th width="50"></th>
                            </tr>
                        </thead>
                        <tbody id="bodyTransaksi">
                        </tbody>
                    </table>

                    <div class="empty-trx" id="emptyTrx">
                        <i class="mdi mdi-cart-outline"></i>
                        <p class="mb-0 fw-bold">Belum ada barang</p>
                        <small>Masukkan kode barang di atas untuk mulai transaksi</small>
                    </div>
                </div>

                {{-- TOTAL + BAYAR --}}
                <div class="mt-4">
                    <div class="total-bar d-flex justify-content-between align-items-center">
                        <div>
                            <div class="label">TOTAL PEMBAYARAN</div>
                            <div class="amount" id="totalDisplay">Rp 0</div>
                        </div>
                        <button type="button" id="btnBayar" class="btn btn-light btn-lg fw-bold rounded-pill px-5 py-3 shadow-sm" disabled>
                            <span class="btn-text"><i class="mdi mdi-cash-check me-2"></i>BAYAR</span>
                            <span class="btn-spinner d-none">
                                <span class="spinner-border spinner-border-sm me-2"></span> Memproses...
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script-page')
{{-- Axios CDN (dicoba load, jika gagal akan pakai fallback jQuery) --}}
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script>
// ============================================
// AXIOS FALLBACK: Jika CDN diblokir browser,
// gunakan jQuery $.ajax sebagai pengganti
// ============================================
if (typeof axios === 'undefined') {
    console.warn('[Kasir] Axios CDN diblokir browser, menggunakan jQuery fallback.');
    var axios = {
        get: function(url) {
            return $.ajax({ url: url, type: 'GET', dataType: 'json' });
        },
        post: function(url, data) {
            return $.ajax({
                url: url,
                type: 'POST',
                data: JSON.stringify(data),
                contentType: 'application/json',
                dataType: 'json',
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
            });
        }
    };
}

$(document).ready(function() {

    // ============================================
    // STATE
    // ============================================
    let cart = [];
    let currentBarang = null;
    let isSearching = false;
    let debounceTimer;

    // ============================================
    // UTILITAS
    // ============================================
    function formatRupiah(n) {
        return new Intl.NumberFormat('id-ID').format(n);
    }

    function updateJam() {
        let now = new Date();
        $('#jamSekarang').text(now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' }));
    }
    setInterval(updateJam, 1000);
    updateJam();

    // ============================================
    // LOADER: Tampilkan/sembunyikan spinner
    // ============================================
    function showLoader() {
        isSearching = true;
        $('#kodeWrapper').addClass('loading');
        $('#searchStatus').html('<span class="text-primary"><i class="mdi mdi-loading mdi-spin me-1"></i>Mencari barang...</span>');
    }

    function hideLoader() {
        isSearching = false;
        $('#kodeWrapper').removeClass('loading');
    }

    // ============================================
    // Logika Tombol TAMBAH
    // ============================================
    function toggleTambahBtn() {
        let jumlah = parseInt($('#jumlahBarang').val()) || 0;
        let aktif = (currentBarang !== null && jumlah > 0);
        $('#btnTambahItem').prop('disabled', !aktif);
    }

    $('#jumlahBarang').on('input change', function() {
        toggleTambahBtn();
    });

    // ============================================
    // FUNGSI: Autocomplete (Suggest)
    // ============================================
    function fetchSuggestions(query) {
        if (query.length < 2) {
            $('#suggestionContainer').hide().empty();
            return;
        }

        axios.get('/kasir/search-barang?query=' + query)
            .then(function(response) {
                let items = response.data || [];
                let container = $('#suggestionContainer');
                container.empty();

                if (items.length > 0) {
                    items.forEach(function(item) {
                        let html = `
                            <div class="suggestion-item" data-kode="${item.id_barang}">
                                <div>
                                    <div class="item-name">${item.nama}</div>
                                    <span class="item-code">${item.id_barang}</span>
                                </div>
                                <div class="item-price">Rp ${formatRupiah(item.harga)}</div>
                            </div>
                        `;
                        container.append(html);
                    });
                    container.show();
                } else {
                    container.hide();
                }
            })
            .catch(function(err) {
                console.error('Error fetching suggestions', err);
            });
    }

    $('#kodeBarang').on('input', function() {
        let query = $(this).val();
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(function() {
            fetchSuggestions(query);
        }, 300);
    });

    // Pilih item dari suggestion
    $(document).on('click', '.suggestion-item', function() {
        let kode = $(this).data('kode');
        $('#kodeBarang').val(kode);
        $('#suggestionContainer').hide().empty();
        cariBarang(kode);
    });

    // Klik di luar sembunyikan suggestion
    $(document).on('click', function(e) {
        if (!$(e.target).closest('#kodeWrapper').length) {
            $('#suggestionContainer').hide();
        }
    });

    // ============================================
    // FUNGSI: Cari barang via Axios GET
    // (Axios CDN atau jQuery fallback)
    // ============================================
    function cariBarang(kode) {
        if (isSearching) return;
        if (kode === '') return;

        currentBarang = null;
        $('#namaBarang').val('');
        $('#hargaBarang').val('');
        $('#btnTambahItem').prop('disabled', true);

        showLoader();

        // Axios GET (Promise style sesuai modul)
        let req = axios.get('/kasir/cari-barang/' + kode);
        
        req.then(function(response) {
                // Tangani perbedaan format response antara Axios asli vs jQuery fallback
                let resData = response.data || response;
                let data = resData.data;

                currentBarang = {
                    kode: data.id_barang,
                    nama: data.nama,
                    harga: data.harga
                };

                // Isi otomatis field Nama dan Harga (readonly)
                $('#namaBarang').val(data.nama);
                $('#hargaBarang').val('Rp ' + formatRupiah(data.harga));

                // Status sukses
                $('#searchStatus').html('<span class="text-success"><i class="mdi mdi-check-circle me-1"></i>Barang ditemukan</span>');

                // Set fokus ke input Jumlah
                $('#jumlahBarang').val(1).focus().select();

                // Aktifkan tombol TAMBAH
                toggleTambahBtn();
            })
            .catch(function(error) {
                currentBarang = null;
                $('#namaBarang').val('');
                $('#hargaBarang').val('');
                $('#btnTambahItem').prop('disabled', true);

                $('#searchStatus').html('<span class="text-danger"><i class="mdi mdi-alert-circle me-1"></i>Barang tidak ditemukan</span>');

                // Tangani format error dari Axios asli vs jQuery
                let msg = 'Gagal mencari barang.';
                if (error.response && error.response.data) {
                    msg = error.response.data.message;
                } else if (error.responseJSON) {
                    msg = error.responseJSON.message;
                }

                Swal.fire({
                    icon: 'error',
                    title: 'Barang Tidak Ditemukan',
                    text: msg,
                    confirmButtonColor: '#6a11cb'
                });

                $('#kodeBarang').val('');
                setTimeout(function() { $('#kodeBarang').focus(); }, 100);
            });

        // Ensure hideLoader is called for both real Axios (.finally) and jQuery fallback (.always)
        if (typeof req.finally === 'function') {
            req.finally(function() { hideLoader(); });
        } else if (typeof req.always === 'function') {
            req.always(function() { hideLoader(); });
        }
    }

    // ============================================
    // EVENT: Enter di Kode Barang → Cari Barang
    // ============================================
    $('#kodeBarang').on('keydown', function(e) {
        if (e.key === 'Enter' || e.keyCode === 13) {
            e.preventDefault();
            e.stopPropagation();
            let kode = $(this).val().trim();
            cariBarang(kode);
            $('#suggestionContainer').hide();
        }
    });

    // ============================================
    // EVENT: Tombol TAMBAH / Enter di Jumlah
    // ============================================
    function tambahKeCart() {
        if (!currentBarang) return;

        let jumlah = parseInt($('#jumlahBarang').val()) || 1;
        if (jumlah < 1) jumlah = 1;

        // Cek apakah barang sudah ada di cart
        let existing = cart.find(function(item) { return item.kode === currentBarang.kode; });

        if (existing) {
            // Update jumlah & subtotal
            existing.jumlah += jumlah;
            existing.subtotal = existing.harga * existing.jumlah;
        } else {
            // Tambah baru
            cart.push({
                kode: currentBarang.kode,
                nama: currentBarang.nama,
                harga: currentBarang.harga,
                jumlah: jumlah,
                subtotal: currentBarang.harga * jumlah
            });
        }

        // Notifikasi toast
        Swal.fire({
            icon: 'success',
            title: currentBarang.nama + ' ditambahkan!',
            text: jumlah + ' x Rp ' + formatRupiah(currentBarang.harga),
            timer: 1200,
            showConfirmButton: false,
            toast: true,
            position: 'top-end'
        });

        renderTabel();
        resetInput();
    }

    $('#btnTambahItem').on('click', function() {
        tambahKeCart();
    });

    $('#jumlahBarang').on('keydown', function(e) {
        if (e.key === 'Enter' || e.keyCode === 13) {
            e.preventDefault();
            e.stopPropagation();
            tambahKeCart();
        }
    });

    // ============================================
    // RENDER TABEL
    // ============================================
    function renderTabel() {
        let tbody = $('#bodyTransaksi');
        tbody.empty();

        if (cart.length === 0) {
            $('#emptyTrx').show();
            $('#btnBayar').prop('disabled', true);
            $('#totalDisplay').text('Rp 0');
            return;
        }

        $('#emptyTrx').hide();
        $('#btnBayar').prop('disabled', false);

        let total = 0;

        cart.forEach(function(item, index) {
            total += item.subtotal;
            let row = `
                <tr class="row-anim" data-kode="${item.kode}">
                    <td class="text-center text-muted fw-bold">${index + 1}</td>
                    <td><span class="kode-tag">${item.kode}</span></td>
                    <td class="fw-bold text-dark">${item.nama}</td>
                    <td class="text-end"><span class="price-val">Rp ${formatRupiah(item.harga)}</span></td>
                    <td class="text-center">
                        <input type="number" class="form-control qty-input-table" value="${item.jumlah}" min="1" data-index="${index}">
                    </td>
                    <td class="text-end"><span class="subtotal-val" id="subtotal-${index}">Rp ${formatRupiah(item.subtotal)}</span></td>
                    <td class="text-center">
                        <button type="button" class="btn-hapus-row" data-index="${index}" title="Hapus">
                            <i class="mdi mdi-close"></i>
                        </button>
                    </td>
                </tr>
            `;
            tbody.append(row);
        });

        $('#totalDisplay').text('Rp ' + formatRupiah(total));
    }

    // Update Qty di Tabel
    $('#bodyTransaksi').on('input change', '.qty-input-table', function() {
        let idx = $(this).data('index');
        let newQty = parseInt($(this).val()) || 1;
        if (newQty < 1) newQty = 1;

        cart[idx].jumlah = newQty;
        cart[idx].subtotal = cart[idx].harga * newQty;

        // Update subtotal display row
        $(`#subtotal-${idx}`).text('Rp ' + formatRupiah(cart[idx].subtotal));

        // Update total payout
        let total = cart.reduce((sum, item) => sum + item.subtotal, 0);
        $('#totalDisplay').text('Rp ' + formatRupiah(total));
    });

    // Hapus baris
    $('#bodyTransaksi').on('click', '.btn-hapus-row', function() {
        let idx = $(this).data('index');
        cart.splice(idx, 1);
        renderTabel();
    });

    // ============================================
    // RESET INPUT: Kembalikan semua ke kondisi awal
    // agar bisa menambahkan barang baru lagi
    // ============================================
    function resetInput() {
        currentBarang = null;
        $('#kodeBarang').val('');
        $('#namaBarang').val('');
        $('#hargaBarang').val('');
        $('#jumlahBarang').val(1);
        $('#btnTambahItem').prop('disabled', true);
        $('#searchStatus').html('');
        $('#suggestionContainer').hide().empty();
        // Fokus kembali ke input Kode Barang
        setTimeout(function() { $('#kodeBarang').focus(); }, 50);
    }

    // ============================================
    // BAYAR → Axios POST
    // ============================================
    $('#btnBayar').on('click', function() {
        if (cart.length === 0) return;

        let btn = $(this);
        let total = cart.reduce((sum, item) => sum + item.subtotal, 0);

        Swal.fire({
            title: 'Proses Pembayaran?',
            html: `Total: <b class="text-primary">Rp ${formatRupiah(total)}</b><br><small class="text-muted">${cart.length} jenis barang</small>`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#6a11cb',
            cancelButtonColor: '#95a5a6',
            confirmButtonText: 'Ya, Bayar!',
            cancelButtonText: 'Batal'
        }).then(function(result) {
            if (result.isConfirmed) {

                // Aktifkan spinner / pencegahan double submit
                btn.prop('disabled', true).addClass('btn-loading');

                // Siapkan data
                let payload = {
                    _token: '{{ csrf_token() }}',
                    total: total,
                    items: cart.map(function(item) {
                        return {
                            kode: item.kode,
                            jumlah: item.jumlah,
                            subtotal: item.subtotal
                        };
                    })
                };

                // Axios POST (Promise style sesuai modul)
                axios.post('/kasir/simpan', payload)
                    .then(function(response) {
                        // Reset semua
                        cart = [];
                        renderTabel();
                        resetInput();

                        btn.prop('disabled', true).removeClass('btn-loading');

                        // Tangani format response Axios vs jQuery
                        let resData = response.data || response;

                        Swal.fire({
                            icon: 'success',
                            title: 'Transaksi Berhasil!',
                            text: resData.message,
                            confirmButtonColor: '#6a11cb'
                        });
                    })
                    .catch(function(error) {
                        btn.prop('disabled', false).removeClass('btn-loading');

                        let msg = 'Terjadi kesalahan saat menyimpan transaksi.';
                        if (error.response && error.response.data) {
                            msg = error.response.data.message;
                        } else if (error.responseJSON) {
                            msg = error.responseJSON.message;
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: msg,
                            confirmButtonColor: '#e74c3c'
                        });
                    });
            }
        });
    });

    // Inisialisasi
    renderTabel();
});
</script>
@endpush
