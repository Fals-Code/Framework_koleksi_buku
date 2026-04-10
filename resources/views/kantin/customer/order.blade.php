@extends('layouts.app')

@section('content')
<style>
    :root {
        --purple-brand: #b66dff;
        --purple-light: rgba(182, 109, 255, 0.1);
        --glass-bg: rgba(255, 255, 255, 0.9);
        --card-shadow: 0 10px 30px rgba(0,0,0,0.08);
    }
    
    .vendor-card {
        cursor: pointer;
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        border: 1px solid #f0f0f0;
        border-radius: 20px;
        background: white;
    }
    .vendor-card:hover {
        transform: translateY(-8px);
        box-shadow: var(--card-shadow);
        border-color: var(--purple-brand);
    }
    .vendor-icon-box {
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        background: var(--purple-light);
        color: var(--purple-brand);
    }
    
    .menu-card {
        transition: all 0.3s ease;
        border: none;
        border-radius: 20px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.03);
        overflow: hidden;
    }
    .menu-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--card-shadow);
    }
    
    .item-img-container {
        width: 100px;
        height: 100px;
        border-radius: 15px;
        overflow: hidden;
        flex-shrink: 0;
    }
    .item-img-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    .menu-card:hover .item-img-container img {
        transform: scale(1.1);
    }
    
    .qty-badge {
        background: var(--purple-brand);
        color: white;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        font-weight: bold;
        position: absolute;
        top: -10px;
        right: -10px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    }
    
    .sticky-cart {
        top: 25px;
        border-radius: 25px;
        backdrop-filter: blur(10px);
        background: var(--glass-bg);
        border: 1px solid rgba(255,255,255,0.5);
    }
    
    .btn-qty {
        width: 30px;
        height: 30px;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        font-weight: bold;
    }
    
    .category-label {
        color: var(--purple-brand);
        font-weight: 800;
        letter-spacing: 1px;
        text-transform: uppercase;
        font-size: 0.75rem;
    }
    
    .cart-item-row {
        border-bottom: 1px solid #f8f9fa;
        padding-bottom: 12px;
        margin-bottom: 12px;
    }
    .cart-item-row:last-child {
        border-bottom: none;
    }
</style>

<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-food"></i>
        </span> Kantin Vokasi
    </h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('kantin.history') }}" class="btn btn-outline-primary btn-sm rounded-pill px-3">
                    <i class="mdi mdi-history me-1"></i> Pesanan Saya
                </a>
            </li>
        </ul>
    </nav>
</div>

