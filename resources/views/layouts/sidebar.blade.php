<nav class="sidebar sidebar-offcanvas" id="sidebar">
  <ul class="nav">
    <li class="nav-item nav-profile">
      <a href="#" class="nav-link">
        <div class="nav-profile-image">
          <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=b66dff&color=fff" alt="profile" />
          <span class="login-status online"></span>
        </div>
        <div class="nav-profile-text d-flex flex-column" style="min-width: 0; width: 100%;">
          <span class="font-weight-bold mb-1">{{ Auth::user()->name }}</span>
        </div>
        <i class="mdi mdi-bookmark-check text-success nav-profile-badge"></i>
      </a>
    </li>
    
    <li class="nav-item {{ request()->is('home') ? 'active' : '' }}">
      <a class="nav-link" href="{{ route('home') }}" onclick="btnLoading(this)">
        <span class="menu-title">Dashboard</span>
        <i class="mdi mdi-home menu-icon"></i>
      </a>
    </li>
    
    <li class="nav-item {{ request()->routeIs('kategori.*') ? 'active' : '' }}">
      <a class="nav-link" href="{{ route('kategori.index') }}" onclick="btnLoading(this)">
        <span class="menu-title">Kategori</span>
        <i class="mdi mdi-format-list-bulleted menu-icon"></i>
      </a>
    </li>

    <li class="nav-item {{ request()->routeIs('buku.*') ? 'active' : '' }}">
      <a class="nav-link" href="{{ route('buku.index') }}" onclick="btnLoading(this)">
        <span class="menu-title">Buku</span>
        <i class="mdi mdi-book-open-page-variant menu-icon"></i>
      </a>
    </li>

    <li class="nav-item {{ request()->is('cetak-sertifikat') ? 'active' : '' }}">
      <a class="nav-link" href="{{ route('cetak.sertifikat') }}" target="_blank" onclick="notifCetak('Sertifikat')">
        <span class="menu-title">Cetak Sertifikat</span>
        <i class="mdi mdi-certificate menu-icon"></i>
      </a>
    </li>

    <li class="nav-item {{ request()->is('cetak-undangan') ? 'active' : '' }}">
      <a class="nav-link" href="{{ route('cetak.undangan') }}" target="_blank" onclick="notifCetak('Undangan')">
        <span class="menu-title">Cetak Undangan</span>
        <i class="mdi mdi-email-open menu-icon"></i>
      </a>
    </li>
  </ul>
</nav>

<script>
  function notifCetak(nama) {
    Toast.fire({
      icon: 'success',
      title: nama + ' sedang diproses!'
    });
  }
</script>