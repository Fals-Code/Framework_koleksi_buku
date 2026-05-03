@extends('layouts.app')

@push('style-page')
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;800&display=swap" rel="stylesheet">
<style>
    :root {
        --kantin-primary: #ff6b35;
        --kantin-secondary: #2575fc;
        --kantin-bg: #f8f9fe;
        --outfit: 'Outfit', sans-serif;
    }

    body { font-family: var(--outfit); }

    /* Modern Hero Section - Safe Version */
    .hero-premium {
        background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
        border-radius: 25px;
        padding: 50px 40px;
        color: white;
        margin-bottom: 30px;
        position: relative;
        box-shadow: 0 10px 30px rgba(106, 17, 203, 0.1);
    }
    .hero-img {
        width: 180px;
        filter: drop-shadow(0 10px 20px rgba(0,0,0,0.2));
    }

    /* Category Chips - Safe Sticky */
    .vendor-nav-container {
        position: sticky;
        top: 70px;
        z-index: 100;
        background: rgba(248, 249, 254, 0.9);
        backdrop-filter: blur(10px);
        padding: 15px 0;
        margin-bottom: 20px;
    }
    .vendor-chips {
        display: flex;
        gap: 10px;
        overflow-x: auto;
        scrollbar-width: none;
    }
    .vendor-chips::-webkit-scrollbar { display: none; }
    
    .chip {
        white-space: nowrap;
        padding: 10px 20px;
        background: white;
        border-radius: 50px;
        font-weight: 600;
        color: #666;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        border: 1.5px solid transparent;
        text-decoration: none !important;
        transition: 0.3s;
    }
    .chip.active {
        background: var(--kantin-primary);
        color: white;
        box-shadow: 0 5px 15px rgba(255, 107, 53, 0.3);
    }

    /* Menu Cards Premium */
    .menu-card-v3 {
        background: white;
        border-radius: 25px;
        border: none;
        overflow: hidden;
        transition: 0.3s;
        height: 100%;
        display: flex;
        flex-direction: column;
        box-shadow: 0 5px 15px rgba(0,0,0,0.02);
    }
    .menu-card-v3:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.08);
    }
    .img-box {
        position: relative;
        height: 200px;
    }
    .img-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .badge-price {
        position: absolute;
        bottom: 15px;
        right: 15px;
        background: rgba(255, 255, 255, 0.9);
        padding: 5px 15px;
        border-radius: 50px;
        font-weight: 800;
        color: var(--kantin-primary);
    }

    /* Glass Cart Sidebar - Safe Sticky */
    .cart-glass-v3 {
        background: rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(15px);
        border-radius: 30px;
        padding: 30px;
        border: 1px solid rgba(255, 255, 255, 0.5);
        position: sticky;
        top: 90px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.03);
    }

    .btn-add {
        background: #fff3f0;
        color: var(--kantin-primary);
        border: none;
        border-radius: 12px;
        font-weight: 800;
        width: 100%;
        padding: 10px;
        transition: 0.3s;
    }
    .btn-add:hover {
        background: var(--kantin-primary);
        color: white;
    }
</style>
@endpush

@section('content')
<div class="hero-premium">
    <div class="row align-items-center">
        <div class="col-md-8">
            <h1 class="display-4 fw-bold">Kantin Vokasi</h1>
            <p class="fs-5 opacity-75">Pesan makanan favoritmu dengan cepat dan mudah.</p>
        </div>
        <div class="col-md-4 text-center d-none d-md-block">
            <img src="https://cdn-icons-png.flaticon.com/512/3075/3075977.png" class="hero-img">
        </div>
    </div>
</div>

<div class="vendor-nav-container">
    <div class="vendor-chips">
        @foreach($vendors as $v)
        <a href="#vendor-{{ $v->id }}" class="chip" id="chip-{{ $v->id }}">
            {{ $v->nama_warung }}
        </a>
        @endforeach
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        @foreach($vendors as $v)
        <section id="vendor-{{ $v->id }}" class="mb-5">
            <h3 class="fw-bold mb-4">{{ $v->nama_warung }}</h3>
            <div class="row g-4">
                @foreach($v->menu as $m)
                <div class="col-md-6">
                    <div class="menu-card-v3">
                        <div class="img-box">
                            <img src="{{ $m->foto ? asset('storage/' . $m->foto) : 'https://placehold.co/500x400?text=' . urlencode($m->nama_makanan) }}">
                            <div class="badge-price">Rp {{ number_format($m->harga, 0, ',', '.') }}</div>
                        </div>
                        <div class="p-4 flex-grow-1 d-flex flex-column">
                            <h5 class="fw-bold mb-2">{{ $m->nama_makanan }}</h5>
                            <p class="small text-muted mb-4">{{ Str::limit($m->deskripsi, 80) }}</p>
                            <div class="mt-auto d-flex justify-content-between align-items-center">
                                <span class="small fw-bold {{ $m->stok > 0 ? 'text-success' : 'text-danger' }}">
                                    ● {{ $m->stok > 0 ? $m->stok . ' Tersedia' : 'Habis' }}
                                </span>
                                <button class="btn btn-add w-auto px-4" 
                                        {{ $m->stok <= 0 ? 'disabled' : '' }}
                                        onclick="addToCartV3({{ $m->id }}, '{{ $m->nama_makanan }}', {{ $m->harga }}, {{ $v->id }}, '{{ $v->nama_warung }}', {{ $m->stok }})">
                                    Tambah
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </section>
        @endforeach
        <div style="height: 100px;"></div>
    </div>

    <div class="col-lg-4">
        <div class="cart-glass-v3">
            <h4 class="fw-bold mb-4">Keranjang</h4>
            
            <div class="mb-3">
                <label class="small fw-bold text-muted mb-2">NAMA KAMU</label>
                <input type="text" id="customer_name" class="form-control border-0 bg-light rounded-3" placeholder="Masukkan nama...">
            </div>

            <div id="cartList" class="mb-4" style="max-height: 300px; overflow-y: auto;">
                <!-- JS Injected -->
            </div>

            <div class="border-top pt-3 mb-4">
                <div class="d-flex justify-content-between">
                    <span class="text-muted">Total Bayar</span>
                    <h3 class="fw-bold text-primary" id="cartTotalV3">Rp 0</h3>
                </div>
            </div>

            <button class="btn btn-primary btn-lg w-100 rounded-pill py-3 fw-bold" onclick="doCheckout()">
                PESAN SEKARANG
            </button>
        </div>
    </div>