<div class="row" id="order-app">
    <!-- Vendor Selection Grid -->
    <div class="col-12 mb-5" id="vendor-selection-area">
        <div class="card bg-transparent border-0 shadow-none">
            <div class="card-body p-0">
                <div class="d-flex justify-content-between align-items-end mb-4">
                    <div>
                        <h4 class="fw-bold mb-1">Pilih Warung Favorit</h4>
                        <p class="text-muted small mb-0">Temukan makanan lezat dari berbagai warung di kantin</p>
                    </div>
                </div>
                
                <div class="row">
                    @foreach($vendors as $v)
                    <div class="col-md-4 col-lg-3 mb-4">
                        <div class="card vendor-card h-100" onclick="selectVendor({{ $v->id }}, '{{ $v->nama_warung }}')">
                            <div class="card-body p-4 text-center">
                                <div class="vendor-icon-box mx-auto mb-3">
                                    <i class="mdi mdi-store mdi-36px"></i>
                                </div>
                                <h5 class="fw-bold mb-2">{{ $v->nama_warung }}</h5>
                                <div class="d-flex justify-content-center gap-2 mb-3">
                                    <span class="badge bg-light text-primary rounded-pill border">
                                        {{ $v->menu->count() }} Menu
                                    </span>
                                    <span class="badge bg-light text-success rounded-pill border">
                                        <i class="mdi mdi-star"></i> 4.8
                                    </span>
                                </div>
                                <button class="btn btn-outline-primary btn-sm rounded-pill px-4">Buka Warung</button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Menu Selection Area -->
    <div class="col-md-8" id="menu-area" style="display: none;">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-4 px-4 border-0">
                <div>
                    <span class="category-label d-block mb-1">Daftar Menu</span>
                    <h4 class="card-title fw-bold mb-0" id="selected-vendor-name"></h4>
                </div>
                <button class="btn btn-inverse-dark btn-sm rounded-pill px-3" onclick="resetStep()">
                    <i class="mdi mdi-arrow-left me-1"></i> Ganti Warung
                </button>
            </div>
            <div class="card-body px-4 pb-4 pt-2">
                <div class="row" id="menu-list">
                    @foreach($vendors as $v)
                    <div class="vendor-menu col-12" id="vendor-{{ $v->id }}-menu" style="display: none;">
                        <div class="row">
                            @foreach($v->menu as $m)
                            <div class="col-lg-6 mb-4">
                                <div class="card menu-card border border-light h-100">
                                    <div class="card-body p-3">
                                        <div class="d-flex align-items-center">
                                            <div class="item-img-container me-3 border">
                                                <img src="{{ $m->foto ? asset('storage/' . $m->foto) : 'https://placehold.co/100x100?text=' . urlencode($m->nama_makanan) }}" alt="{{ $m->nama_makanan }}">
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="d-flex justify-content-between align-items-start">
                                                    <div>
                                                        <h6 class="mb-1 fw-bold text-dark">{{ $m->nama_makanan }}</h6>
                                                        <p class="text-muted small mb-2 line-clamp-2" style="font-size: 0.75rem;">{{ $m->deskripsi }}</p>
                                                    </div>
                                                    <span class="badge {{ $m->stok > 0 ? 'bg-light text-success' : 'bg-light text-danger' }} border-0 px-2 py-1" style="font-size: 0.65rem;">
                                                        {{ $m->stok > 0 ? $m->stok . ' tersedia' : 'Habis' }}
                                                    </span>
                                                </div>
                                                <div class="d-flex justify-content-between align-items-center mt-auto">
                                                    <span class="fw-bold text-primary fs-5">Rp{{ number_format($m->harga, 0, ',', '.') }}</span>
                                                    <button class="btn btn-gradient-primary btn-sm rounded-pill px-3" 
                                                            {{ $m->stok <= 0 ? 'disabled' : '' }}
                                                            onclick="addToCart({{ $m->id }}, '{{ $m->nama_makanan }}', {{ $m->harga }}, {{ $v->id }}, {{ $m->stok }})">
                                                        <i class="mdi mdi-plus me-1"></i> Tambah
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Improved Cart Area -->
    <div class="col-md-4" id="cart-area" style="display: none;">
        <div class="card sticky-top sticky-cart shadow-lg border-0">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="card-title mb-0 fw-bold">
                        <i class="mdi mdi-basket-outline me-2 text-primary"></i> Pesanan Anda
                    </h4>
                    <span id="items-count-badge" class="badge bg-primary rounded-pill">0 Item</span>
                </div>
                
                <div class="form-group mb-4">
                    <label class="small fw-bold text-dark mb-2">Pilih Nama Anda <span class="text-danger">*</span></label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-white border-end-0 text-primary"><i class="mdi mdi-account"></i></span>
                        <input type="text" id="customer_name" class="form-control border-start-0 ps-0" placeholder="Contoh: Budi Santoso">
                    </div>
                </div>

                <div id="cart-items" class="pr-1 mb-4" style="max-height: 250px; overflow-y: auto;">
                    <!-- Items injected here by JS -->
                </div>

                <div class="form-group mb-4">
                    <label class="small fw-bold text-dark mb-2">Catatan Tambahan</label>
                    <textarea id="order_notes" class="form-control form-control-sm rounded-3" rows="2" placeholder="Gak pake pedes, ya..."></textarea>
                </div>

                <div class="bg-light rounded-4 p-3 mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted small">Subtotal</span>
                        <span class="fw-bold" id="cart-subtotal">Rp 0</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fw-bold text-dark">Total Pembayaran</span>
                        <span class="fw-bold text-primary fs-4" id="cart-total">Rp 0</span>
                    </div>
                </div>
                
                <button class="btn btn-gradient-primary w-100 py-3 rounded-pill fw-bold shadow-sm" id="btn-checkout" onclick="checkout()">
                    <i class="mdi mdi-credit-card-outline me-2"></i> BAYAR SEKARANG
                </button>
                <p class="text-center x-small text-muted mt-3 mb-0">
                    <i class="mdi mdi-shield-check-outline me-1"></i> Pembayaran aman via Midtrans
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Modal Status -->
<div class="modal fade" id="statusModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-center p-4">
            <div class="modal-body">
                <div id="payment-pending">
                    <div class="spinner-border text-primary mb-3" style="width: 3rem; height: 3rem;" role="status"></div>
                    <h4>Menunggu Pembayaran...</h4>
                    <p class="text-muted">Silakan selesaikan pembayaran di jendela Midtrans.</p>
                    <div class="alert alert-warning border-0 small mt-4">
                        <i class="mdi mdi-information-outline me-2"></i>
                        Pake Simulator? <a href="https://simulator.sandbox.midtrans.com/" target="_blank" class="fw-bold text-dark">Simulator Midtrans</a>
                    </div>
                </div>
                <div id="payment-success" style="display: none;">
                    <i class="mdi mdi-check-circle text-success" style="font-size: 80px;"></i>
                    <h4 class="mt-3">Pembayaran Berhasil!</h4>
                    <p>Pesanan Anda sedang diproses oleh vendor.</p>
                    <div class="alert alert-info mt-3">
                        Status Pesanan: <strong id="order-status-label">Cooking</strong>
                    </div>
                    <p class="small text-muted">Halaman akan otomatis update saat pesanan siap.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
