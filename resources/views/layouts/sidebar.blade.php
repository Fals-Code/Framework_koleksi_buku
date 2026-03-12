<nav class="sidebar sidebar-offcanvas" id="sidebar">
  <ul class="nav">
    <li class="nav-item nav-profile mb-2">
      <a href="#" class="nav-link">
        <div class="nav-profile-image">
          <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=b66dff&color=fff" alt="profile" />
          <span class="login-status online"></span>
        </div>
        <div class="nav-profile-text d-flex flex-column" style="min-width: 0; width: 100%;">
          <span class="font-weight-bold mb-1 text-dark">{{ Auth::user()->name }}</span>
        </div>
      </a>
    </li>
    
    <li class="nav-item {{ request()->is('home') ? 'active' : '' }}">
      <a class="nav-link" href="{{ route('home') }}" onclick="btnLoading(this)">
        <span class="menu-title">Dashboard</span>
        <i class="mdi mdi-home menu-icon"></i>
      </a>
    </li>
    
    <li class="nav-item nav-category mt-2">
       <span class="nav-link text-muted small fw-bold">DATA MASTER</span>
    </li>
    
    <li class="nav-item {{ request()->routeIs('kategori.*') ? 'active' : '' }}">
      <a class="nav-link" href="{{ route('kategori.index') }}" onclick="btnLoading(this)">
        <span class="menu-title">Kategori</span>
        <i class="mdi mdi-format-list-bulleted menu-icon"></i>
      </a>
    </li>

    <li class="nav-item {{ request()->routeIs('buku.*') ? 'active' : '' }}">
      <a class="nav-link" href="{{ route('buku.index') }}" onclick="btnLoading(this)">
        <span class="menu-title">Koleksi Buku</span>
        <i class="mdi mdi-book-open-page-variant menu-icon"></i>
      </a>
    </li>

    <li class="nav-item {{ request()->routeIs('barang.*') ? 'active' : '' }}">
      <a class="nav-link" href="{{ route('barang.index') }}" onclick="btnLoading(this)">
        <span class="menu-title">Tag Harga UMKM</span>
        <i class="mdi mdi-tag-multiple menu-icon"></i>
      </a>
    </li>

<li class="nav-item {{ request()->is('barang-tabel-html') ? 'active' : '' }}">
  <a class="nav-link" href="{{ route('barang.tabel_html') }}" onclick="btnLoading(this)">
    <span class="menu-title">Tabel HTML Biasa</span>
    <i class="mdi mdi-table-large menu-icon"></i>
  </a>
</li>

<li class="nav-item {{ request()->routeIs('latihan.*') ? 'active' : '' }}">
  <a class="nav-link" href="{{ route('latihan.index') }}" onclick="btnLoading(this)">
    <span class="menu-title">Warehouse System</span>
    <i class="mdi mdi-archive menu-icon "></i>
  </a>
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

    <li class="nav-item nav-category mt-3">
       <span class="nav-link text-muted small fw-bold">LAPORAN PDF</span>
    </li>

    <li class="nav-item {{ request()->routeIs('laporan.buku') ? 'active' : '' }}">
      <a class="nav-link" href="{{ route('laporan.buku') }}" target="_blank" onclick="notifCetak('Laporan Buku')">
        <span class="menu-title">Laporan Koleksi</span>
        <i class="mdi mdi-file-document-outline menu-icon"></i>
      </a>
    </li>
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