</div>

<!-- Modal Ganti Warung -->
<div class="modal fade" id="vendorWarningModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">
            <div class="modal-body p-5 text-center">
                <i class="mdi mdi-alert-circle-outline text-warning mb-4" style="font-size: 60px;"></i>
                <h4 class="fw-bold">Ganti Warung?</h4>
                <p class="text-muted">Hapus isi keranjang saat ini untuk memesan dari warung lain?</p>
                <div class="d-grid gap-2 mt-4">
                    <button class="btn btn-primary rounded-pill py-2" onclick="confirmSwitch()">Ya, Hapus & Ganti</button>
                    <button class="btn btn-light rounded-pill py-2" data-bs-dismiss="modal">Batal</button>
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
    let pendingItem = null;

    $(document).ready(function() {
        renderCartV3();
        initScrollSpyV3();
    });

    function initScrollSpyV3() {
        const sections = document.querySelectorAll('section');
        const chips = document.querySelectorAll('.chip');
        $(window).scroll(function() {
            let current = "";
            sections.forEach(section => {
                if (pageYOffset >= section.offsetTop - 150) {
                    current = section.getAttribute('id');
                }
            });
            chips.forEach(chip => {
                chip.classList.remove('active');
                if (chip.getAttribute('href').substring(1) === current) {
                    chip.classList.add('active');
                }
            });
        });
    }

    function addToCartV3(id, name, price, vendorId, vendorName, maxStock) {
        if (currentVendorId && currentVendorId != vendorId && cart.length > 0) {
            pendingItem = { id, name, price, vendorId, vendorName, maxStock };
            $('#vendorWarningModal').modal('show');
            return;
        }
        currentVendorId = vendorId;
        let item = cart.find(i => i.id === id);
        if (item) {
            if (item.quantity >= maxStock) return alert('Stok habis!');
            item.quantity++;
        } else {
            cart.push({ id, name, price, quantity: 1, maxStock });
        }
        saveCartV3();
    }

    function confirmSwitch() {
        cart = [];
        $('#vendorWarningModal').modal('hide');
        if (pendingItem) {
            addToCartV3(pendingItem.id, pendingItem.name, pendingItem.price, pendingItem.vendorId, pendingItem.vendorName, pendingItem.maxStock);
            pendingItem = null;
        }
    }

    function saveCartV3() {
        localStorage.setItem('kantin_cart', JSON.stringify(cart));
        localStorage.setItem('kantin_vendor_id', currentVendorId);
        renderCartV3();
    }

    function renderCartV3() {
        let html = '';
        let total = 0;
        cart.forEach((item, index) => {
            total += item.price * item.quantity;
            html += `
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div style="max-width: 70%;">
                        <div class="fw-bold small text-truncate">${item.name}</div>
                        <div class="x-small text-muted">${item.quantity} x Rp ${item.price.toLocaleString()}</div>
                    </div>
                    <div class="btn-group">
                        <button class="btn btn-sm btn-light py-0 px-2" onclick="updateQtyV3(${index}, -1)">-</button>
                        <button class="btn btn-sm btn-light py-0 px-2" onclick="updateQtyV3(${index}, 1)">+</button>
                    </div>
                </div>
            `;
        });
        $('#cartList').html(html || '<div class="text-center py-4 opacity-50">Keranjang Kosong</div>');
        $('#cartTotalV3').text('Rp ' + total.toLocaleString());
        if (cart.length === 0) currentVendorId = null;
    }

    function updateQtyV3(index, delta) {
        if (delta > 0 && cart[index].quantity >= cart[index].maxStock) return;
        cart[index].quantity += delta;
        if (cart[index].quantity <= 0) cart.splice(index, 1);
        saveCartV3();
    }

    function doCheckout() {
        const name = $('#customer_name').val();
        if (!name) return alert('Masukkan nama kamu!');
        if (cart.length === 0) return alert('Keranjang masih kosong!');

        $.post("{{ route('kantin.checkout') }}", {
            _token: "{{ csrf_token() }}",
            vendor_id: currentVendorId,
            nama_pelanggan: name,
            items: cart
        }, function(res) {
            snap.pay(res.snap_token, {
                onSuccess: function() { 
                    localStorage.removeItem('kantin_cart');
                    window.location.href = `/kantin/success/${res.order_id}`; 
                }
            });
        });
    }
</script>
@endpush
