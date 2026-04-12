@extends('layouts.app')

@push('style-page')
<style>
    :root {
        --purple-brand: #b66dff;
        --purple-light: rgba(182, 109, 255, 0.1);
        --glass-bg: rgba(255, 255, 255, 0.9);
        --card-shadow: 0 10px 30px rgba(0,0,0,0.08);
    }

    /* Layout Containers */
    .order-container {
        display: flex;
        gap: 25px;
        position: relative;
    }

    /* Adaptive Vendor Navigation */
    .vendor-nav {
        background: var(--glass-bg);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        border: 1px solid rgba(0,0,0,0.05);
        z-index: 100;
    }

    /* Desktop Sidebar */
    @media (min-width: 992px) {
        .vendor-nav {
            width: 260px;
            position: sticky;
            top: 80px;
            height: fit-content;
            padding: 20px;
        }
        .main-order-content {
            flex-grow: 1;
        }
        .mobile-vendor-nav {
            display: none;
        }
    }

    /* Mobile Header Scroll */
    @media (max-width: 991px) {
        .order-container {
            flex-direction: column;
            gap: 15px;
        }
        .vendor-nav {
            display: none; /* Hide desktop sidebar */
        }
        .mobile-vendor-nav {
            position: sticky;
            top: 70px;
            z-index: 1000;
            background: white;
            padding: 10px 0;
            margin: -10px -10px 10px -10px;
            border-bottom: 1px solid #eee;
            overflow-x: auto;
            white-space: nowrap;
            display: flex;
            gap: 10px;
            scrollbar-width: none;
            -ms-overflow-style: none;
            padding-left: 20px;
            padding-right: 20px;
        }
        .mobile-vendor-nav::-webkit-scrollbar {
            display: none;
        }
        .mobile-vendor-item {
            display: inline-block;
            padding: 8px 18px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.85rem;
            background: #f8f9fa;
            color: #666;
            border: 1px solid #eee;
            transition: all 0.3s ease;
        }
        .mobile-vendor-item.active {
            background: var(--purple-brand);
            color: white;
            border-color: var(--purple-brand);
            box-shadow: 0 4px 10px rgba(182, 109, 255, 0.3);
        }
    }

    /* Sidebar Items */
    .vendor-link {
        display: flex;
        align-items: center;
        padding: 12px 15px;
        border-radius: 15px;
        color: #555;
        text-decoration: none;
        margin-bottom: 8px;
        transition: all 0.3s ease;
        font-weight: 600;
        border: 1px solid transparent;
    }
    .vendor-link i {
        font-size: 1.2rem;
        margin-right: 12px;
        opacity: 0.7;
    }
    .vendor-link:hover {
        background: var(--purple-light);
        color: var(--purple-brand);
        transform: translateX(5px);
    }
    .vendor-link.active {
        background: var(--purple-brand);
        color: white;
        box-shadow: 0 4px 15px rgba(182, 109, 255, 0.2);
    }
    .vendor-link.active i {
        opacity: 1;
    }

    /* Vendor Sections */
    .vendor-section {
        margin-bottom: 50px;
        scroll-margin-top: 130px; /* offset for sticky headers */
    }
    .vendor-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid #f0f0f0;
    }
    .vendor-name {
        font-weight: 800;
        font-size: 1.5rem;
        color: #333;
    }

    /* Menu Cards */
    .menu-card {
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        border: 1px solid #f1f1f1;
        border-radius: 20px;
        background: white;
        overflow: hidden;
    }
    .menu-card:hover {
        transform: translateY(-8px);
        box-shadow: var(--card-shadow);
        border-color: var(--purple-light);
    }
    
    .item-img-container {
        width: 100px;
        height: 100px;
        border-radius: 15px;
        overflow: hidden;
        flex-shrink: 0;
        background: #f8f9fa;
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

    /* Vendor Badge on Card */
    .vendor-badge-mini {
        font-size: 0.65rem;
        background: var(--purple-light);
        color: var(--purple-brand);
        padding: 2px 8px;
        border-radius: 5px;
        font-weight: 800;
        text-transform: uppercase;
        display: inline-block;
        margin-bottom: 5px;
    }

    /* Cart Sidebar */
    .sticky-cart {
        width: 350px;
        position: sticky;
        top: 80px;
        height: fit-content;
    }
    @media (max-width: 1200px) {
        .sticky-cart {
            width: 300px;
        }
    }
    @media (max-width: 991px) {
        .sticky-cart {
            width: 100%;
            position: relative;
            top: 0;
            margin-top: 30px;
        }
    }

    .btn-qty {
        width: 32px;
        height: 32px;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        font-weight: bold;
        transition: all 0.2s;
    }
    .btn-qty:active { transform: scale(0.9); }

    /* Custom Scrollbar for Vendor Nav Desktop */
    .vendor-nav-list {
        max-height: calc(100vh - 250px);
        overflow-y: auto;
        padding-right: 5px;
    }
    .vendor-nav-list::-webkit-scrollbar { width: 4px; }
    .vendor-nav-list::-webkit-scrollbar-thumb { background: #eee; border-radius: 10px; }
</style>
@endpush

@section('content')
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

<!-- Mobile Vendor Navigation (Visible only on Mobile) -->
<div class="mobile-vendor-nav">
    @foreach($vendors as $v)
    <a href="#vendor-{{ $v->id }}" class="mobile-vendor-item" id="mobile-nav-{{ $v->id }}">
        {{ $v->nama_warung }}
    </a>
    @endforeach
</div>

<div class="order-container">
    <!-- Desktop Vendor Navigation (Visible only on Desktop) -->
    <aside class="vendor-nav">
        <h6 class="text-uppercase fw-bold mb-4 small text-muted">Daftar Warung</h6>
        <div class="vendor-nav-list">
            @foreach($vendors as $v)
            <a href="#vendor-{{ $v->id }}" class="vendor-link" id="nav-{{ $v->id }}">
                <i class="mdi mdi-store-outline"></i>
                <span class="text-truncate">{{ $v->nama_warung }}</span>
            </a>
            @endforeach
        </div>
    </aside>

    <!-- Main Menu Area -->
    <main class="main-order-content">
        @foreach($vendors as $v)
        <section class="vendor-section" id="vendor-{{ $v->id }}">
            <div class="vendor-header">
                <div>
                    <h2 class="vendor-name mb-0">{{ $v->nama_warung }}</h2>
                    <p class="text-muted small mb-0">{{ $v->menu->count() }} Pilihan Menu • <i class="mdi mdi-star text-warning"></i> 4.8</p>
                </div>
                <span class="badge bg-light text-primary rounded-pill border px-3">Buka</span>
            </div>

            <div class="row">
                @foreach($v->menu as $m)
                <div class="col-xl-6 col-md-12 mb-4">
                    <div class="card menu-card h-100">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center">
                                <div class="item-img-container me-3 border">
                                    <img src="{{ $m->foto ? asset('storage/' . $m->foto) : 'https://placehold.co/100x100?text=' . urlencode($m->nama_makanan) }}" alt="{{ $m->nama_makanan }}">
                                </div>
                                <div class="flex-grow-1">
                                    <div class="vendor-badge-mini">{{ $v->nama_warung }}</div>
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="mb-1">
                                            <h6 class="mb-1 fw-bold text-dark">{{ $m->nama_makanan }}</h6>
                                            <p class="text-muted small mb-0 line-clamp-2" style="font-size: 0.75rem; height: 32px; overflow: hidden;">{{ $m->deskripsi }}</p>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mt-2">
                                        <div>
                                            <span class="fw-bold text-primary fs-5">Rp{{ number_format($m->harga, 0, ',', '.') }}</span>
                                            <div class="x-small {{ $m->stok > 0 ? 'text-success' : 'text-danger' }}">
                                                {{ $m->stok > 0 ? $m->stok . ' tersedia' : 'Habis' }}
                                            </div>
                                        </div>
                                        <button class="btn btn-gradient-primary btn-sm rounded-pill px-3 shadow-sm" 
                                                {{ $m->stok <= 0 ? 'disabled' : '' }}
                                                onclick="addToCart({{ $m->id }}, '{{ $m->nama_makanan }}', {{ $m->harga }}, {{ $v->id }}, '{{ $v->nama_warung }}', {{ $m->stok }})">
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
        </section>
        @endforeach
    </main>

    <!-- Right Sidebar Cart -->
    <aside class="sticky-cart">
        <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
            <div class="card-body p-4 bg-white">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="card-title mb-0 fw-bold">
                        <i class="mdi mdi-basket-outline me-2 text-primary"></i> Pesanan Anda
                    </h4>
                    <span id="items-count-badge" class="badge bg-primary rounded-pill">0 Item</span>
                </div>
                
                <div class="form-group mb-4">
                    <label class="small fw-bold text-dark mb-2">Pilih Nama Anda <span class="text-danger">*</span></label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light border-end-0 text-primary"><i class="mdi mdi-account"></i></span>
                        <input type="text" id="customer_name" class="form-control bg-light border-start-0 ps-0" placeholder="Contoh: Budi Santoso">
                    </div>
                </div>

                <div id="cart-items" class="pr-1 mb-4" style="max-height: 350px; overflow-y: auto;">
                    <!-- Items JS injected -->
                </div>

                <div class="form-group mb-4">
                    <label class="small fw-bold text-dark mb-2">Catatan Tambahan</label>
                    <textarea id="order_notes" class="form-control form-control-sm rounded-3 border-light bg-light" rows="2" placeholder="Gak pake pedes, ya..."></textarea>
                </div>

                <div class="bg-light rounded-4 p-3 mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted small">Subtotal</span>
                        <span class="fw-bold" id="cart-subtotal">Rp 0</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fw-bold text-dark">Total</span>
                        <span class="fw-bold text-primary fs-4" id="cart-total">Rp 0</span>
                    </div>
                </div>
                
                <button class="btn btn-gradient-primary w-100 py-3 rounded-pill fw-bold shadow-sm" id="btn-checkout" onclick="checkout()">
                    <i class="mdi mdi-credit-card-outline me-2"></i> BAYAR SEKARANG
                </button>
            </div>
        </div>
        <p class="text-center x-small text-muted mt-3">
            <i class="mdi mdi-shield-check-outline me-1"></i> Pembayaran aman via Midtrans
        </p>
    </aside>
</div>

<!-- Warning Modal Vendor Switch -->
<div class="modal fade" id="vendorWarningModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0">
            <div class="modal-body p-5 text-center">
                <div class="mb-4">
                    <i class="mdi mdi-store-alert text-warning" style="font-size: 70px;"></i>
                </div>
                <h3 class="fw-bold">Ganti Warung?</h3>
                <p class="text-muted">Oops! Kamu baru bisa memesan dari <strong>satu warung</strong> dalam satu transaksi. Ingin menghapus keranjang saat ini dan mulai pesanan baru di warung ini?</p>
                <div class="d-grid gap-2 mt-4">
                    <button type="button" class="btn btn-gradient-primary btn-lg rounded-pill" id="confirm-vendor-switch">
                        Ya, Hapus & Ganti
                    </button>
                    <button type="button" class="btn btn-link text-muted" data-bs-dismiss="modal">
                        Batal, Tetap di Warung Lama
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Status Midtrans (Existing) -->
<div class="modal fade" id="statusModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-center p-4 rounded-4 border-0">
            <div class="modal-body">
                <div id="payment-pending">
                    <div class="spinner-border text-primary mb-3" style="width: 3rem; height: 3rem;" role="status"></div>
                    <h4>Menunggu Pembayaran...</h4>
                    <p class="text-muted">Jendela pembayaran telah dibuka. Segera selesaikan pembayaranmu agar pesanan dapat diproses.</p>
                </div>
                <div id="payment-success" style="display: none;">
                    <i class="mdi mdi-check-circle text-success" style="font-size: 80px;"></i>
                    <h4 class="mt-3">Pembayaran Berhasil!</h4>
                    <p>Pesanan Anda sedang diproses oleh vendor.</p>
                    <div class="alert alert-info mt-3">
                        Status Pesanan: <strong id="order-status-label">Cooking</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
@endsection

@push('script-page')
<script>
    let cart = JSON.parse(localStorage.getItem('kantin_cart')) || [];
    let currentVendorId = localStorage.getItem('kantin_vendor_id') || null;
    let currentVendorName = localStorage.getItem('kantin_vendor_name') || '';
    let pendingSwitch = null;

    $(document).ready(function() {
        updateCartUI();
        initScrollSpy();
        
        // Modal Confirm Event
        $('#confirm-vendor-switch').on('click', function() {
            if (pendingSwitch) {
                cart = [];
                currentVendorId = pendingSwitch.vendorId;
                currentVendorName = pendingSwitch.vendorName;
                saveCart();
                addToCart(pendingSwitch.id, pendingSwitch.name, pendingSwitch.price, pendingSwitch.vendorId, pendingSwitch.vendorName, pendingSwitch.maxStock);
                $('#vendorWarningModal').modal('hide');
                pendingSwitch = null;
            }
        });

        // Smooth Scrolling for Nav Links
        $('a[href^="#"]').on('click', function(e) {
            e.preventDefault();
            const target = $(this.getAttribute('href'));
            if (target.length) {
                $('html, body').stop().animate({
                    scrollTop: target.offset().top - 120
                }, 500);
            }
        });
    });

    function initScrollSpy() {
        const sections = document.querySelectorAll('.vendor-section');
        const navLinks = document.querySelectorAll('.vendor-link');
        const mobileLinks = document.querySelectorAll('.mobile-vendor-item');

        const observerOptions = {
            root: null,
            rootMargin: '-20% 0px -70% 0px', // adjustment for centering
            threshold: 0
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const id = entry.target.id.split('-')[1];
                    
                    // Desktop active class
                    navLinks.forEach(link => {
                        link.classList.toggle('active', link.id === `nav-${id}`);
                    });
                    
                    // Mobile active class
                    mobileLinks.forEach(link => {
                        link.classList.toggle('active', link.id === `mobile-nav-${id}`);
                        if (link.classList.contains('active')) {
                            // Scroll mobile nav into view
                            link.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
                        }
                    });
                }
            });
        }, observerOptions);

        sections.forEach(section => observer.observe(section));
    }

    function addToCart(id, name, price, vendorId, vendorName, maxStock) {
        // Vendor Validation
        if (currentVendorId && currentVendorId != vendorId && cart.length > 0) {
            pendingSwitch = { id, name, price, vendorId, vendorName, maxStock };
            $('#vendorWarningModal').modal('show');
            return;
        }

        // Proceed if same vendor or empty cart
        currentVendorId = vendorId;
        currentVendorName = vendorName;

        let exists = cart.find(i => i.id === id);
        if (exists) {
            if (exists.quantity >= maxStock) {
                return Swal.fire('Stok Habis', 'Maksimal pembelian dicapai', 'error');
            }
            exists.quantity++;
        } else {
            cart.push({ id, name, price, quantity: 1, maxStock: maxStock });
        }
        
        updateCartUI();
        saveCart();
        
        // Pulse effect on cart icon
        $('.mdi-basket-outline').addClass('pulse-animation');
        setTimeout(() => $('.mdi-basket-outline').removeClass('pulse-animation'), 1000);
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
                <div class="cart-item-row p-3 border rounded-3 mb-2 bg-white shadow-sm border-light">
                    <div class="d-flex justify-content-between align-items-center">
                        <div style="flex-grow: 1;">
                            <div class="fw-bold text-dark" style="font-size: 0.9rem;">${item.name}</div>
                            <div class="small text-primary fw-bold">Rp ${item.price.toLocaleString()}</div>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <button class="btn btn-qty btn-light border" onclick="removeFromCart(${index})">
                                <i class="mdi mdi-minus text-dark" style="font-size: 10px;"></i>
                            </button>
                            <span class="mx-1 fw-bold text-dark" style="min-width: 15px; text-align: center;">${item.quantity}</span>
                            <button class="btn btn-qty btn-light border" onclick="addToCart(${item.id}, '${item.name}', ${item.price}, ${currentVendorId}, '${currentVendorName}', ${item.maxStock})">
                                <i class="mdi mdi-plus text-primary" style="font-size: 10px;"></i>
                            </button>
                            <button class="btn btn-qty btn-outline-danger border-0 ms-2" onclick="deleteFromCart(${index})">
                                <i class="mdi mdi-trash-can-outline"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `;
        });
        
        $('#cart-items').html(html || `
            <div class="text-center py-5 text-muted bg-light rounded-4">
                <i class="mdi mdi-cart-outline opacity-25" style="font-size: 4rem;"></i>
                <p class="small mt-2 mb-0">Keranjang masih kosong</p>
                <p class="x-small">Pilih menu di sebelah kiri ya!</p>
            </div>
        `);
        
        $('#items-count-badge').text(itemCount + ' Menu');
        $('#cart-subtotal').text('Rp ' + total.toLocaleString());
        $('#cart-total').text('Rp ' + total.toLocaleString());
        
        if (itemCount === 0) {
            currentVendorId = null;
            currentVendorName = '';
            saveCart();
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

    function deleteFromCart(index) {
        cart.splice(index, 1);
        updateCartUI();
        saveCart();
    }

    function checkout() {
        let customerName = $('#customer_name').val();
        let notes = $('#order_notes').val();

        if (!customerName) {
            return Swal.fire('Oops!', 'Tolong isi nama pemesan dulu ya.', 'warning').then(() => $('#customer_name').focus());
        }

        if (cart.length === 0) return Swal.fire('Keranjang Kosong', 'Pilih menu favoritmu dulu.', 'info');

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
                    onSuccess: function() { resetCartAndListen(res.order_id); },
                    onPending: function() { resetCartAndListen(res.order_id); },
                    onError: function() { 
                        Swal.fire('Error', 'Pembayaran gagal', 'error');
                        restoreCheckoutBtn();
                    },
                    onClose: function() { restoreCheckoutBtn(); }
                });
            },
            error: function(xhr) {
                Swal.fire('Error', xhr.responseJSON.message || 'Gagal checkout', 'error');
                restoreCheckoutBtn();
            }
        });
    }

    function restoreCheckoutBtn() {
        $('#btn-checkout').html('<i class="mdi mdi-credit-card-outline me-2"></i> BAYAR SEKARANG').removeClass('disabled').prop('disabled', false);
    }

    function resetCartAndListen(orderId) {
        localStorage.removeItem('kantin_cart');
        localStorage.removeItem('kantin_vendor_id');
        localStorage.removeItem('kantin_vendor_name');
        cart = [];
        updateCartUI();
        startPolling(orderId);
    }

    function startPolling(orderId) {
        $('#statusModal').modal('show');
        setInterval(() => {
            $.get(`/kantin/status/${orderId}`, function(res) {
                if (res.status != 'pending') {
                    $('#payment-pending').hide();
                    $('#payment-success').fadeIn();
                    $('#order-status-label').text(res.status_label);
                    setTimeout(() => { window.location.href = `/kantin/success/${orderId}`; }, 2000);
                }
            });
        }, 5000);
    }
</script>
@endpush
