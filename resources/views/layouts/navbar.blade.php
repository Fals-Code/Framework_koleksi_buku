<nav class="navbar default-layout-navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
  <style>
    .pulse-animation {
        animation: pulse-red 2s infinite;
        box-shadow: 0 0 0 0 rgba(255, 82, 82, 0.7);
        border-radius: 50%;
    }

    @keyframes pulse-red {
        0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(255, 82, 82, 0.7); }
        70% { transform: scale(1); box-shadow: 0 0 0 10px rgba(255, 82, 82, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(255, 82, 82, 0); }
    }
    .nav-loading {
        pointer-events: none;
        opacity: 0.6;
    }
    
    .nav-loading::after {
        content: "";
        display: inline-block;
        width: 12px;
        height: 12px;
        margin-left: 8px;
        border: 2px solid rgba(182, 109, 255, 0.3);
        border-radius: 50%;
        border-top-color: #b66dff;
        animation: spin 0.8s linear infinite;
        vertical-align: middle;
    }

    @keyframes spin { to { transform: rotate(360deg); } }
  </style>

  <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
    <a class="navbar-brand brand-logo" href="{{ route('home') }}">
      <i class="mdi mdi-book-multiple text-primary me-2"></i> <span class="fw-bold text-dark">VOKASI PERPUS</span>
    </a>
    <a class="navbar-brand brand-logo-mini" href="{{ route('home') }}">
      <i class="mdi mdi-book-multiple text-primary"></i>
    </a>
  </div>

  <div class="navbar-menu-wrapper d-flex align-items-stretch">
    <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
      <span class="mdi mdi-menu"></span>
    </button>
    
    <div class="search-field d-none d-md-block">
      <form class="d-flex align-items-center h-100" action="#">
        <div class="input-group">
          <div class="input-group-prepend bg-transparent">
            <i class="input-group-text border-0 mdi mdi-magnify text-muted"></i>
          </div>
          <input type="text" class="form-control bg-transparent border-0" placeholder="Cari buku atau laporan...">
        </div>
      </form>
    </div>

    <ul class="navbar-nav navbar-nav-right">
      <li class="nav-item dropdown">
        <a class="nav-link count-indicator dropdown-toggle" id="notificationDropdown" href="#" data-bs-toggle="dropdown">
          <i class="mdi mdi-bell-outline"></i>
          @if(auth()->user()->unreadNotifications->count() > 0)
            <span class="count-symbol bg-danger pulse-animation"></span>
          @endif
        </a>
        <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list shadow-lg border-0" aria-labelledby="notificationDropdown" style="width: 350px; border-radius: 15px;">
          <h6 class="p-3 mb-0 fw-bold">Aktivitas Terbaru</h6>
          <div class="dropdown-divider"></div>
          <div class="notif-scrollable" style="max-height: 350px; overflow-y: auto;">
            @forelse(auth()->user()->notifications->take(10) as $notif)
              <a class="dropdown-item preview-item py-3" href="javascript:void(0);" 
                 onclick="showNotifDetail('{{ $notif->data['title'] }}', '{{ $notif->data['message'] }}', '{{ $notif->created_at->diffForHumans() }}', '{{ $notif->id }}')">
                <div class="preview-thumbnail">
                  @php
                    $type = $notif->data['type'] ?? 'info';
                    $icon = match($type) { 'success' => 'mdi-check-circle', 'danger' => 'mdi-delete-alert', 'info' => 'mdi-information', default => 'mdi-bell' };
                    $bg = match($type) { 'success' => 'bg-gradient-success', 'danger' => 'bg-gradient-danger', 'info' => 'bg-gradient-info', default => 'bg-gradient-primary' };
                  @endphp
                  <div class="preview-icon {{ $bg }} rounded-circle"><i class="mdi {{ $icon }} text-white"></i></div>
                </div>
                <div class="preview-item-content d-flex align-items-start flex-column justify-content-center">
                  <h6 class="preview-subject font-weight-normal mb-1 text-dark fw-bold">{{ $notif->data['title'] }}</h6>
                  <p class="text-muted ellipsis mb-0 small">{{ $notif->data['message'] }}</p>
                  <small class="text-primary mt-1" style="font-size: 9px;">{{ $notif->created_at->diffForHumans() }}</small>
                </div>
              </a>
              <div class="dropdown-divider"></div>
            @empty
              <div class="p-4 text-center">
                <i class="mdi mdi-bell-off-outline text-muted fs-3"></i>
                <p class="text-muted small mt-2">Belum ada aktivitas tercatat</p>
              </div>
            @endforelse
          </div>
          @if(auth()->user()->notifications->count() > 0)
            <div class="p-2 text-center">
                <form action="{{ route('notifications.clear') }}" method="POST" onsubmit="navBtnLoading(this)">
                    @csrf
                    <button type="submit" class="btn btn-link btn-sm text-primary text-decoration-none fw-bold">
                        Bersihkan Semua Notifikasi
                    </button>
                </form>
            </div>
          @endif
        </div>
      </li>

      <li class="nav-item nav-profile dropdown">
        <a class="nav-link dropdown-toggle" id="profileDropdown" href="#" data-bs-toggle="dropdown">
          <div class="nav-profile-img">
            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=b66dff&color=fff" alt="image">
            <span class="availability-status online"></span>
          </div>
          <div class="nav-profile-text"><p class="mb-1 text-black">{{ Auth::user()->name }}</p></div>
        </a>
        <div class="dropdown-menu navbar-dropdown shadow-sm" aria-labelledby="profileDropdown">
          <a class="dropdown-item py-2" href="{{ route('profile.index') }}" onclick="navLinkLoading(this)">
            <i class="mdi mdi-account-outline me-2 text-primary"></i> Profil Saya
          </a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item py-2" href="{{ route('logout') }}" onclick="handleLogout(this, event)">
            <i class="mdi mdi-logout me-2 text-danger"></i> Keluar 
          </a>
        </div>
      </li>
    </ul>

    <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
      <span class="mdi mdi-menu"></span>
    </button>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
  </div>

  <script>
    function navLinkLoading(link) {
        link.classList.add('nav-loading');
    }
    function navBtnLoading(form) {
        const btn = form.querySelector('button');
        btn.classList.add('nav-loading');
    }
    function handleLogout(element, event) {
        event.preventDefault();
        element.classList.add('nav-loading');
        document.getElementById('logout-form').submit();
    }
  </script>
</nav>