<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Koleksi Buku</title>
    <link rel="stylesheet" href="{{ asset('assets/vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/ti-icons/css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}" />
    <style>
      .transition-all { transition: all 0.5s ease; }
      
      #preloader {
        position: fixed;
        top: 0; left: 0;
        width: 100%; height: 100%;
        background: #ffffff;
        z-index: 9999;
        display: flex;
        justify-content: center;
        align-items: center;
      }
      
      .loader-circle {
        width: 50px;
        height: 50px;
        border: 5px solid #f3f3f3;
        border-top: 5px solid #b66dff;
        border-radius: 50%;
        animation: spin 1s linear infinite;
      }

      @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
      }

      .btn-loading {
        position: relative;
        color: transparent !important;
        pointer-events: none;
      }

      .btn-loading::after {
        content: "";
        position: absolute;
        width: 16px; height: 16px;
        top: 0; left: 0; right: 0; bottom: 0;
        margin: auto;
        border: 2px solid transparent;
        border-top-color: #ffffff;
        border-radius: 50%;
        animation: spin 0.6s linear infinite;
      }
    </style>
    @stack('style-page')
  </head>
  <body>
    <div id="preloader">
      <div class="text-center">
        <div class="loader-circle mb-2"></div>
        <p class="text-muted small">Memuat Sistem...</p>
      </div>
    </div>

    <div class="container-scroller">
      @include('layouts.navbar')
      <div class="container-fluid page-body-wrapper">
        @include('layouts.sidebar')
        <div class="main-panel">
          <div class="content-wrapper">
            @yield('content')
          </div>
          @include('layouts.footer')
        </div>
      </div>
    </div>

    <div class="modal fade" id="detailNotifModal" tabindex="-1" role="dialog" aria-hidden="true" style="z-index: 1060;">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow">
          <div id="notifHeader" class="modal-header bg-gradient-primary text-white transition-all">
            <h5 class="modal-title d-flex align-items-center">
              <span id="badge-status" class="badge bg-warning text-dark me-2" style="font-size: 10px;">DETAIL</span>
              Notifikasi Sistem
            </h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body text-center p-4">
            <div id="notifIconBox">
              <i class="mdi mdi-bell-ring text-primary mb-3" style="font-size: 50px;"></i>
            </div>
            <h4 id="notifTitleDisplay" class="font-weight-bold"></h4>
            <p id="notifMessageDisplay" class="text-muted mt-3" style="line-height: 1.6;"></p>
            <div class="mt-4 pt-2 border-top">
              <small id="notifTimeDisplay" class="text-muted"></small>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-gradient-primary btn-sm px-4" data-bs-dismiss="modal">Tutup</button>
          </div>
        </div>
      </div>
    </div>

    <script src="{{ asset('assets/vendors/js/vendor.bundle.base.js') }}"></script>
    <script src="{{ asset('assets/vendors/chart.js/chart.umd.js') }}"></script>
    <script src="{{ asset('assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('assets/js/off-canvas.js') }}"></script>
    <script src="{{ asset('assets/js/misc.js') }}"></script>
    <script src="{{ asset('assets/js/settings.js') }}"></script>
    <script src="{{ asset('assets/js/todolist.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.cookie.js') }}"></script>
    
    <script>
      window.addEventListener('load', function() {
        const preloader = document.getElementById('preloader');
        preloader.style.transition = 'opacity 0.5s ease';
        preloader.style.opacity = '0';
        setTimeout(() => {
          preloader.style.display = 'none';
        }, 500);
      });

      function btnLoading(el) {
        el.classList.add('btn-loading');
      }

      function showNotifDetail(index) {
        const notifications = @json(session('notifications') ?? []);
        const n = notifications[index];
        
        if(n) {
          document.getElementById('notifTitleDisplay').innerText = n.title;
          document.getElementById('notifMessageDisplay').innerText = n.message;
          document.getElementById('notifTimeDisplay').innerText = 'Waktu: ' + n.time + ' WIB';
          
          const header = document.getElementById('notifHeader');
          const iconBox = document.getElementById('notifIconBox');
          
          header.className = 'modal-header transition-all ' + (n.unread ? 'bg-gradient-primary' : 'bg-secondary');
          iconBox.innerHTML = n.unread 
            ? '<i class="mdi mdi-bell-ring text-primary mb-3" style="font-size: 50px;"></i>' 
            : '<i class="mdi mdi-bell-check text-muted mb-3" style="font-size: 50px;"></i>';

          var myModal = new bootstrap.Modal(document.getElementById('detailNotifModal'));
          myModal.show();
        }
      }
    </script>
    @stack('script-page')
  </body>
</html>