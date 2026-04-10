@extends('layouts.app')

@section('content')
<style>
    :root {
        --pos-primary: #6a11cb;
        --pos-secondary: #b66dff;
        --pos-bg: #f8f9fa;
        --pos-card-bg: rgba(255, 255, 255, 0.9);
        --pos-glass-bg: rgba(255, 255, 255, 0.7);
        --pos-border: rgba(255, 255, 255, 0.3);
        --pos-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.07);
    }

    .content-wrapper { 
        animation: fadeIn 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        padding: 1.5rem !important;
        background: #f4f7ff !important;
    }
    
    @keyframes fadeIn { 
        from { opacity: 0; transform: translateY(20px); } 
        to { opacity: 1; transform: translateY(0); } 
    }

    .pos-container {
        display: flex;
        gap: 1.5rem;
        align-items: flex-start;
    }

    .pos-left { flex: 1; min-width: 0; }
    .pos-right { width: 400px; flex-shrink: 0; position: sticky; top: 1.5rem; }

    .glass-card {
        background: var(--pos-card-bg);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid var(--pos-border);
        border-radius: 24px;
        box-shadow: var(--pos-shadow);
        transition: all 0.3s ease;
        overflow: hidden;
    }

    .glass-card:hover {
        box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.12);
    }

    /* Total Section */
    .summary-card {
        background: linear-gradient(135deg, #6a11cb 0%, #b66dff 100%);
        color: white;
        border: none;
        padding: 2rem;
        margin-bottom: 1.5rem;
    }

    .summary-card .label {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.1rem;
        opacity: 0.8;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .summary-card .total-amount {
        font-size: 3rem;
        font-weight: 800;
        font-family: 'Outfit', sans-serif;
        line-height: 1;
        margin-bottom: 0;
    }

    /* Table Section */
    .cart-card {
        min-height: calc(100vh - 12rem);
        display: flex;
        flex-direction: column;
    }

    .table-container {
        flex-grow: 1;
        overflow-y: auto;
    }

    .table-pos thead th {
        background: transparent;
        border-bottom: 2px solid #f0f0f0;
        color: #888;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05rem;
        padding: 1.25rem 1rem;
    }

    .table-pos tbody tr {
        transition: all 0.2s;
        border-bottom: 1px solid #f8f8f8;
    }

    .table-pos tbody tr:hover {
        background: rgba(106, 17, 203, 0.02);
        transform: scale(1.002);
    }

    .table-pos td {
        padding: 1.25rem 1rem;
        vertical-align: middle;
    }

    .item-info {
        display: flex;
        flex-direction: column;
    }

    .item-name {
        font-weight: 700;
        color: #2d3436;
        font-size: 1rem;
        margin-bottom: 0.2rem;
    }

    .item-code-badge {
        font-size: 0.7rem;
        background: rgba(106, 17, 203, 0.1);
        color: #6a11cb;
        padding: 2px 8px;
        border-radius: 6px;
        width: fit-content;
        font-weight: 600;
        font-family: 'JetBrains Mono', monospace;
    }

    /* Input Controls */
    .pos-input-group {
        background: white;
        border-radius: 16px;
        padding: 0.5rem;
        display: flex;
        align-items: center;
        box-shadow: 0 2px 10px rgba(0,0,0,0.03);
        border: 1.5px solid #eee;
        transition: all 0.3s;
    }

    .pos-input-group:focus-within {
        border-color: #6a11cb;
        box-shadow: 0 0 0 4px rgba(106, 17, 203, 0.1);
    }

    .pos-input-group input {
        border: none !important;
        box-shadow: none !important;
        padding: 0.75rem 1rem !important;
        font-size: 1.1rem;
        font-weight: 600;
        width: 100%;
    }

    .scan-btn {
        background: #f8f9fa;
        color: #6a11cb;
        border: none;
        width: 45px;
        height: 45px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }

    .scan-btn:hover {
        background: #6a11cb;
        color: white;
    }

    /* Suggestions */
    #suggestionContainer {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: white;
        border-radius: 16px;
        margin-top: 10px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        z-index: 1000;
        border: 1px solid #eee;
        overflow: hidden;
        display: none;
    }

    .suggestion-item {
        padding: 1rem 1.5rem;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: all 0.2s;
    }

    .suggestion-item:hover {
        background: #f4f7ff;
        padding-left: 2rem;
    }

    /* Qty Input */
    .qty-control {
        display: flex;
        align-items: center;
        background: #f8f9fa;
        border-radius: 10px;
        padding: 2px;
        width: fit-content;
    }

    .qty-input-table {
        width: 45px;
        text-align: center;
        border: none;
        background: transparent;
        font-weight: 700;
        font-size: 0.9rem;
    }

    /* Empty Cart */
    .empty-state {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 4rem 2rem;
        color: #ccc;
    }

    .empty-state i {
        font-size: 4rem;
        margin-bottom: 1.5rem;
        background: linear-gradient(135deg, #f0f0f0 0%, #e0e0e0 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    /* Action Buttons */
    .btn-checkout {
        background: white;
        color: #6a11cb;
        border: none;
        border-radius: 16px;
        padding: 1.25rem;
        font-weight: 800;
        font-size: 1.1rem;
        width: 100%;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    .btn-checkout:not(:disabled):hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        color: #550bb1;
    }

    .btn-checkout:disabled {
        opacity: 0.7;
        cursor: not-allowed;
    }

    /* Animations */
    .row-anim {
        animation: slideInLeft 0.4s ease-out forwards;
    }

    @keyframes slideInLeft {
        from { opacity: 0; transform: translateX(-20px); }
        to { opacity: 1; transform: translateX(0); }
    }

    @media (max-width: 1100px) {
        .pos-container { flex-direction: column; }
        .pos-right { width: 100%; position: static; }
    }
</style>

<div class="pos-container">
    {{-- BAGIAN KIRI: TABEL BARANG --}}
    <div class="pos-left">
        <div class="glass-card cart-card">
            <div class="p-4 border-bottom d-flex justify-content-between align-items-center bg-white bg-opacity-50">
                <div>
                    <h4 class="fw-bold mb-0 text-dark">Keranjang Belanja</h4>
                    <p class="text-muted small mb-0">Kelola item transaksi hari ini</p>
                </div>
                <div class="badge bg-light text-dark p-2 px-3 border" style="border-radius: 12px;">
                    <i class="mdi mdi-clock-outline me-1"></i> <span id="jamSekarang"></span>
                </div>
            </div>

            <div class="table-container">
                <table class="table table-pos mb-0" id="tabelTransaksi">
                    <thead>
                        <tr>
                            <th width="50" class="text-center">#</th>
                            <th>ITEM DETAIL</th>
                            <th width="120" class="text-end">HARGA</th>
                            <th width="100" class="text-center">QTY</th>
                            <th width="140" class="text-end">SUBTOTAL</th>
                            <th width="60"></th>
                        </tr>
                    </thead>
                    <tbody id="bodyTransaksi">
                        <!-- Items will be rendered here -->
                    </tbody>
                </table>

                <div class="empty-state" id="emptyTrx">
                    <i class="mdi mdi-cart-remove"></i>
                    <h5 class="fw-bold text-dark opacity-50">Keranjang Kosong</h5>
                    <p class="mb-0 small">Mulai transaksi dengan mencari produk di samping</p>
                </div>
            </div>
        </div>
    </div>

    {{-- BAGIAN KANAN: INPUT & SUMMARY --}}
    <div class="pos-right">
        {{-- TOTAL DISPLAY --}}
        <div class="glass-card summary-card">
            <div class="label">Total Pembayaran</div>
            <h2 class="total-amount" id="totalDisplay">Rp 0</h2>
            <div class="mt-4 pt-4 border-top border-white border-opacity-20">
                <button type="button" id="btnBayar" class="btn-checkout" disabled>
                    <span class="btn-text">PROSES PEMBAYARAN</span>
                    <span class="btn-spinner d-none">
                        <span class="spinner-border spinner-border-sm me-2"></span> MEMPROSES...
                    </span>
                </button>
            </div>
        </div>

        {{-- INPUT BARANG --}}
        <div class="glass-card p-4">
            <h5 class="fw-bold mb-3 text-dark d-flex align-items-center">
                <i class="mdi mdi-barcode-scan text-primary me-2 fs-4"></i> Input Produk
            </h5>
            
            <div class="mb-4 position-relative" id="kodeWrapper">
                <label class="small fw-bold text-muted mb-2">CARI NAMA ATAU KODE</label>
                <div class="pos-input-group">
                    <input type="text" id="kodeBarang" placeholder="Ketik di sini..." autocomplete="off" autofocus>
                    <button class="scan-btn" id="btnOpenScanner" title="Buka Kamera">
                        <i class="mdi mdi-camera"></i>
                    </button>
                </div>
                <div id="suggestionContainer"></div>
                <div class="search-status mt-2 small" id="searchStatus" style="min-height: 20px;"></div>
            </div>

            <div class="row g-3">
                <div class="col-12">
                    <div class="p-3 rounded-4 bg-light border border-white">
                        <label class="small fw-bold text-muted mb-1">PRODUK TERPILIH</label>
                        <h6 id="namaBarangDisplay" class="fw-bold text-dark mb-0">-</h6>
                        <div id="hargaBarangDisplay" class="text-primary fw-bold mt-1">Rp 0</div>
                        
                        {{-- Hidden inputs for logic --}}
                        <input type="hidden" id="namaBarang">
                        <input type="hidden" id="hargaBarang">
                    </div>
                </div>
                
                <div class="col-8">
                    <label class="small fw-bold text-muted mb-2">JUMLAH BELI</label>
                    <div class="pos-input-group p-0">
                        <input type="number" id="jumlahBarang" class="text-center" value="1" min="1">
                    </div>
                </div>
                
                <div class="col-4">
                    <label class="small fw-bold text-muted mb-2">&nbsp;</label>
                    <button type="button" id="btnTambahItem" class="btn btn-gradient-primary w-100 h-100 rounded-4" disabled style="min-height: 48px;">
                        <i class="mdi mdi-plus fs-4"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL SCANNER --}}
<div class="modal fade" id="scannerModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content overflow-hidden border-0 shadow-lg" style="border-radius: 30px;">
            <div class="modal-header bg-gradient-primary text-white border-0 p-4">
                <h5 class="modal-title fw-bold"><i class="mdi mdi-camera-iris me-2"></i>Scanner Barcode</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 text-center bg-light">
                <div id="reader" style="border-radius: 20px; overflow: hidden; border: none !important;"></div>
                <p class="text-muted mt-4 small mb-0">Posisikan barcode produk tepat di dalam bingkai kamera</p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script-page')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

<script>
$(document).ready(function() {
    let cart = [];
    let currentBarang = null;
    let isSearching = false;
    let debounceTimer;
    let html5QrCode = null;

    function formatRupiah(n) {
        return new Intl.NumberFormat('id-ID').format(n);
    }

    function updateJam() {
        let now = new Date();
        $('#jamSekarang').text(now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' }));
    }
    setInterval(updateJam, 1000);
    updateJam();

    function showLoader() {
        isSearching = true;
        $('#searchStatus').html('<span class="text-primary"><i class="mdi mdi-loading mdi-spin me-1"></i>Mencari...</span>');
    }

    function hideLoader() {
        isSearching = false;
    }

    function toggleTambahBtn() {
        let jumlah = parseInt($('#jumlahBarang').val()) || 0;
        let aktif = (currentBarang !== null && jumlah > 0);
        $('#btnTambahItem').prop('disabled', !aktif);
    }

    $('#jumlahBarang').on('input change', toggleTambahBtn);

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
                        let displayCode = item.barcode || item.id_barang;
                        let html = `
                            <div class="suggestion-item" data-search-key="${displayCode}">
                                <div>
                                    <div class="fw-bold text-dark">${item.nama}</div>
                                    <span class="small text-muted font-monospace">${displayCode}</span>
                                </div>
                                <div class="text-primary fw-bold">Rp ${formatRupiah(item.harga)}</div>
                            </div>
                        `;
                        container.append(html);
                    });
                    container.show();
                } else {
                    container.hide();
                }
            })
            .catch(err => console.error('Error fetching suggestions', err));
    }

    $('#kodeBarang').on('input', function() {
        let query = $(this).val();
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => fetchSuggestions(query), 300);
    });

    $(document).on('click', '.suggestion-item', function() {
        let key = $(this).data('search-key');
        $('#kodeBarang').val(key);
        $('#suggestionContainer').hide().empty();
        cariBarang(key);
    });

    $(document).on('click', e => {
        if (!$(e.target).closest('#kodeWrapper').length) $('#suggestionContainer').hide();
    });

    function cariBarang(kode) {
        if (isSearching || !kode) return;

        currentBarang = null;
        $('#namaBarangDisplay').text('-');
        $('#hargaBarangDisplay').text('Rp 0');
        $('#namaBarang').val('');
        $('#hargaBarang').val('');
        $('#btnTambahItem').prop('disabled', true);

        showLoader();

        axios.get('/kasir/cari-barang/' + kode)
            .then(function(response) {
                let resData = response.data || response;
                let data = resData.data;

                currentBarang = {
                    kode: data.id_barang,
                    barcode: data.barcode,
                    nama: data.nama,
                    harga: data.harga
                };

                $('#namaBarangDisplay').text(data.nama);
                $('#hargaBarangDisplay').text('Rp ' + formatRupiah(data.harga));
                $('#namaBarang').val(data.nama);
                $('#hargaBarang').val(data.harga);
                $('#searchStatus').html('<span class="text-success small fw-bold"><i class="mdi mdi-check-circle me-1"></i>Ditemukan</span>');
                $('#jumlahBarang').val(1).focus().select();
                toggleTambahBtn();
            })
            .catch(function(error) {
                $('#searchStatus').html('<span class="text-danger small fw-bold"><i class="mdi mdi-alert-circle me-1"></i>Tidak ditemukan</span>');
                Swal.fire({
                    icon: 'error',
                    title: 'Waduh!',
                    text: 'Produk tidak ditemukan dalam sistem.',
                    confirmButtonColor: '#6a11cb'
                });
                $('#kodeBarang').val('').focus();
            })
            .finally(hideLoader);
    }

    const scannerModal = new bootstrap.Modal(document.getElementById('scannerModal'));
    $('#btnOpenScanner').on('click', () => scannerModal.show());

    $('#scannerModal').on('shown.bs.modal', function () {
        if (!html5QrCode) html5QrCode = new Html5Qrcode("reader");
        if (html5QrCode.isScanning) return;

        html5QrCode.start(
            { facingMode: "environment" }, 
            { fps: 20, qrbox: { width: 280, height: 280 } },
            (decodedText) => {
                $('#kodeBarang').val(decodedText);
                scannerModal.hide();
                setTimeout(() => cariBarang(decodedText), 300);
            }
        ).catch(err => $('#reader').html(`<div class="alert alert-danger mx-3">Kamera gagal!</div>`));
    });

    $('#scannerModal').on('hidden.bs.modal', function () {
        if (html5QrCode && html5QrCode.isScanning) html5QrCode.stop().then(() => $('#reader').empty());
    });

    $('#kodeBarang').on('keydown', e => {
        if (e.key === 'Enter') {
            e.preventDefault();
            cariBarang($(e.target).val().trim());
        }
    });

    function tambahKeCart() {
        if (!currentBarang) return;
        let jumlah = parseInt($('#jumlahBarang').val()) || 1;
        let existing = cart.find(item => item.kode === currentBarang.kode);
        if (existing) {
            existing.jumlah += jumlah;
            existing.subtotal = existing.harga * existing.jumlah;
        } else {
            cart.push({ ...currentBarang, jumlah, subtotal: currentBarang.harga * jumlah });
        }
        renderTabel();
        resetInput();
    }

    $('#btnTambahItem').on('click', tambahKeCart);
    $('#jumlahBarang').on('keydown', e => { if (e.key === 'Enter') tambahKeCart(); });

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
        cart.forEach((item, index) => {
            total += item.subtotal;
            tbody.append(`
                <tr class="row-anim">
                    <td class="text-center text-muted fw-bold small">${index + 1}</td>
                    <td>
                        <div class="item-info">
                            <span class="item-name">${item.nama}</span>
                            <span class="item-code-badge">${item.kode}</span>
                        </div>
                    </td>
                    <td class="text-end fw-bold text-dark">Rp ${formatRupiah(item.harga)}</td>
                    <td>
                        <div class="qty-control mx-auto">
                            <input type="number" class="qty-input-table" value="${item.jumlah}" min="1" data-index="${index}">
                        </div>
                    </td>
                    <td class="text-end fw-bold text-primary">Rp ${formatRupiah(item.subtotal)}</td>
                    <td class="text-center">
                        <button type="button" class="btn btn-link text-danger p-0 btn-hapus-row" data-index="${index}"><i class="mdi mdi-delete-outline fs-5"></i></button>
                    </td>
                </tr>
            `);
        });
        $('#totalDisplay').text('Rp ' + formatRupiah(total));
    }

    $('#bodyTransaksi').on('input change', '.qty-input-table', function() {
        let idx = $(this).data('index');
        let newQty = parseInt($(this).val()) || 1;
        if (newQty < 1) newQty = 1;
        cart[idx].jumlah = newQty;
        cart[idx].subtotal = cart[idx].harga * newQty;
        renderTabel();
    });

    $('#bodyTransaksi').on('click', '.btn-hapus-row', function() {
        cart.splice($(this).data('index'), 1);
        renderTabel();
    });

    function resetInput() {
        currentBarang = null;
        $('#kodeBarang').val('').focus();
        $('#namaBarangDisplay').text('-');
        $('#hargaBarangDisplay').text('Rp 0');
        $('#namaBarang').val('');
        $('#hargaBarang').val('');
        $('#jumlahBarang').val(1);
        $('#btnTambahItem').prop('disabled', true);
        $('#searchStatus').html('');
    }

    $('#btnBayar').on('click', function() {
        if (cart.length === 0) return;
        let btn = $(this);
        let total = cart.reduce((sum, item) => sum + item.subtotal, 0);
        Swal.fire({
            title: 'Bayar?',
            html: `<h2 class="text-primary fw-bold">Rp ${formatRupiah(total)}</h2>`,
            showCancelButton: true,
            confirmButtonColor: '#6a11cb',
            confirmButtonText: 'KIRIM'
        }).then(result => {
            if (result.isConfirmed) {
                btn.prop('disabled', true);
                $('.btn-spinner').removeClass('d-none');
                $('.btn-text').addClass('d-none');
                axios.post('/kasir/simpan', { _token: '{{ csrf_token() }}', total, items: cart.map(i => ({ kode: i.kode, jumlah: i.jumlah, subtotal: i.subtotal })) })
                    .then(res => {
                        cart = []; renderTabel(); resetInput();
                        Swal.fire({ icon: 'success', title: 'Berhasil!' });
                    })
                    .catch(err => Swal.fire({ icon: 'error', title: 'Gagal!' }))
                    .finally(() => {
                        btn.prop('disabled', false); $('.btn-spinner').addClass('d-none'); $('.btn-text').removeClass('d-none');
                    });
            }
        });
    });

    renderTabel();
});
</script>
@endpush