<script>
    let cart = JSON.parse(localStorage.getItem('kantin_cart')) || [];
    let currentVendorId = localStorage.getItem('kantin_vendor_id') || null;
    let currentVendorName = localStorage.getItem('kantin_vendor_name') || '';
    let pollingInterval = null;
    let currentOrderId = null;

    // Load initial state from localstorage
    $(document).ready(function() {
        if (currentVendorId) {
            selectVendor(currentVendorId, currentVendorName, true);
        }
        updateCartUI();
    });

    function selectVendor(id, name, isInit = false) {
        if (currentVendorId && currentVendorId != id && cart.length > 0 && !isInit) {
            Swal.fire({
                title: 'Ganti Warung?',
                text: "Keranjang Anda akan dihapus jika ganti ke warung lain.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#b66dff',
                cancelButtonColor: '#fe7c96',
                confirmButtonText: 'Ya, Ganti',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    currentVendorId = id;
                    currentVendorName = name;
                    cart = [];
                    saveCart();
                    proceedSelection(id, name);
                }
            });
        } else {
            currentVendorId = id;
            currentVendorName = name;
            saveCart();
            proceedSelection(id, name);
        }
    }

    function proceedSelection(id, name) {
        $('#selected-vendor-name').text(name);
        $('.vendor-menu').hide();
        $('#vendor-' + id + '-menu').show();

        $('#menu-area, #cart-area').fadeIn();
        $('#vendor-selection-area').hide();
    }



    function resetStep() {
        if (cart.length > 0) {
            Swal.fire({
                title: 'Keluar?',
                text: "Keranjang Anda tidak akan hilang, tapi Anda harus kembali ke warung ini untuk melihatnya.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Cari Lain',
                cancelButtonText: 'Tetap Disini'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#menu-area, #cart-area').hide();
                    $('#vendor-selection-area').fadeIn();
                }
            });
        } else {
            $('#menu-area, #cart-area').hide();
            $('#vendor-selection-area').fadeIn();
        }
    }

    function addToCart(id, name, price, vendorId, maxStock) {
        if (currentVendorId && currentVendorId != vendorId) {
            return selectVendor(vendorId, 'Warung Baru');
        }

        let exists = cart.find(i => i.id === id);
        if (exists) {
            if (exists.quantity >= maxStock) {
                return Swal.fire('Stok Habis', 'Maksimal pembelian untuk menu ini: ' + maxStock, 'error');
            }
            exists.quantity++;
        } else {
            cart.push({ id, name, price, quantity: 1, maxStock: maxStock });
        }
        updateCartUI();
        saveCart();
    }

    function saveCart() {
        localStorage.setItem('kantin_cart', JSON.stringify(cart));
        localStorage.setItem('kantin_vendor_id', currentVendorId);
        localStorage.setItem('kantin_vendor_name', currentVendorName);
    }

    function updateCartUI() {
        let html = '';
        let total = 0;
        let itemCount = 0;
        
        cart.forEach((item, index) => {
            let subtotal = item.price * item.quantity;
            total += subtotal;
            itemCount += item.quantity;
            
            html += `
                <div class="cart-item-row">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <div style="flex-grow: 1;">
                            <div class="fw-bold text-dark" style="font-size: 0.9rem;">${item.name}</div>
                            <div class="small text-muted fw-bold">Rp ${item.price.toLocaleString()}</div>
                        </div>
                        <div class="d-flex align-items-center bg-white rounded-3 shadow-sm p-1">
                            <button class="btn btn-qty btn-light text-danger" onclick="removeFromCart(${index})">
                                <i class="mdi mdi-minus"></i>
                            </button>
                            <span class="mx-3 fw-bold text-dark" style="min-width: 20px; text-align: center;">${item.quantity}</span>
                            <button class="btn btn-qty btn-light text-primary" onclick="addToCart(${item.id}, '${item.name}', ${item.price}, ${currentVendorId}, ${item.maxStock})">
                                <i class="mdi mdi-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `;
        });
        
        $('#cart-items').html(html || `
            <div class="text-center py-5 text-muted">
                <div class="mb-3">
                    <i class="mdi mdi-cart-off" style="font-size: 3rem; opacity: 0.2;"></i>
                </div>
                <p class="small mb-0">Keranjang Anda masih kosong</p>
                <p class="x-small">Mulai pilih menu lezat di sebelah kiri!</p>
            </div>
        `);
        
        $('#items-count-badge').text(itemCount + ' Item');
        $('#cart-subtotal').text('Rp ' + total.toLocaleString());
        $('#cart-total').text('Rp ' + total.toLocaleString());
        
        // Simple scale animation for totals
        if (total > 0) {
            $('#cart-total').css('transform', 'scale(1.1)').css('transition', '0.2s');
            setTimeout(() => $('#cart-total').css('transform', 'scale(1)'), 2000);
        }
    }

    function removeFromCart(index) {
        if (cart[index].quantity > 1) {
            cart[index].quantity--;
        } else {
            cart.splice(index, 1);
        }
        updateCartUI();
        saveCart();
    }

    function checkout() {
        let customerName = $('#customer_name').val();
        let notes = $('#order_notes').val();

        if (!customerName) {
            return Swal.fire('Nama Diperlukan', 'Harap isi nama pemesan sebelum checkout', 'warning').then(() => {
                $('#customer_name').focus();
            });
        }

        if (cart.length === 0) return Swal.fire('Oops', 'Keranjang masih kosong!', 'warning');

        btnLoading('#btn-checkout');
        
        $.ajax({
            url: "{{ route('kantin.checkout') }}",
            method: 'POST',
            data: {
                _token: "{{ csrf_token() }}",
                vendor_id: currentVendorId,
                nama_pelanggan: customerName,
                catatan: notes,
                items: cart
            },
            success: function(res) {
                snap.pay(res.snap_token, {
                    onSuccess: function(result) {
                        $('#btn-checkout').html('BAYAR SEKARANG').removeClass('disabled').prop('disabled', false);
                        localStorage.removeItem('kantin_cart');
                        localStorage.removeItem('kantin_vendor_id');
                        localStorage.removeItem('kantin_vendor_name');
                        startPolling(res.order_id);
                    },
                    onPending: function(result) {
                        $('#btn-checkout').html('BAYAR SEKARANG').removeClass('disabled').prop('disabled', false);
                        localStorage.removeItem('kantin_cart');
                        localStorage.removeItem('kantin_vendor_id');
                        localStorage.removeItem('kantin_vendor_name');
                        startPolling(res.order_id);
                    },
                    onError: function(result) {
                        Swal.fire('Error', 'Gagal memproses pembayaran', 'error');
                        $('#btn-checkout').html('BAYAR SEKARANG').removeClass('disabled').prop('disabled', false);
                    }
                });
            },
            error: function(xhr) {
                if(xhr.status == 422) {
                    Swal.fire('Stok Kurang', xhr.responseJSON.message, 'error');
                } else {
                    Swal.fire('Error', 'Gagal menghubungi server', 'error');
                }
                $('#btn-checkout').html('BAYAR SEKARANG').removeClass('disabled').prop('disabled', false);
            }
        });
    }

    function startPolling(orderId) {
        currentOrderId = orderId;
        $('#statusModal').modal('show');
        
        if (pollingInterval) clearInterval(pollingInterval);
        
        pollingInterval = setInterval(() => {
            $.get(`/kantin/status/${orderId}`, function(res) {
                if (res.status != 'pending') {
                    $('#payment-pending').hide();
                    $('#payment-success').fadeIn();
                    $('#order-status-label').text(res.status_label);
                    
                    if (res.status == 'paid' || res.status == 'ready' || res.status == 'cooking') {
                        clearInterval(pollingInterval);
                        setTimeout(() => {
                            window.location.href = `/kantin/success/${orderId}`;
                        }, 2000);
                    }
                }
            });
        }, 5000);
    }
</script>
@endsection
