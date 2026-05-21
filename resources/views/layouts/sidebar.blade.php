<nav class="sidebar sidebar-offcanvas" id="sidebar">
  <ul class="nav">
    <li class="nav-item nav-profile mb-2">
      <a href="#" class="nav-link">
        <div class="nav-profile-image">
          <img src="https://ui-avatars.com/api/?name={{ urlencode($name = (session('vendor_name') ?? (Auth::check() ? Auth::user()->name : 'Guest'))) }}&background=b66dff&color=fff" alt="profile" />
          <span class="login-status online"></span>
        </div>
        <div class="nav-profile-text d-flex flex-column" style="min-width: 0; width: 100%;">
          <span class="font-weight-bold mb-1 text-dark">{{ $name }}</span>
        </div>
      </a>
    </li>
    
    <li class="nav-item {{ request()->is('home') ? 'active' : '' }}">
      <a class="nav-link" href="{{ route('home') }}" onclick="btnLoading(this)">
        <span class="menu-title">Dashboard</span>
        <i class="mdi mdi-home menu-icon"></i>
      </a>
    </li>
    
    <li class="nav-item {{ request()->routeIs('kategori.*', 'buku.*', 'barang.*', 'latihan.*') || request()->is('barang-tabel-html') ? 'active' : '' }}">
      <a class="nav-link" data-bs-toggle="collapse" href="#master-data" aria-expanded="{{ request()->routeIs('kategori.*', 'buku.*', 'barang.*', 'latihan.*') || request()->is('barang-tabel-html') ? 'true' : 'false' }}" aria-controls="master-data">
        <span class="menu-title">Data Master</span>
        <i class="menu-arrow"></i>
        <i class="mdi mdi-database menu-icon"></i>
      </a>
      <div class="collapse {{ request()->routeIs('kategori.*', 'buku.*', 'barang.*', 'latihan.*') || request()->is('barang-tabel-html') ? 'show' : '' }}" id="master-data">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"> <a class="nav-link {{ request()->routeIs('kategori.*') ? 'active' : '' }}" href="{{ route('kategori.index') }}">Kategori</a></li>
          <li class="nav-item"> <a class="nav-link {{ request()->routeIs('buku.*') ? 'active' : '' }}" href="{{ route('buku.index') }}">Koleksi Buku</a></li>
          <li class="nav-item"> <a class="nav-link {{ request()->routeIs('barang.*') ? 'active' : '' }}" href="{{ route('barang.index') }}">Tag Harga UMKM</a></li>
          <li class="nav-item"> <a class="nav-link {{ request()->is('barang-tabel-html') ? 'active' : '' }}" href="{{ route('barang.tabel_html') }}">Tabel HTML Biasa</a></li>
          <li class="nav-item"> <a class="nav-link {{ request()->routeIs('latihan.*') ? 'active' : '' }}" href="{{ route('latihan.index') }}">Studi Kasus nomor 4 W4</a></li>
        </ul>
      </div>
    </li>

    <li class="nav-item nav-category mt-3">
       <span class="nav-link text-muted small fw-bold">TRANSAKSI</span>
    </li>

    <li class="nav-item {{ request()->is('kasir') ? 'active' : '' }}">
      <a class="nav-link" href="{{ route('kasir.index') }}" onclick="btnLoading(this)">
        <span class="menu-title">Kasir POS</span>
        <i class="mdi mdi-cash-register menu-icon"></i>
      </a>
    </li>
    
    <li class="nav-item {{ request()->routeIs('scan.barcode') ? 'active' : '' }}">
      <a class="nav-link" href="{{ route('scan.barcode') }}" onclick="btnLoading(this)">
        <span class="menu-title">Scan Barcode</span>
        <i class="mdi mdi-barcode-scan menu-icon"></i>
      </a>
    </li>

    <li class="nav-item nav-category mt-3">
       <span class="nav-link text-muted small fw-bold">NFC PERPUSTAKAAN</span>
    </li>

    <li class="nav-item {{ request()->routeIs('nfc.index') ? 'active' : '' }}">
      <a class="nav-link" href="{{ route('nfc.index') }}" onclick="btnLoading(this)">
        <span class="menu-title">Scan NFC</span>
        <i class="mdi mdi-cellphone-nfc menu-icon"></i>
      </a>
    </li>

    <li class="nav-item {{ request()->routeIs('nfc.write') ? 'active' : '' }}">
      <a class="nav-link" href="{{ route('nfc.write') }}" onclick="btnLoading(this)">
        <span class="menu-title">Tulis Kartu NFC</span>
        <i class="mdi mdi-card-plus menu-icon"></i>
      </a>
    </li>

    <li class="nav-item {{ request()->routeIs('nfc.cards') ? 'active' : '' }}">
      <a class="nav-link" href="{{ route('nfc.cards') }}" onclick="btnLoading(this)">
        <span class="menu-title">Data Kartu NFC</span>
        <i class="mdi mdi-card-account-details menu-icon"></i>
      </a>
    </li>

    <li class="nav-item {{ request()->routeIs('nfc.history') ? 'active' : '' }}">
      <a class="nav-link" href="{{ route('nfc.history') }}" onclick="btnLoading(this)">
        <span class="menu-title">Riwayat NFC</span>
        <i class="mdi mdi-history menu-icon"></i>
      </a>
    </li>


    <li class="nav-item nav-category mt-3">
       <span class="nav-link text-muted small fw-bold">LAPORAN PDF</span>
    </li>

    <li class="nav-item {{ request()->routeIs('laporan.buku') ? 'active' : '' }}">
      <a class="nav-link" href="{{ route('laporan.buku') }}" target="_blank" onclick="notifCetak('Laporan Buku')">
        <span class="menu-title">Laporan Koleksi</span>
        <i class="mdi mdi-file-document-outline menu-icon"></i>
      </a>
    </li>

    <li class="nav-item nav-category mt-3">
       <span class="nav-link text-muted small fw-bold">MODULE KANTIN</span>
    </li>
    
    <li class="nav-item {{ request()->is('kantin') ? 'active' : '' }}">
      <a class="nav-link" href="{{ route('kantin.index') }}" onclick="btnLoading(this)">
        <span class="menu-title">Order Kantin</span>
        <i class="mdi mdi-food menu-icon"></i>
      </a>
    </li>

    <li class="nav-item {{ request()->routeIs('kantin.history') ? 'active' : '' }}">
      <a class="nav-link" href="{{ route('kantin.history') }}" onclick="btnLoading(this)">
        <span class="menu-title">Riwayat Pesanan</span>
        <i class="mdi mdi-history menu-icon"></i>
      </a>
    </li>

    <li class="nav-item {{ request()->routeIs('vendor.*') ? 'active' : '' }}">
      <a class="nav-link" data-bs-toggle="collapse" href="#kantin-mgmt" aria-expanded="{{ request()->routeIs('vendor.*') ? 'true' : 'false' }}" aria-controls="kantin-mgmt">
        <span class="menu-title">Manajemen Kantin</span>
        <i class="menu-arrow"></i>
        <i class="mdi mdi-store menu-icon"></i>
      </a>
      <div class="collapse {{ request()->routeIs('vendor.*') ? 'show' : '' }}" id="kantin-mgmt">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"> <a class="nav-link {{ request()->routeIs('vendor.dashboard') ? 'active' : '' }}" href="{{ route('vendor.dashboard') }}">Dashboard Kantin</a></li>
          <li class="nav-item"> <a class="nav-link {{ request()->routeIs('vendor.menu.*') ? 'active' : '' }}" href="{{ route('vendor.menu.index') }}">Kelola Menu</a></li>
          <li class="nav-item"> <a class="nav-link {{ request()->routeIs('vendor.orders') ? 'active' : '' }}" href="{{ route('vendor.orders') }}">Pesanan Masuk</a></li>
          <li class="nav-item"> <a class="nav-link {{ request()->routeIs('vendor.scan_qr') ? 'active' : '' }}" href="{{ route('vendor.scan_qr') }}">Scan QR Pesanan</a></li>
        </ul>
      </div>
    </li>

    <li class="nav-item nav-category mt-3">
       <span class="nav-link text-muted small fw-bold">CUSTOMER</span>
    </li>

    <li class="nav-item {{ request()->routeIs('customer.*') ? 'active' : '' }}">
      <a class="nav-link" data-bs-toggle="collapse" href="#customer-menu" 
         aria-expanded="{{ request()->routeIs('customer.*') ? 'true' : 'false' }}" 
         aria-controls="customer-menu">
        <span class="menu-title">Customer</span>
        <i class="menu-arrow"></i>
        <i class="mdi mdi-account-group menu-icon"></i>
      </a>
      <div class="collapse {{ request()->routeIs('customer.*') ? 'show' : '' }}" id="customer-menu">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('customer.index') ? 'active' : '' }}" 
               href="{{ route('customer.index') }}">Data Customer</a>
          </li>
          <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('customer.create1') ? 'active' : '' }}" 
               href="{{ route('customer.create1') }}">Tambah Customer 1</a>
          </li>
          <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('customer.create2') ? 'active' : '' }}" 
               href="{{ route('customer.create2') }}">Tambah Customer 2</a>
          </li>
        </ul>
      </div>
    </li>

    @if(!config('midtrans.is_production'))
    <li class="nav-item nav-category mt-3">
       <span class="nav-link text-muted small fw-bold">TESTING TOOLS</span>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="https://simulator.sandbox.midtrans.com/" target="_blank">
        <span class="menu-title">Midtrans Simulator</span>
        <i class="mdi mdi-flask menu-icon text-warning"></i>
      </a>
    </li>
    @endif
  </ul>
</nav>

<script>
  function notifCetak(nama) {
    Swal.fire({
      icon: 'success',
      title: 'Mencetak...',
      text: nama + ' sedang diproses dalam tab baru!',
      timer: 2500,
      showConfirmButton: false,
      toast: true,
      position: 'top-end'
    });
  }
</script>