@extends('layouts.app')

@section('content')
<style>
    .menu-card {
        transition: transform 0.3s;
        border: none;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
    }
    .menu-card:hover {
        transform: translateY(-5px);
    }
    .img-container {
        width: 80px;
        height: 80px;
        overflow: hidden;
        border-radius: 12px;
    }
    .img-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .badge-stock {
        font-size: 0.7rem;
        padding: 0.3rem 0.6rem;
    }
    .vendor-badge {
        font-size: 0.8rem;
        background: rgba(182, 109, 255, 0.1);
        color: #b66dff;
        padding: 4px 12px;
        border-radius: 20px;
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
            <li class="breadcrumb-item active" aria-current="page">
                <span></span>Order <i class="mdi mdi-check icon-sm text-primary align-middle"></i>
            </li>
        </ul>
    </nav>
</div>

<div class="row" id="order-app">
    <!-- Vendor Selection -->
    <div class="col-md-4" id="vendor-selection-area">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Pilih Warung</h4>
                <p class="text-muted small">Pilih salah satu warung untuk mulai memesan</p>
                <div class="list-group">
                    @foreach($vendors as $v)
                    <button type="button" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center p-3 border-0 mb-2 rounded shadow-sm" onclick="selectVendor({{ $v->id }}, '{{ $v->nama_warung }}')">
                        <div class="d-flex align-items-center">
                            <div class="bg-light p-2 rounded me-3">
                                <i class="mdi mdi-store text-primary"></i>
                            </div>
                            <span class="fw-bold">{{ $v->nama_warung }}</span>
                        </div>
                        <span class="badge bg-gradient-primary rounded-pill text-white border-0">{{ $v->menu->count() }} Menu</span>
                    </button>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Menu Selection -->
    <div class="col-md-8" id="menu-area" style="display: none;">
        <div class="card">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <h4 class="card-title mb-0">Menu: <span id="selected-vendor-name" class="text-primary fw-bold"></span></h4>
                <button class="btn btn-sm btn-outline-secondary" onclick="resetStep()">
                    <i class="mdi mdi-swap-horizontal me-1"></i> Ganti Warung
                </button>
            </div>
            <div class="card-body">


                <div class="row" id="menu-list">
                    @foreach($vendors as $v)
                    <div class="vendor-menu col-12" id="vendor-{{ $v->id }}-menu" style="display: none;">
                        <div class="row">
                            @foreach($v->menu as $m)
                            <div class="col-md-6 mb-4 menu-item">
                                <div class="card menu-card border h-100">
                                    <div class="card-body p-3">
                                        <div class="d-flex align-items-start">
                                            <div class="img-container me-3 border">
                                                <img src="{{ $m->foto ? asset('storage/' . $m->foto) : 'https://placehold.co/100x100?text=' . urlencode($m->nama_makanan) }}" alt="{{ $m->nama_makanan }}">
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="d-flex justify-content-between">
                                                    <h5 class="mb-1 fw-bold">{{ $m->nama_makanan }}</h5>
                                                    <span class="badge {{ $m->stok > 0 ? 'bg-light text-success' : 'bg-light text-danger' }} badge-stock">
                                                        {{ $m->stok > 0 ? 'Stok: ' . $m->stok : 'Habis' }}
                                                    </span>
                                                </div>
                                                <p class="text-muted small mb-2 text-truncate-2" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; height: 32px;">{{ $m->deskripsi }}</p>
                                                <div class="d-flex justify-content-between align-items-center mt-2">
                                                    <span class="fw-bold text-dark fs-5">Rp {{ number_format($m->harga, 0, ',', '.') }}</span>
                                                    <button class="btn btn-gradient-primary btn-sm rounded-pill px-3" 
                                                            {{ $m->stok <= 0 ? 'disabled' : '' }}
                                                            onclick="addToCart({{ $m->id }}, '{{ $m->nama_makanan }}', {{ $m->harga }}, {{ $v->id }}, {{ $m->stok }})">
                                                        <i class="mdi mdi-plus-circle-outline me-1"></i> Tambah
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

    <!-- Cart Area -->
    <div class="col-md-4" id="cart-area" style="display: none;">
        <div class="card sticky-top" style="top: 20px; z-index: 100;">
            <div class="card-body">
                <h4 class="card-title d-flex align-items-center">
                    <i class="mdi mdi-cart-outline me-2 text-primary"></i> Keranjang
                </h4>
                
                <!-- Customer Name -->
                <div class="form-group mt-3">
                    <label class="small fw-bold">Nama Pemesan <span class="text-danger">*</span></label>
                    <input type="text" id="customer_name" class="form-control form-control-sm border-primary" placeholder="Siapa yang pesan?">
                </div>

                <div id="cart-items" class="mt-4 mb-3" style="max-height: 300px; overflow-y: auto;">
                    <!-- Cart items -->
                </div>

                <div class="form-group mb-3">
                    <label class="small fw-bold">Catatan (Opsional)</label>
                    <textarea id="order_notes" class="form-control form-control-sm" rows="2" placeholder="Contoh: Gak pake sambel ya"></textarea>
                </div>

                <hr>
                <div class="d-flex justify-content-between mb-3">
                    <span class="fw-bold">Total Pembayaran</span>
                    <span class="fw-bold text-primary fs-4" id="cart-total">Rp 0</span>
                </div>
                <button class="btn btn-gradient-primary w-100 py-3 fw-bold" id="btn-checkout" onclick="checkout()">
                    <i class="mdi mdi-credit-card me-2"></i> BAYAR SEKARANG
                </button>
                <p class="text-center small text-muted mt-2 mb-0">Klik bayar via Midtrans</p>
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
        cart.forEach((item, index) => {
            let subtotal = item.price * item.quantity;
            total += subtotal;
            html += `
                <div class="d-flex justify-content-between align-items-center mb-3 bg-light p-2 rounded">
                    <div style="flex-grow: 1;">
                        <div class="fw-bold" style="font-size: 0.9rem;">${item.name}</div>
                        <div class="small text-muted text-primary fw-bold">Rp ${item.price.toLocaleString()}</div>
                    </div>
                    <div class="d-flex align-items-center">
                        <button class="btn btn-sm btn-inverse-danger px-2 py-1" onclick="removeFromCart(${index})">-</button>
                        <span class="mx-2 fw-bold">${item.quantity}</span>
                        <button class="btn btn-sm btn-inverse-primary px-2 py-1" onclick="addToCart(${item.id}, '${item.name}', ${item.price}, ${currentVendorId}, ${item.maxStock})">+</button>
                    </div>
                </div>
            `;
        });
        $('#cart-items').html(html || '<div class="text-center py-4 text-muted"><i class="mdi mdi-cart-off fs-1"></i><p class="mt-2 mb-0">Keranjang Kosong</p></div>');
        $('#cart-total').text('Rp ' + total.toLocaleString());
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
