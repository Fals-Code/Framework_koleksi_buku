<nav class="navbar default-layout-navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
  <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
    <a class="navbar-brand brand-logo" href="{{ route('home') }}"><img src="{{ asset('assets/images/logo.svg') }}" alt="logo" /></a>
    <a class="navbar-brand brand-logo-mini" href="{{ route('home') }}"><img src="{{ asset('assets/images/logo-mini.svg') }}" alt="logo" /></a>
  </div>
  <div class="navbar-menu-wrapper d-flex align-items-stretch">
    <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize"><span class="mdi mdi-menu"></span></button>
    <div class="search-field d-none d-md-block">
      <form class="d-flex align-items-center h-100" action="#">
        <div class="input-group">
          <div class="input-group-prepend bg-transparent"><i class="input-group-text border-0 mdi mdi-magnify"></i></div>
          <input type="text" class="form-control bg-transparent border-0" placeholder="Cari data buku...">
        </div>
      </form>
    </div>
    <ul class="navbar-nav navbar-nav-right">
      <li class="nav-item nav-profile dropdown">
        <a class="nav-link dropdown-toggle" id="profileDropdown" href="#" data-bs-toggle="dropdown">
          <div class="nav-profile-img">
            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=b66dff&color=fff">
            <span class="availability-status online"></span>
          </div>
          <div class="nav-profile-text"><p class="mb-1 text-black">{{ Auth::user()->name }}</p></div>
        </a>
        <div class="dropdown-menu navbar-dropdown" aria-labelledby="profileDropdown">
          <a class="dropdown-item" href="#"><i class="mdi mdi-cached me-2 text-success"></i> Log Aktivitas </a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="mdi mdi-logout me-2 text-primary"></i> Keluar </a>
        </div>
      </li>
      
      <li class="nav-item dropdown">
        <a class="nav-link count-indicator dropdown-toggle" id="notificationDropdown" href="#" data-bs-toggle="dropdown">
          <i class="mdi mdi-bell-outline"></i>
          @if(session()->has('notifications') && count(array_filter(session('notifications'), fn($n) => $n['unread'])))
            <span class="count-symbol bg-danger"></span>
          @endif
        </a>
        <div class="dropdown-menu dropdown-menu-end navbar-dropdown preview-list" aria-labelledby="notificationDropdown" style="width: 320px;">
          <h6 class="p-3 mb-0">Notifikasi</h6>
          <div class="dropdown-divider"></div>
          
          @if(session()->has('notifications') && count(session('notifications')) > 0)
            @foreach(session('notifications') as $index => $n)
              <a class="dropdown-item preview-item py-3" onclick="showNotifDetail({{ $index }})" style="cursor: pointer;">
                <div class="preview-thumbnail">
                  <div class="preview-icon {{ $n['unread'] ? 'bg-success' : 'bg-secondary' }}">
                    <i class="mdi {{ $n['unread'] ? 'mdi-email-outline' : 'mdi-email-open-outline' }}"></i>
                  </div>
                </div>
                <div class="preview-item-content d-flex align-items-start flex-column justify-content-center">
                  <h6 class="preview-subject font-weight-normal mb-1 {{ $n['unread'] ? 'fw-bold text-dark' : 'text-muted' }}">{{ $n['title'] }}</h6>
                  <p class="text-gray ellipsis mb-0 small">{{ $n['time'] }} WIB</p>
                </div>
              </a>
              <div class="dropdown-divider"></div>
            @endforeach
            <div class="p-2 text-center">
                <a href="{{ route('notifications.clear') }}" class="btn btn-link btn-sm text-primary p-0" style="text-decoration:none;">Hapus Semua</a>
            </div>
          @else
            <div class="p-3 text-center"><p class="text-muted small mb-0">Tidak ada aktivitas baru</p></div>
          @endif
        </div>
      </li>

      <li class="nav-item nav-logout d-none d-lg-block">
        <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="mdi mdi-power"></i></a>
      </li>
    </ul>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
  </div> 
</nav>