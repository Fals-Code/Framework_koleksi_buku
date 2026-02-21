<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Koleksi Buku - Login</title>
    <link rel="stylesheet" href="{{ asset('assets/vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}" />
    <style>
      .auth.auth-bg-1 { background: linear-gradient(135deg, #7117ea 0%, #ea6060 100%); }
      .auth-form-light { border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1) !important; background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); }
      .btn-gradient-primary { transition: all 0.3s ease; border-radius: 8px; border: none; }
      .btn-gradient-primary:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(182, 109, 255, 0.4); }
      .btn-google { background: #ffffff !important; color: #555 !important; border: 1px solid #ddd !important; transition: all 0.3s ease; font-weight: 500; display: flex; align-items: center; justify-content: center; }
      .btn-google:hover { background: #f8f9fa !important; box-shadow: 0 2px 8px rgba(0,0,0,0.1); transform: translateY(-1px); }
      .form-control-lg { border-radius: 10px !important; font-size: 0.9rem; border: 1px solid #e8eff9; padding: 1rem 1.5rem; }
      .brand-logo img { width: 150px; }
      .modal-content { border-radius: 20px; border: none; }
    </style>
  </head>
  <body>
    <div class="container-scroller">
      <div class="container-fluid page-body-wrapper full-page-wrapper">
        <div class="content-wrapper d-flex align-items-center auth auth-bg-1">
          <div class="row flex-grow">
            <div class="col-lg-4 mx-auto">
              <div class="auth-form-light text-left p-5 shadow-lg">
                <div class="brand-logo text-center">
                  <img src="{{ asset('assets/images/logo.svg') }}" alt="logo">
                </div>
                <h4 class="text-dark font-weight-bold mt-3">Welcome back!</h4>
                <h6 class="font-weight-light text-muted">Please sign in to continue.</h6>
                
                <form class="pt-4" method="POST" action="{{ route('login') }}">
                  @csrf
                  <div class="form-group mb-3">
                    <label class="small font-weight-bold">Email Address</label>
                    <input type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="Email" required autofocus>
                    @error('email')
                        <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                    @enderror
                  </div>
                  <div class="form-group mb-3">
                    <label class="small font-weight-bold">Password</label>
                    <input type="password" class="form-control form-control-lg @error('password') is-invalid @enderror" name="password" placeholder="Password" required>
                  </div>
                  
                  <div class="mt-4 d-grid gap-2">
                    <button type="submit" class="btn btn-gradient-primary btn-lg font-weight-medium">
                      <i class="mdi mdi-login me-2"></i>SIGN IN
                    </button>
                  </div>

                  <div class="text-center mt-3 text-muted small">— Or login with —</div>
                  <div class="mt-3 d-grid gap-2">
                    <a href="{{ route('google.login') }}" class="btn btn-google btn-lg">
                      <img src="https://authjs.dev/img/providers/google.svg" width="18" class="me-2" alt="Google"> Google Account
                    </a>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    @if(session('status') || session('error') || $errors->any())
    <div class="modal fade" id="notifModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow">
          <div class="modal-header {{ session('error') || $errors->any() ? 'bg-danger' : 'bg-primary' }} text-white">
            <h5 class="modal-title">Sistem Notifikasi</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body text-center p-5">
            <i class="mdi {{ session('error') || $errors->any() ? 'mdi-alert-circle-outline text-danger' : 'mdi-checkbox-marked-circle-outline text-success' }}" style="font-size: 60px;"></i>
            <h3 class="mt-3">
                {{ session('error') ? 'Oops!' : 'Informasi' }}
            </h3>
            <p class="text-muted">
                {{ session('status') ?? session('error') ?? 'Terdapat kesalahan pada input Anda.' }}
            </p>
          </div>
          <div class="modal-footer justify-content-center border-0">
            <button type="button" class="btn btn-secondary px-5" data-bs-dismiss="modal">Tutup</button>
          </div>
        </div>
      </div>
    </div>
    @endif

    <script src="{{ asset('assets/vendors/js/vendor.bundle.base.js') }}"></script>
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        var modalEl = document.getElementById('notifModal');
        if (modalEl) {
            var myModal = new bootstrap.Modal(modalEl);
            myModal.show();
        }
      });
    </script>
  </body>
</html>